USE [dm]
GO

/****** Object:  StoredProcedure [dbo].[DM_uspApvOrderH_Post_split_SGTDAT]    Script Date: 11/24/2017 16:09:08 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Author		:	Wong Tjliek, Isokuiki
-- Create date	:	20130507
-- Description	:	transfer order header from mobile_sales to SGTDAT
-- =============================================
CREATE PROCEDURE [dbo].[DM_uspApvOrderH_Post_split_SGTDAT]
	(
		@orderid nvarchar(50),
		@audtuser nvarchar(6),
		@sta smallint,
		@totalorder decimal(19,3),
		@subtotalorder decimal(19,3),
		@subtotal_nodisc decimal(19,3)
	)
AS

	declare @tempOrder decimal(19,3)
	declare @rCount int
	set @rCount = 0
	set @tempOrder = 0
	
	select @tempOrder = sum((harga - (harga*(diskon_total_persen/100))) * kuantitas) from order_item_split 
		where dbo.sambung_order_id(order_id, order_id_split, '-') = @orderid
	
	select @rCount = COUNT(*) from orderExec where order_id = @orderid
	
	if @rCount = 0
	begin
		insert into orderExec 
			values (@audtuser, @orderid, @sta, @totalorder, @subtotalorder, @subtotal_nodisc)
	end
	
BEGIN TRANSACTION

/*
declare @orderid varchar(100), @audtuser varchar(6), @sta smallint; 
set @orderid='M-usr-00002-914-00108'; set @audtuser='MSAS'; set @sta=1;
*/
	SET NOCOUNT ON;
	--header
	declare @orduniq decimal(19,0)
	declare @audtdate decimal(9,0)
	declare @audttime decimal(9,0)
	declare @audtorg char(6)
	declare @ordnumber char(22)
	declare @customer char(12)
	declare @custgroup char(6)
	declare @bilname char(60)
	declare @biladdr1 char(60)
	declare @biladdr2 char(60)
	declare @biladdr3 char(60)
	declare @biladdr4 char(60)
	declare @bilcity char(30)
	declare @bilstate char(30)
	declare @bilzip char(20)
	declare @bilcountry char(30)
	declare @bilphone char(30)
	declare @bilfax char(30)
	declare @bilcontact char(60)
	declare @shpname char(60)
	declare @shpaddr1 char(60)
	declare @shpaddr2 char(60)
	declare @shpaddr3 char(60)
	declare @shpaddr4 char(60)
	declare @shpcity char(30)
	declare @shpstate char(30)
	declare @shpzip char(20)
	declare @shpcountry char(30)
	declare @shpphone char(30)
	declare @shpfax char(30)
	declare @shpcontact char(60)
	declare @ponumber char(22)
	declare @territory char(6)
	declare @terms char(6)
	declare @orddate decimal(9,0)
	declare @expdate decimal(9,0)
	declare @qtexpdate decimal(9,0)
	declare @ordfiscyr char(4)
	declare @ordfisper char(2)
	declare @lastpost decimal(9,0)
	declare @orhomecurr char(3)
	declare @orsourcurr char(3)
	declare @orratedate decimal(9,0)
	declare @salesper1 char(8)
	declare @salesper2 char(8)
	declare @salesper3 char(8)
	declare @salesper4 char(8)
	declare @salesper5 char(8)
	declare @taxgroup char(12)
	declare @tauth1 char(12)
	declare @tauth2 char(12)
	declare @shipdate decimal(9,0)
	declare @invdate decimal(9,0)
	declare @invfiscyr char(4)
	declare @invfiscper char(2)
	declare @paymntasof decimal(9,0)
	declare @inhomecurr char(3)
	declare @insourcurr char(3)
	declare @inratedate decimal(9,0)
	declare @ordersourc smallint
	declare @gudang char(10)
	declare @priclist char(5)
	declare @invdiscamt decimal(19,3)
		
	set @totalorder = ROUND(@totalorder,0)
	
	declare @tclass1 smallint
	-- set @tclass1=1
	set @tclass1 = 2 -- implementasi ppn
	
	declare @CUSACCTSET char(10)
	set @CUSACCTSET='AR1'
	declare @salesplt1 smallint
	set @salesplt1=100
	declare @diskon decimal(9,5)
	
	declare @ordlines int, @nextdtlnum int
	select @ordlines=COUNT(1) from dbo.ufn_daftar_order_item_split(@orderid)
	set @nextdtlnum=@ordlines+1
	
	declare @tbase1 decimal(19,3)
	--set @tbase1=@totalorder*@ordlines
	set @tbase1= round(@totalorder / 1.1,0) -- tax base = total - 10%
	
	/*
		Implementasi PPn
		
		- INVNETNOTX = TOTAL - 10%
		- INVITAXTOT = 10% * TOTAL 
		- TCLASS = 2
		- TBASE1 = TOTAL - 10%
		- TIAMMOUNT1 = 10% * TOTAL
	
	*/
	
	declare @tanpaPajak decimal(19,3)
	select @tanpaPajak = @tbase1
	declare @pajak decimal(19,3)
	select @pajak = @totalorder - @tbase1
	
	--if(@sta=1)
	--begin	
		set @orduniq = (select max(orduniq)+ 1 as xx from SGTDAT.dbo.oeordh)
		
		declare cHeader cursor for
			select 
				mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as audtdate
				,mesdb.dbo.fnSetAccpacTime(convert(varchar(12),getdate(),108)) as audttime
				,'SGTDAT' as audtorg
				,dbo.sambung_order_id(a.order_id, a.order_id_split, '-') as ordnumber
				,a.dealer_id as customer
				,b.idgrp as custgroup
				,case when isnull(d.nama_tagih, '') = '' then b.namecust else upper(d.nama_kirim) end as bilname
				,case when isnull(d.alamat_tagih, '') = '' then b.textstre1 else upper(substring(d.alamat_kirim, 1, 60)) end as biladdr1
				,case when isnull(d.alamat_tagih, '') = '' then b.textstre2 else upper(substring(d.alamat_kirim, 61, 60)) end as biladdr2
				,case when isnull(d.alamat_tagih, '') = '' then b.textstre3 else upper(substring(d.alamat_kirim, 121, 60)) end as biladdr3
				,case when isnull(d.alamat_tagih, '') = '' then b.textstre4 else upper(substring(d.alamat_kirim, 181, 60)) end as biladdr4
				,case when isnull(d.kota_tagih, '') = '' then b.namecity else upper(d.kota_kirim) end as bilcity
				,case when isnull(d.propinsi_tagih, '') = '' then b.codestte else upper(d.propinsi_kirim) end as bilstate
				,b.codepstl as bilzip
				,b.codectry as bilcountry
				,case when isnull(d.telp_tagih, '') = '' then b.textphon1 else upper(d.telp_kirim) end as bilphone
				,case when isnull(d.hp_tagih, '') = '' then b.textphon2 else upper(d.hp_kirim) end as bilfax
				,b.namectac as bilcontact
				,case when isnull(d.nama_kirim, '') = '' then b.namecust else upper(d.nama_kirim) end as shpname
				,case when isnull(d.alamat_kirim, '') = '' then b.textstre1 else upper(substring(d.alamat_kirim, 1, 60)) end as shpaddr1
				,case when isnull(d.alamat_kirim, '') = '' then b.textstre2 else upper(substring(d.alamat_kirim, 61, 60)) end as shpaddr2
				,case when isnull(d.alamat_kirim, '') = '' then b.textstre3 else upper(substring(d.alamat_kirim, 121, 60)) end as shpaddr3
				,case when isnull(d.alamat_kirim, '') = '' then b.textstre4 else upper(substring(d.alamat_kirim, 181, 60)) end as shpaddr4
				,case when isnull(d.kota_kirim, '') = '' then b.namecity else upper(d.kota_kirim) end as shpcity
				,case when isnull(d.propinsi_kirim, '') = '' then b.codestte else upper(d.propinsi_kirim) end as shpstate
				,b.codepstl as shpzip
				,b.codectry as shpcountry
				,case when isnull(d.telp_kirim, '') = '' then b.textphon1 else upper(d.telp_kirim) end as shpphone
				,case when isnull(d.hp_kirim, '') = '' then b.textphon2 else upper(d.hp_kirim) end as shpfax
				,b.namectac as shpcontact
				,case when isnull(d.po_referensi, '') = '' then '' else upper(d.po_referensi) end as ponumber
				,b.codeterr as territory
				,b.codeterm as terms
				,mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as orddate
				,mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as expdate
				,mesdb.dbo.fnSetAccpacDate(convert(varchar(12),dateadd(day, 30, getdate()),101)) as qtexpdate
				,year(getdate()) as ordfiscyr
				,month(getdate()) as ordfisper
				,mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as lastpost
				,b.codecurn as orhomecurr
				,b.codecurn as orsourcurr
				,mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as orratedate
				,/*b.codeslsp1*/ c.kode_sales as salesper1
				,b.codeslsp2 as salesper2
				,b.codeslsp3 as salesper3
				,b.codeslsp4 as salesper4
				,b.codeslsp5 as salesper5
				,b.codetaxgrp as taxgroup
				,b.codetaxgrp as tauth1
				,mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as shipdate
				,mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as invdate
				,year(getdate()) as invfiscyr, month(getdate()) invfiscper
				,mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as paymntasof
				,b.codecurn as inhomecurr
				,b.codecurn as insourcurr
				,mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as inratedate
				,0 as ordersourc
				,a.gudang as gudang
				,b.priclist
				,a.diskon
				,@subtotalorder * a.diskon / 100 invdiscamt
			from [order_split] a inner join SGTDAT.dbo.arcus b on a.dealer_id=b.idcust
				inner join [user] c on a.user_id = c.user_id
				inner join [order] d on a.order_id = d.order_id and a.user_id = d.user_id
			where dbo.sambung_order_id(a.order_id, a.order_id_split, '-')=@orderid
		open cHeader
		fetch next from cHeader into 
			@audtdate
			,@audttime
			,@audtorg
			,@ordnumber
			,@customer
			,@custgroup
			,@bilname
			,@biladdr1
			,@biladdr2
			,@biladdr3
			,@biladdr4
			,@bilcity
			,@bilstate
			,@bilzip
			,@bilcountry
			,@bilphone
			,@bilfax
			,@bilcontact
			,@shpname
			,@shpaddr1
			,@shpaddr2
			,@shpaddr3
			,@shpaddr4
			,@shpcity
			,@shpstate
			,@shpzip
			,@shpcountry
			,@shpphone
			,@shpfax
			,@shpcontact
			,@ponumber
			,@territory
			,@terms
			,@orddate
			,@expdate
			,@qtexpdate
			,@ordfiscyr
			,@ordfisper
			,@lastpost
			,@orhomecurr
			,@orsourcurr
			,@orratedate
			,@salesper1
			,@salesper2
			,@salesper3
			,@salesper4
			,@salesper5
			,@taxgroup
			,@tauth1
			,@shipdate
			,@invdate
			,@invfiscyr
			,@invfiscper
			,@paymntasof
			,@inhomecurr
			,@insourcurr
			,@inratedate
			,@ordersourc
			,@gudang
			,@priclist
			,@diskon
			,@invdiscamt
			
		while @@FETCH_STATUS = 0
		begin
			--exec procedure insert order to SGTDAT.dbo.oeordh
			select @invdiscamt = ROUND(@invdiscamt,0) 

			exec SGTDAT.dbo.sp_addWO_Post_DM
				@orduniq
				,@audtdate
				,@audttime
				,@audtuser
				,@audtorg
				,@ordnumber
				,@customer
				,@custgroup
				,@bilname
				,@biladdr1
				,@biladdr2
				,@biladdr3
				,@biladdr4
				,@bilcity
				,@bilstate
				,@bilzip
				,@bilcountry
				,@bilphone
				,@bilfax
				,@bilcontact
				,'' /*shipto*/
				,@shpname
				,@shpaddr1
				,@shpaddr2
				,@shpaddr3
				,@shpaddr4
				,@shpcity
				,@shpstate
				,@shpzip
				,@shpcountry
				,@shpphone
				,@shpfax
				,@shpcontact
				,'1' /*custdisc*/
				,@priclist /*pricelist*/
				,@ponumber
				,@territory
				,@terms
				,@totalorder /*termttldue*/
				,'0' /*discavail*/
				,'0' /*termoverrd*/
				,@orderid /*MOBILE_SALES*//*reference*/
				,'1' /*[type]*/
				,@orddate
				,@expdate
				,@qtexpdate
				,@ordfiscyr
				,@ordfisper /*ordfiscper*/
				,'' /*shipvia*/
				,'' /*viadesc*/
				,'' /*lastinvnum*/
				,'0' /*numinvoice*/
				,'' /*fob*/
				,'' /*template*/
				,@gudang /*location*/
				,'0' /*onhold*/
				,'' /*[desc]*/
				,'' /*comment*/
				,'1' /*printstat*/
				,@lastpost
				,'0' /*ornoprepay*/
				,'0' /*overcredit*/
				,'0' /*approvelmt*/
				,'' /*approveby*/
				,'0' /*shiplabel*/
				,'0' /*lblprinted*/
				,@orhomecurr
				,'SP' /*orratetype*/
				,@orsourcurr
				,@orratedate
				,'1' /*orrate*/
				,'0' /*orspread*/
				,'3' /*ordatemtch*/
				,'1' /*orraterep*/
				,'0' /*orrateover*/
				,@subtotal_nodisc /*ordtotal*/
				,'0' /*ordmtotal*/
				,@ordlines /*ordlines*/
				,'1' /*numlabels*/
				,'0' /*ordpaytot*/
				,'0' /*ordpydstot*/
				,@salesper1
				,@salesper2
				,@salesper3
				,@salesper4
				,@salesper5
				,@salesplt1 /*salesplt1*/
				,'0' /*salesplt2*/
				,'0' /*salesplt3*/
				,'0' /*salesplt4*/
				,'0' /*salesplt5*/
				,'0' /*recalctac*/
				,'0' /*taxoverrd*/
				,@taxgroup
				,@tauth1
				,'' /*tauth2*/
				,'' /*tauth3*/
				,'' /*tauth4*/
				,'' /*tauth5*/
				,@tclass1 /*tclass1*/
				,'0' /*tclass2*/
				,'0' /*tclass3*/
				,'0' /*tclass4*/
				,'0' /*tclass5*/
				,@tbase1  /*tbase1*/
				,'0' /*tbase2*/
				,'0' /*tbase3*/
				,'0' /*tbase4*/
				,'0' /*tbase5*/
				,'0' /*teamount1*/
				,'0' /*teamount2*/
				,'0' /*teamount3*/
				,'0' /*teamount4*/
				,'0' /*teamount5*/
				,@pajak /*tiamount1*/
				,'0' /*tiamount2*/
				,'0' /*tiamount3*/
				,'0' /*tiamount4*/
				,'0' /*tiamount5*/
				,'' /*texempt1*/
				,'' /*texempt2*/
				,'' /*texempt3*/
				,'' /*texempt4*/
				,'' /*texempt5*/
				,'' /*optional1*/
				,'' /*optional2*/
				,'' /*optional3*/
				,'' /*optional4*/
				,'' /*optional5*/
				,'' /*optional6*/
				,0 /*optdate*/
				,0 /*optamt*/
				,'1' /*complete*/
				,'0' /*compdate*/
				,'*** NEW ***' /*invnumber*/
				,@shipdate
				,@invdate
				,@invfiscyr
				,@invfiscper /*invfiscper*/
				,'1' /*numpayment*/
				,@paymntasof
				,'0' /*invweight*/
				,@nextdtlnum /*nextdtlnum*/
				,'1' /*postinv*/
				,'0' /*idisonmisc*/
				,'0' /*innoprepay*/
				,'0' /*noshipline*/
				,'' /*nomiscline*/
				,@tanpaPajak /* @totalorder /*invnetnotx*/ */
				,@pajak /*invitaxtot*/
				,@subtotal_nodisc  /*invitmtot*/
				,@subtotalorder /*invdiscbas*/
				,@diskon /*invdiscper*/
				,@invdiscamt /*invdiscamt*/
				,'0' /*invmisc*/
				,@subtotal_nodisc  /*invsubtot*/
				,@totalorder  /*invnet*/
				,'0' /*invetaxtot*/
				,@totalorder /*invnetwtx*/
				,@totalorder  /*invamtude*/
				,@inhomecurr
				,'SP' /*inratetype*/
				,@insourcurr
				,@inratedate
				,'1' /*inrate*/
				,'0' /*inspread*/
				,'3' /*indatemtch*/
				,'1' /*inraterep*/
				,'0' /*inrateover*/
				,@ordersourc
				,'' /*csisusr*/
				,@CUSACCTSET /*CUSACCTSET*/
				
			
			exec [DM_uspApvOrderD_split_SGTDAT] @orderid,@orduniq,@audtuser,@totalorder,@subtotalorder, @sta 
			
			fetch next from cHeader into 
				@audtdate
				,@audttime
				,@audtorg
				,@ordnumber
				,@customer
				,@custgroup
				,@bilname
				,@biladdr1
				,@biladdr2
				,@biladdr3
				,@biladdr4
				,@bilcity
				,@bilstate
				,@bilzip
				,@bilcountry
				,@bilphone
				,@bilfax
				,@bilcontact
				,@shpname
				,@shpaddr1
				,@shpaddr2
				,@shpaddr3
				,@shpaddr4
				,@shpcity
				,@shpstate
				,@shpzip
				,@shpcountry
				,@shpphone
				,@shpfax
				,@shpcontact
				,@ponumber
				,@territory
				,@terms
				,@orddate
				,@expdate
				,@qtexpdate
				,@ordfiscyr
				,@ordfisper
				,@lastpost
				,@orhomecurr
				,@orsourcurr
				,@orratedate
				,@salesper1
				,@salesper2
				,@salesper3
				,@salesper4
				,@salesper5
				,@taxgroup
				,@tauth1
				,@shipdate
				,@invdate
				,@invfiscyr
				,@invfiscper
				,@paymntasof
				,@inhomecurr
				,@insourcurr
				,@inratedate
				,@ordersourc
				,@gudang
				,@priclist
				,@diskon
				,@invdiscamt
				
		end
		close cHeader
		deallocate cHeader

	
IF @@ERROR <> 0
	BEGIN
		ROLLBACK
		RAISERROR ('ERROR', 16, 1)
		RETURN
	END
COMMIT

--select * from [order]
--begin tran
--exec [uspApvOrderH] 'ORD-00001','ADM'
--rollback tran
--commit tran


GO

