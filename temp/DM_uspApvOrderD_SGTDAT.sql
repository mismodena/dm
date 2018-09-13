USE [dm]
GO

/****** Object:  StoredProcedure [dbo].[DM_uspApvOrderD_SGTDAT]    Script Date: 11/24/2017 16:07:28 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO



CREATE PROCEDURE [dbo].[DM_uspApvOrderD_SGTDAT]
	(
--declare 
		@orderid nvarchar(50),
		@orduniq decimal(19,0),
		@audtuser char(8),
		@totalorder decimal(19,3),
		@subtotalorder decimal(19,3),
		@boolLimit smallint
/*set @orderid = 'IM/MS-1017-09645' 
set @orduniq = 28974090 
set @audtuser = 'ADMIN' 
set @totalorder = 21743000.000 
set @subtotalorder =  21743000.000 
set @boolLimit =  1
*/

	)
AS
BEGIN TRANSACTION
	SET NOCOUNT ON;
	
	declare @linenum smallint		
	declare @audtdate decimal(9,0)	
	declare @audttime decimal(9,0)		
	declare @audtorg char(6)		
	declare @linetype smallint		
	declare @item char(24)
	declare @fmtitemno char(30)
	declare @itemdesc char(60)		
	declare @location char(6)
	declare @expdate decimal(9)	
	declare @qtyordered decimal(19,4)
	declare @diskon decimal(9,5)
	declare @harga decimal(19,6) 
	declare @pricelist varchar(10)
	declare @custtype decimal
	declare @diskon_per_invoice decimal(19,3), @keterangan_per_item varchar(60), @codeterr varchar(10), @kategori_gift varchar(10), @acctset varchar(5), @approval_user varchar(50), @location_asal char(6)
	declare @harga_total decimal(19,3)
	declare @detailnum smallint
	declare @linenum_increasedby smallint
	
	set @linenum_increasedby = 32
	set @linenum = 0
	set @detailnum=0
	
	-- Fajar: Trap untuk overlimit. Apabila melebihi dari overlimit maka commited = 0 dan tidak dimasukkan kedalam iciloc --
	declare /*@boolLimit bit,*/ @customer varchar(50), @tbase1_header decimal(19,3), @tiamount1_header decimal(19,3)
	declare @idGrp varchar(10), @checkFocus int, @salesDealer varchar(10), @tmpCategory varchar(10)
	
	select @customer = customer, @tbase1_header = TBASE1, @tiamount1_header = TIAMOUNT1 from SGTDAT..oeordh where orduniq = @orduniq
	select @idGrp = idgrp from SGTDAT..arcus where IDCUST = @customer

	select @tmpCategory = c.approval_user,  @salesDealer = b.kode_sales
					from [order] a
					left join [user] b on a.[user_id] = b.[user_id]
					left join [user] c on b.bm = c.kode_sales
					where order_id = @orderid
					
	--select @boolLimit = is_overlimit from SGTDAT.dbo.ufnCekOverLimit(@totalorder, @customer)
	-- END TRAP --
	
	-- cari jumlah baris order_item
	declare @jumlah_row_order_item int, @row_order_item int, @total_order_tanpa_diskon	decimal(19,3), @header_tbase1 decimal(19,3), @header_tiamount1 decimal(19,3);
	select 
	@jumlah_row_order_item = COUNT(1), @row_order_item = 0, 
	@total_order_tanpa_diskon = sum( ( (a.harga* a.kuantitas) - ( case when a.diskon_total_persen <= 100 then (a.harga * a.kuantitas * a.diskon_total_persen / 100) else a.diskon_total end ) ) )  ,
	@header_tbase1 = @tbase1_header, --round(@totalorder / 1.1, 0) 
	@header_tiamount1 =  @tiamount1_header --round( round(@totalorder / 1.1, 0) / 10, 0 )
	from 
	dbo.ufn_daftar_order_item_disetujui(@orderid) a
	/*
	(
	select order_id, user_id, item_seq, item_id, harga, kuantitas, diskon, tambahan_diskon, diskon_total, diskon_total_persen, convert(varchar(max), keterangan_order_item) keterangan, convert(varchar, paketid) paketid, urutan_parameter from order_item where order_id = @orderid union
	( select order_id, user_id, '' item_seq, item_id, harga, kuantitas, harga diskon, 0 tambahan_diskon, harga diskon_total, 100 diskon_total_persen, convert(varchar(max), y.singkatan) keterangan, 
	convert(varchar, x.diskon_id) paketid, 0 urutan_parameter from order_diskon_freeitem x, diskon y where order_id = @orderid and x.diskon_id = y.diskon_id )  )
	
	a inner join SGTDAT.dbo.icitem b on a.item_id=b.itemno 
	inner join [order] c on a.order_id=c.order_id
	inner join [user] d on c.user_id=d.user_id
	inner join SGTDAT.dbo.arcus e on c.dealer_id=e.idcust
	where a.order_id=@orderid
	*/
	
	declare @tbase1_dialokasikan decimal(19, 3), @tiamount1_dialokasikan decimal(19, 3)
	set @tbase1_dialokasikan = 0
	set @tiamount1_dialokasikan = 0

	
	declare cDetails cursor for
	/*
	select 
		mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as audtdate
		,mesdb.dbo.fnSetAccpacTime(convert(varchar(12),getdate(),108)) as audttime
		,'SGTDAT' as audtorg
		,1 as linetype
		,b.itemno
		,b.fmtitemno as item
		,b.[desc] as itemdesc
		,d.cabang as location
		,mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),106)) as expdate
		,a.kuantitas as qtyordered
		,a.diskon_total_persen
		,a.harga
		,e.priclist
		,e.custtype
		,c.diskon diskon_per_invoice
		,a.kuantitas * a.harga harga_total
	from order_item a inner join SGTDAT.dbo.icitem b on a.item_id=b.itemno 
	inner join [order] c on a.order_id=c.order_id
	inner join [user] d on c.user_id=d.user_id
	inner join SGTDAT.dbo.arcus e on c.dealer_id=e.idcust
	where a.order_id=@orderid
	*/
	select 
		mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as audtdate
		,mesdb.dbo.fnSetAccpacTime(convert(varchar(12),getdate(),108)) as audttime
		,'SGTDAT' as audtorg
		,1 as linetype
		,b.itemno
		,b.fmtitemno as item
		,b.[desc] as itemdesc
		,a.gudang as location
		,mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),106)) as expdate
		,a.kuantitas as qtyordered
		,a.diskon_total_persen
		,a.harga
		,e.priclist
		,e.custtype
		,c.diskon diskon_per_invoice
		,a.kuantitas * a.harga harga_total,
		left(isnull(a.keterangan+(case when isnull(a.keterangan,'')='' then '' else case when isnull(a.keterangan_diskon_tambahan, '') = '' then '' else ',' end end)+isnull(a.keterangan_diskon_tambahan, ''), ''), 60) keterangan_per_item
		,e.codeterr, isnull(a.kategori_gift, '') kategori_gift, b.cntlacct, case when isnull(f.approval_user, '') = '' then d.approval_user else f.approval_user end, c.gudang
	from 
	/*
	(
	select order_id, user_id, item_seq, item_id, harga, kuantitas, diskon, tambahan_diskon, diskon_total, diskon_total_persen, convert(varchar(max), keterangan_order_item) keterangan, convert(varchar, paketid) paketid, urutan_parameter from order_item union
	( select order_id, user_id, '' item_seq, item_id, harga, kuantitas, (harga * kuantitas) diskon, 0 tambahan_diskon, (harga * kuantitas) diskon_total, 100 diskon_total_persen, convert(varchar(max), y.singkatan) keterangan, 
	convert(varchar, x.diskon_id) paketid, 0 urutan_parameter from order_diskon_freeitem x, diskon y where x.diskon_id = y.diskon_id )  )
	*/
	dbo.ufn_daftar_order_item_disetujui( @orderid )
	a inner join SGTDAT.dbo.icitem b on a.item_id=b.itemno 
	inner join [order] c on a.order_id=c.order_id
	inner join [user] d on c.user_id=d.user_id
	inner join SGTDAT.dbo.arcus e on c.dealer_id=e.idcust
	left outer join (select distinct kode_sales, approval_user from [user] where approval_user is not null ) f on f.kode_sales = d.bm
	where a.order_id=@orderid
	
	open cDetails
	fetch next from cDetails into 
		@audtdate
		,@audttime
		,@audtorg
		,@linetype
		,@item
		,@fmtitemno
		,@itemdesc
		,@location	
		,@expdate
		,@qtyordered
		,@diskon
		,@harga
		,@pricelist
		,@custtype
		,@diskon_per_invoice
		,@harga_total
		,@keterangan_per_item
		,@codeterr
		,@kategori_gift
		,@acctset
		,@approval_user
		,@location_asal
	
	declare		@category varchar(10), 
				@diskon_nominal decimal(19,3), 
				@diskon_nominal_total decimal(19,3),
				@diskon_nominal_total_semua_item decimal(19,3),
				@pripercent decimal(9,5), 
				@unitcost decimal(19,6), 
				@mostrec decimal(19,6),
				@extocost decimal(19,3),
				@pribasprc decimal(19,6),
				@qtycommitted decimal(19,4)
	
	set @diskon_nominal_total_semua_item=0
		
	
	while @@FETCH_STATUS = 0
	begin

		set @qtycommitted = @qtyordered
		if @boolLimit = 1 
		begin			
			set @qtycommitted = 0
		end
		
		set @linenum = @linenum + @linenum_increasedby
		set @detailnum=@detailnum+1

		set @category = dbo.ufn_kategori_barang(
				@location_asal , 
				@approval_user , 
				@kategori_gift , 
				@salesDealer , 
				@idGrp , 
				@item , 
				@tmpCategory ,
				@customer ,
				@codeterr
				)
		
		
		set @diskon_nominal=@harga*(@diskon/100)
		set @diskon_nominal_total = round(@diskon_nominal*@qtyordered,0)
		set @diskon_nominal_total_semua_item=@diskon_nominal_total_semua_item + @diskon_nominal_total
		
		select @pripercent=case @custtype 
			when 1 then prcntlvl1 
			when 2 then prcntlvl2 
			when 3 then prcntlvl3
			when 4 then prcntlvl4
			when 5 then prcntlvl5
			else 0 end from SGTDAT..icpric where itemno=@item and pricelist=@pricelist
		set @pripercent = case ISNULL(@pripercent, -1) when -1 then 0 else @pripercent end
		
		--select @unitcost=TOTALCOST/QTYONHAND, @mostrec=RECENTCOST from 
		--	SGTDAT..iciloc where ITEMNO=@item and LOCATION=@location
			
		select @unitcost=case when QTYONHAND>0 then TOTALCOST/QTYONHAND else 0 end, @mostrec=RECENTCOST from 
		[SGTDAT]..iciloc where ITEMNO=@item and LOCATION=@location			
		
		select @extocost=round(@unitcost * @qtyordered,0)
		
		set @pribasprc=@harga/(1-(@pripercent/100))
		
		/*
			Implementasi PPn
			
			- TClass1 = 2
			- TINCLUDED = 1
			- TBASE1 = net - 10%
			- TAMOUNT1 = 10% * net
			- TRATE1 = 10
		
		*/
		declare @tbase1 decimal(19, 3), @tiamount1 decimal(19, 3);
		set @row_order_item = @row_order_item + 1
		
		if( @row_order_item >= @jumlah_row_order_item ) begin
			set @tbase1 = @header_tbase1 - @tbase1_dialokasikan
			set @tiamount1 = @header_tiamount1 - @tiamount1_dialokasikan --case when @tiamount1_dialokasikan <= 0 then @tbase1 else @tiamount1_dialokasikan end
		end
		else begin
			set @tbase1 = round( @header_tbase1 * ( ( @harga * @qtyordered ) - @diskon_nominal_total ) / @total_order_tanpa_diskon, 0 )			
			set @tbase1_dialokasikan = @tbase1_dialokasikan + @tbase1						

			set @tiamount1 = round( @tbase1 / 10, 0 )
			set @tiamount1_dialokasikan = @tiamount1_dialokasikan + @tiamount1
		end
		
		declare @tanpaPajak decimal(19,3)
		set @tanpaPajak = @tbase1
		--select @tanpaPajak = round((@harga_total - @diskon_nominal_total) / 1.1,0)
		declare @pajak decimal(19,3)
		set @pajak = @tiamount1
		--select @pajak = (@harga_total - @diskon_nominal_total) - @tanpaPajak

		exec [SGTDAT].[dbo].[sp_addWOdetil_Post_DM]
			@orduniq			
			,@linenum			
			,@audtdate				
			,@audttime					
			,@audtuser				
			,@audtorg
			,@linetype			
			,@fmtitemno
			,'' /*misccharge*/		
			,@itemdesc					
			,@acctset /*acctset*/			
			,0 /*'false' usercostmd*/
			,@pricelist /*pricelist*/	
			,@category /*category*/	
			,@location				
			,'' /*pickseq*/				
			,@expdate			
			,1 /*'true' stockitem*/
			,@qtyordered		
			,0 /*qtyshipped*/	
			,@qtyordered /*qtybackord*/		
			,0 /*qtyshptodt*/			
			,@qtyordered /*origqty*/			
			,0 /*qtypo*/
			,'UNIT' /*ordunit*/		
			,1 /*unitconv*/		
			,@harga /*unitprice*/		
			,0 /*'false' priceover*/	
			,@unitcost /*unitcost*/			
			,@mostrec /*mostrec*/		
			,0 /*stdcost*/		
			,0 /*cost1*/		
			,0 /*cost2*/			
			,0 /* 6 unitprcdec*/			
			,'UNIT' /*priceunit*/		
			,@harga /*priuntprc*/
			,1 /*priuntconv*/	
			,@pripercent /*pripercent*/	
			,0 /*priamount*/		
			,'UNIT' /*baseunit*/			
			,@pribasprc /*pribasprc*/		
			,1 /*pribasconv*/
			,'UNIT' /*costunit*/	
			,@unitcost /*cosuntcst*/	
			,1 /*cosuntconv*/		
			,0 /*extoprice*/			
			,@extocost /*extocost*/			
			,@harga_total /*extinvmisc*/
			,@diskon_nominal_total /*invdisc -- ini diisi diskon*/		
			,0 /*exticost*/		
			,0 /*'false' extover*/	
			,0 /*unitweight*/			
			,0 /*extweight*/		
			,0 /*complete*/
			,1 /*'false' addtoiloc*/ 
			,0 /*saleslost*/	
			,'PPN1' /*tauth1*/		
			,'' /*tauth2*/				
			,'' /*tauth3*/			
			,'' /*tauth4*/
			,'' /*tauth5*/		
			,2 /*tclass1*/		
			,0 /*tclass2*/			
			,0 /*tclass3*/				
			,0 /*tclass4*/			
			,0 /*tclass5*/
			,1 /*'false' tincluded1*/				
			,0/*'false' tincluded2*/
			,0/*'false' tincluded3*/	
			,0/*'false' tincluded4*/
			,0/*'false' tincluded5*/		
			,@tanpaPajak /*tbase1*/		
			,0 /*tbase2*/		
			,0 /*tbase3*/			
			,0 /*tbase4*/				
			,0 /*tbase5*/			
			,@pajak /*tamount1*/
			,0 /*tamount2*/		
			,0 /*tamount3*/		
			,0 /*tamount4*/			
			,0 /*tamount5*/				
			,10 /*trate1*/			
			,0 /*trate2*/
			,0 /*trate3*/		
			,0 /*trate4*/		
			,0 /*trate5*/			
			,'' /*miscacct*/			
			,@detailnum /*detailnum*/		
			,'' /*haveserial*/
			,'' /*comminst*/	
			,'' /*glnonstkcr*/
			,@diskon
			,@diskon_per_invoice
			,@keterangan_per_item
			,@qtycommitted		
			
		--print @linenum
		--print @orduniq
		
		-- untuk update iciloc		
		--update [SGTDAT].[dbo].iciloc set qtycommit=qtycommit+@qtycommitted, 
		set @qtycommitted = (select qtycommit from SGTDAT.dbo.OEORDD where ITEM = @fmtitemno and ORDUNIQ = @orduniq and LINENUM = @linenum)
		update [SGTDAT].[dbo].iciloc set 
			qtycommit=qtycommit+ISNULL(@qtycommitted,0), 
			QTYSALORDR=QTYSALORDR+ISNULL(@qtycommitted,0) 
		where itemno=@item and location=@location
		
		
/*		declare @qtysalordr decimal(19,4)
		set @qtycommitted = (select sum(qtycommit) from SGTDAT.dbo.OEORDD where ITEM = @fmtitemno and LOCATION = @location)
		set @qtysalordr = (select SUM(QTYORDERED) from SGTDAT.dbo.OEORDD where ITEM = @fmtitemno and LOCATION = @location)
		update [SGTDAT].[dbo].iciloc set 
			qtycommit=ISNULL(@qtycommitted,0), 
			QTYSALORDR=ISNULL(@qtysalordr,0) 
		where itemno=@item and location=@location*/

		
		
		fetch next from cDetails into 
			@audtdate
			,@audttime
			,@audtorg
			,@linetype
			,@item
			,@fmtitemno
			,@itemdesc
			,@location
			,@expdate
			,@qtyordered
			,@diskon
			,@harga
			,@pricelist
			,@custtype
			,@diskon_per_invoice
			,@harga_total
			,@keterangan_per_item		
			,@codeterr
			,@kategori_gift
			,@acctset
			,@approval_user
			,@location_asal
	end
	close cDetails
	deallocate cDetails
	
	update SGTDAT..OEORDH1 set ITEMDISTOT=@diskon_nominal_total_semua_item where ORDUNIQ=@orduniq
	
	-- update location di OEORDH
	update SGTDAT..OEORDH set LOCATION=@location where ORDUNIQ=@orduniq
	
	
IF @@ERROR <> 0
	BEGIN
		ROLLBACK
		RAISERROR ('ERRORX', 16, 1)
		RETURN
	END
COMMIT
--select * from [mobile_sales].[dbo].[order] 

--begin tran
--exec [mobile_sales].[dbo].[uspApvOrderD] 'ORD-00001',12609027,'ADM'
--rollback tran






GO

