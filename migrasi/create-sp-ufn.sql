/****** Object:  StoredProcedure [dbo].[DM_uspApvOrderH_Post_SGTDAT]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[DM_uspApvOrderH_Post_SGTDAT]') AND type in (N'P', N'PC'))
DROP PROCEDURE [dbo].[DM_uspApvOrderH_Post_SGTDAT]
GO
/****** Object:  StoredProcedure [dbo].[DM_uspApvOrderH_Post_split_SGTDAT]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[DM_uspApvOrderH_Post_split_SGTDAT]') AND type in (N'P', N'PC'))
DROP PROCEDURE [dbo].[DM_uspApvOrderH_Post_split_SGTDAT]
GO
/****** Object:  StoredProcedure [dbo].[DM_uspApvOrderD_SGTDAT]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[DM_uspApvOrderD_SGTDAT]') AND type in (N'P', N'PC'))
DROP PROCEDURE [dbo].[DM_uspApvOrderD_SGTDAT]
GO
/****** Object:  StoredProcedure [dbo].[DM_uspApvOrderD_split_SGTDAT]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[DM_uspApvOrderD_split_SGTDAT]') AND type in (N'P', N'PC'))
DROP PROCEDURE [dbo].[DM_uspApvOrderD_split_SGTDAT]
GO
/****** Object:  StoredProcedure [dbo].[DM_uspApvOrderH_Post_TRDAT]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[DM_uspApvOrderH_Post_TRDAT]') AND type in (N'P', N'PC'))
DROP PROCEDURE [dbo].[DM_uspApvOrderH_Post_TRDAT]
GO
/****** Object:  UserDefinedFunction [dbo].[ufn_daftar_order_item]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[ufn_daftar_order_item]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
DROP FUNCTION [dbo].[ufn_daftar_order_item]
GO
/****** Object:  UserDefinedFunction [dbo].[ufn_daftar_order_item_split]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[ufn_daftar_order_item_split]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
DROP FUNCTION [dbo].[ufn_daftar_order_item_split]
GO
/****** Object:  UserDefinedFunction [dbo].[ufn_diskon_approval]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[ufn_diskon_approval]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
DROP FUNCTION [dbo].[ufn_diskon_approval]
GO
/****** Object:  UserDefinedFunction [dbo].[ufn_nilai_diskon_peritem]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[ufn_nilai_diskon_peritem]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
DROP FUNCTION [dbo].[ufn_nilai_diskon_peritem]
GO
/****** Object:  UserDefinedFunction [dbo].[ufn_order_split]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[ufn_order_split]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
DROP FUNCTION [dbo].[ufn_order_split]
GO
/****** Object:  StoredProcedure [dbo].[usp_order_split]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[usp_order_split]') AND type in (N'P', N'PC'))
DROP PROCEDURE [dbo].[usp_order_split]
GO
/****** Object:  StoredProcedure [dbo].[usp_reporting]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[usp_reporting]') AND type in (N'P', N'PC'))
DROP PROCEDURE [dbo].[usp_reporting]
GO
/****** Object:  StoredProcedure [dbo].[uspApvOrderH_Post_TRDAT]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[uspApvOrderH_Post_TRDAT]') AND type in (N'P', N'PC'))
DROP PROCEDURE [dbo].[uspApvOrderH_Post_TRDAT]
GO
/****** Object:  StoredProcedure [dbo].[DM_uspApvOrderD_TRDAT]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[DM_uspApvOrderD_TRDAT]') AND type in (N'P', N'PC'))
DROP PROCEDURE [dbo].[DM_uspApvOrderD_TRDAT]
GO
/****** Object:  StoredProcedure [dbo].[usp_cek_kode_sub_kategori]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[usp_cek_kode_sub_kategori]') AND type in (N'P', N'PC'))
DROP PROCEDURE [dbo].[usp_cek_kode_sub_kategori]
GO
/****** Object:  StoredProcedure [dbo].[uspApvOrderD_TRDAT]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[uspApvOrderD_TRDAT]') AND type in (N'P', N'PC'))
DROP PROCEDURE [dbo].[uspApvOrderD_TRDAT]
GO
/****** Object:  UserDefinedFunction [dbo].[ufn_order_id]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[ufn_order_id]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
DROP FUNCTION [dbo].[ufn_order_id]
GO
/****** Object:  UserDefinedFunction [dbo].[sambung_order_id]    Script Date: 07/11/2017 16:41:32 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[sambung_order_id]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
DROP FUNCTION [dbo].[sambung_order_id]
GO
/****** Object:  UserDefinedFunction [dbo].[ufn_split_string]    Script Date: 07/11/2017 16:41:33 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[ufn_split_string]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
DROP FUNCTION [dbo].[ufn_split_string]
GO
/****** Object:  UserDefinedFunction [dbo].[ufn_split_string]    Script Date: 07/11/2017 16:41:33 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[ufn_split_string]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
BEGIN
execute dbo.sp_executesql @statement = N'CREATE FUNCTION [dbo].[ufn_split_string] 
( 
    @string NVARCHAR(MAX), 
    @delimiter CHAR(1) 
) 
RETURNS @output TABLE(splitdata NVARCHAR(MAX) 
) 
BEGIN 
    DECLARE @start INT, @end INT 
    SELECT @start = 1, @end = CHARINDEX(@delimiter, @string) 
    WHILE @start < LEN(@string) + 1 BEGIN 
        IF @end = 0  
            SET @end = LEN(@string) + 1
       
        INSERT INTO @output (splitdata)  
        VALUES(SUBSTRING(@string, @start, @end - @start)) 
        SET @start = @end + 1 
        SET @end = CHARINDEX(@delimiter, @string, @start)
        
    END 
    RETURN 
END' 
END
GO
/****** Object:  UserDefinedFunction [dbo].[sambung_order_id]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[sambung_order_id]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
BEGIN
execute dbo.sp_executesql @statement = N'CREATE function [dbo].[sambung_order_id](@s1 varchar(50), @s2 int, @pemisah varchar(1)) returns varchar(101) as begin
	
	return ltrim(rtrim(@s1)) + ltrim(rtrim(@pemisah)) + ltrim(rtrim(convert(varchar(10), @s2)))
	
end' 
END
GO
/****** Object:  UserDefinedFunction [dbo].[ufn_order_id]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[ufn_order_id]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
BEGIN
execute dbo.sp_executesql @statement = N'
CREATE function [dbo].[ufn_order_id]( @order_id_input varchar(50) ) returns varchar(50) as begin
--declare @order_id_input varchar(50)

declare @order_id varchar(50), @tahun varchar(2), @bulan varchar(2), @urutan varchar(5), @nol_loop tinyint

mulai_bikin_order_id:

set @bulan = case when month(current_timestamp)<10 then ''0''+cast(month(current_timestamp) as varchar(2)) else cast(month(current_timestamp) as varchar(2)) end 
set @tahun = substring(convert(varchar,year(current_timestamp), 101), 3,2)
set @urutan = isnull((select max(cast(right(order_id, 5) as int))+1 from [order] where order_id like ''IM/MS-%'' and year(tanggal)=year(getdate()) and isnumeric(right(order_id, 5)) = 1 ), 1)

while 1=1 begin	
	if( 5 - len(@urutan) <= 0 ) begin	
		break
	end
	set @urutan = ''0'' + @urutan 
end

set @order_id = ''IM/MS-''+@bulan+@tahun+''-''+@urutan

if( exists(select 1 from [order] where order_id = @order_id) ) begin goto mulai_bikin_order_id end

declare @string_sql varchar(1000), @cmd varchar(2000)
set @string_sql = ''update [order] set tanggal=getdate(), order_id = '''''' + @order_id +'''''' where order_id =''''''+ @order_id_input+''''''''
set @cmd = ''sqlcmd -S '' + @@servername +
              '' -d '' + db_name() + '' -Q "'' + @string_sql + ''"''
EXEC master..xp_cmdshell @cmd , ''no_output''

return @order_id

end
' 
END
GO
/****** Object:  StoredProcedure [dbo].[uspApvOrderD_TRDAT]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[uspApvOrderD_TRDAT]') AND type in (N'P', N'PC'))
BEGIN
EXEC dbo.sp_executesql @statement = N'-- =============================================
-- Author		:	Wong Tjliek, Isokuiki
-- Create date	:	20130507
-- Description	:	transfer order details from mobile_sales to TRDAT
-- =============================================

CREATE PROCEDURE [dbo].[uspApvOrderD_TRDAT]
	(
		@orderid nvarchar(50),
		@orduniq decimal(19,0),
		@audtuser char(8),
		@totalorder decimal,
		@subtotalorder decimal
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
	declare @diskon decimal
	declare @harga decimal 
	declare @pricelist varchar(10)
	declare @custtype decimal
	declare @diskon_per_invoice decimal
	declare @harga_total decimal
	declare @detailnum smallint
	declare @linenum_increasedby smallint
	
	set @linenum_increasedby = 32
	set @linenum = 0
	set @detailnum=0
	
	-- Fajar: Trap untuk overlimit. Apabila melebihi dari overlimit maka commited = 0 dan tidak dimasukkan kedalam iciloc --
	declare @boolLimit bit, @customer varchar(50)
	
	select @customer = customer from TRDAT..oeordh where orduniq = @orduniq
	
	select @boolLimit = 0--is_overlimit from TRDAT.dbo.ufnCekOverLimit(@totalorder, @customer)
	-- END TRAP --
	
	declare cDetails cursor for
	select 
		mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as audtdate
		,mesdb.dbo.fnSetAccpacTime(convert(varchar(12),getdate(),108)) as audttime
		,''TRDAT'' as audtorg
		,1 as linetype
		,b.itemno
		,b.fmtitemno as item
		,b.[desc] as itemdesc
		,d.cabang as location
		,mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),106)) as expdate
		,a.kuantitas as qtyordered
		,a.diskon_total_persen as diskon
		,a.harga
		,e.priclist
		,e.custtype
		,c.diskon diskon_per_invoice
		,a.kuantitas * a.harga harga_total
	from order_item a inner join TRDAT.dbo.icitem b on a.item_id=b.itemno 
	inner join [order] c on a.order_id=c.order_id
	inner join [user] d on c.user_id=d.user_id
	inner join TRDAT.dbo.arcus e on c.dealer_id=e.idcust
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
	
	declare		@category varchar(10), 
				@diskon_nominal decimal, 
				@diskon_nominal_total decimal,
				@diskon_nominal_total_semua_item decimal,
				@pripercent decimal, 
				@unitcost decimal, 
				@mostrec decimal,
				@extocost decimal,
				@pribasprc decimal
	
	set @diskon_nominal_total_semua_item=0
	
	while @@FETCH_STATUS = 0
	begin
		
		if @boolLimit = 1 
		begin			
			set @qtyordered = 0
		end
		
		set @linenum = @linenum + @linenum_increasedby
		set @detailnum=@detailnum+1
		
		select @category=''PBJ''+location_pbj from TRDAT.dbo.mis_loc_active where location=@location
		set @diskon_nominal=@harga*(@diskon/100)
		set @diskon_nominal_total=@diskon_nominal*@qtyordered
		set @diskon_nominal_total_semua_item=@diskon_nominal_total_semua_item + @diskon_nominal_total
		
		select @pripercent=case @custtype 
			when 1 then prcntlvl1 
			when 2 then prcntlvl2 
			when 3 then prcntlvl3
			when 4 then prcntlvl4
			when 5 then prcntlvl5
			else 0 end from TRDAT..icpric where itemno=@item and pricelist=@pricelist
		
		select @unitcost=TOTALCOST/QTYONHAND, @mostrec=RECENTCOST from 
			TRDAT..iciloc where ITEMNO=@item and LOCATION=@location
		
		set @extocost=@unitcost * @qtyordered
		
		set @pribasprc=@harga/(1-(@pripercent/100))
		
		/*
			Implementasi PPn
			
			- TClass1 = 2
			- TINCLUDED = 1
			- TBASE1 = net - 10%
			- TAMOUNT1 = 10% * net
			- TRATE1 = 10
		
		*/
		
		declare @tanpaPajak decimal
		select @tanpaPajak = (@harga_total - @diskon_nominal_total) / 1.1
		declare @pajak decimal
		select @pajak = (@harga_total - @diskon_nominal_total) - @tanpaPajak
		
		exec [TRDAT].[dbo].[sp_addWOdetil_Post]
			@orduniq			
			,@linenum			
			,@audtdate				
			,@audttime					
			,@audtuser				
			,@audtorg
			,@linetype			
			,@fmtitemno
			,'''' /*misccharge*/		
			,@itemdesc					
			,''01'' /*acctset*/			
			,0 /*''false'' usercostmd*/
			,@pricelist /*pricelist*/	
			,@category /*category*/	
			,@location				
			,'''' /*pickseq*/				
			,@expdate			
			,1 /*''true'' stockitem*/
			,@qtyordered		
			,0 /*qtyshipped*/	
			,@qtyordered /*qtybackord*/		
			,0 /*qtyshptodt*/			
			,@qtyordered /*origqty*/			
			,0 /*qtypo*/
			,''UNIT'' /*ordunit*/		
			,1 /*unitconv*/		
			,@harga /*unitprice*/		
			,0 /*''false'' priceover*/	
			,@unitcost /*unitcost*/			
			,@mostrec /*mostrec*/		
			,0 /*stdcost*/		
			,0 /*cost1*/		
			,0 /*cost2*/			
			,0 /* 6 unitprcdec*/			
			,''UNIT'' /*priceunit*/		
			,@harga /*priuntprc*/
			,1 /*priuntconv*/	
			,@pripercent /*pripercent*/	
			,0 /*priamount*/		
			,''UNIT'' /*baseunit*/			
			,@pribasprc /*pribasprc*/		
			,1 /*pribasconv*/
			,''UNIT'' /*costunit*/	
			,@unitcost /*cosuntcst*/	
			,1 /*cosuntconv*/		
			,0 /*extoprice*/			
			,@extocost /*extocost*/			
			,@harga_total /*extinvmisc*/
			,@diskon_nominal_total /*invdisc -- ini diisi diskon*/		
			,0 /*exticost*/		
			,0 /*''false'' extover*/	
			,0 /*unitweight*/			
			,0 /*extweight*/		
			,0 /*complete*/
			,1 /*''false'' addtoiloc*/ 
			,0 /*saleslost*/	
			,''PPN1'' /*tauth1*/		
			,'''' /*tauth2*/				
			,'''' /*tauth3*/			
			,'''' /*tauth4*/
			,'''' /*tauth5*/		
			,2 /*tclass1*/		
			,0 /*tclass2*/			
			,0 /*tclass3*/				
			,0 /*tclass4*/			
			,0 /*tclass5*/
			,1 /*''false'' tincluded1*/				
			,0/*''false'' tincluded2*/
			,0/*''false'' tincluded3*/	
			,0/*''false'' tincluded4*/
			,0/*''false'' tincluded5*/		
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
			,'''' /*miscacct*/			
			,@detailnum /*detailnum*/		
			,'''' /*haveserial*/
			,'''' /*comminst*/	
			,'''' /*glnonstkcr*/
			,@diskon
			,@diskon_per_invoice
			
		--print @linenum
		--print @orduniq
		
		-- untuk update iciloc
		

		update [TRDAT].[dbo].iciloc set qtycommit=qtycommit+@qtyordered, 
		QTYSALORDR=QTYSALORDR+@qtyordered where itemno=@item and location=@location

		 
		
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
	end
	close cDetails
	deallocate cDetails
	
	update TRDAT..OEORDH1 set ITEMDISTOT=@diskon_nominal_total_semua_item where ORDUNIQ=@orduniq
	
IF @@ERROR <> 0
	BEGIN
		ROLLBACK
		RAISERROR (''ERRORX'', 16, 1)
		RETURN
	END
COMMIT
--select * from [mobile_sales].[dbo].[order] 

--begin tran
--exec [mobile_sales].[dbo].[uspApvOrderD] ''ORD-00001'',12609027,''ADM''
--rollback tran





' 
END
GO
/****** Object:  StoredProcedure [dbo].[usp_cek_kode_sub_kategori]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[usp_cek_kode_sub_kategori]') AND type in (N'P', N'PC'))
BEGIN
EXEC dbo.sp_executesql @statement = N'
create proc [dbo].[usp_cek_kode_sub_kategori] as begin

declare @tabel table(kode_prefiks varchar(5))

declare @kode_kategori varchar(5)
declare cur_kategori cursor for
select distinct left(ltrim(rtrim(b.itemno)), 2) kode_kategori 
	/*, ltrim(rtrim(b.itemno)) itemno, 
		case when isnull(c.model, '''') = '''' then b.[desc] else c.model end [desc], b.itembrkid, b.fmtitemno, c.[model], b.comment1, b.comment2,
		e.unitprice*/
	from 
	sgtdat..ICITEM b inner join mesdb..tbl_icitem c 
		on b.ITEMNO = c.ITEMNO
	inner join sgtdat..ICPRIC d on b.ITEMNO = d.ITEMNO 
	left join sgtdat..ICPRICP e on d.ITEMNO = e.ITEMNO and d.PRICELIST = e.PRICELIST and d.CURRENCY = e.CURRENCY 
	where 
		c.MODEL is not null and b.ITEMBRKID in (''FG'') and 
		b.INACTIVE = 0 and b.[DESC] not like ''%SAMPLE%'' and
		DPRICETYPE = 1 and e.CURRENCY = ''IDR'' and d.pricelist=''STD''
		
open cur_kategori; fetch next from cur_kategori into @kode_kategori		
while(@@FETCH_STATUS = 0) begin
	
	declare @master_kode_kategori varchar(max), @ketemu_kode_kategorinya smallint
	declare cur_master_kode_kategori cursor for
		select kode_prefiks from sub_kategori where aktif_sub_kategori = 1
	open cur_master_kode_kategori; fetch next from cur_master_kode_kategori into @master_kode_kategori
	set @ketemu_kode_kategorinya = 0;
	while( @@FETCH_STATUS = 0 ) begin
		
		if( exists( select 1 from dbo.ufn_split_string( @master_kode_kategori, '','' ) where splitdata = @kode_kategori ) )
			set @ketemu_kode_kategorinya = 1;
		
		fetch next from cur_master_kode_kategori into @master_kode_kategori
	end
	close cur_master_kode_kategori;
	deallocate cur_master_kode_kategori;

	if	(	
			@ketemu_kode_kategorinya = 0 and 
			not exists(select 1 from @tabel where kode_prefiks = @kode_kategori)
		) 
		begin
			insert into @tabel values(@kode_kategori)
		end
		
	fetch next from cur_kategori into @kode_kategori		
end
close cur_kategori;
deallocate cur_kategori

declare @nomor int, @kode_kategori_belum_terdaftar varchar(5), @bodiemail varchar(max)
set @bodiemail = ''''
declare cur_tabel cursor for
	select ROW_NUMBER() OVER(ORDER BY kode_prefiks ASC) AS nomor, kode_prefiks from @tabel
open cur_tabel; fetch next from cur_tabel into @nomor, @kode_kategori_belum_terdaftar;
while( @@FETCH_STATUS = 0 )	 begin
	set @bodiemail = @bodiemail + ''<tr><td>''+convert(varchar(10), @nomor)+''</td><td>''+ @kode_kategori_belum_terdaftar +''</td></tr>'';
	fetch next from cur_tabel into @nomor, @kode_kategori_belum_terdaftar;
end
close cur_tabel; deallocate cur_tabel;

set @bodiemail = 
''
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; ">
</head>
<body>
Berikut adalah sub kategori produk belum terdaftar di tabel dm_ujicoba.sub_kategori :
<table cellpadding="1" cellspacing="0" border="1">
<tr><td>No. </td><td>Sub Kategori</td></tr>
'' + @bodiemail + ''
</table>
</body>
</html>
''

exec msdb..sp_send_dbmail 
			@profile_name = ''mis mail''
		,	@recipients = ''zaenal.fanani@modena.co.id''
		,   @copy_recipients = ''''
		,   @blind_copy_recipients = ''''
		,   @subject =   ''Diskon Manajemen :: Sub Kategori Belum Terdaftar''
		,   @body =   @bodiemail
		,   @body_format =   ''html'' 

end' 
END
GO
/****** Object:  StoredProcedure [dbo].[DM_uspApvOrderD_TRDAT]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[DM_uspApvOrderD_TRDAT]') AND type in (N'P', N'PC'))
BEGIN
EXEC dbo.sp_executesql @statement = N'-- =============================================
-- Author		:	Wong Tjliek, Isokuiki
-- Create date	:	20130507
-- Description	:	transfer order details from mobile_sales to TRDAT
-- =============================================

CREATE PROCEDURE [dbo].[DM_uspApvOrderD_TRDAT]
	(
		@orderid nvarchar(50),
		@orduniq decimal(19,0),
		@audtuser char(8),
		@totalorder decimal(19,3),
		@subtotalorder decimal(19,3)
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
	declare @diskon_per_invoice decimal(19,3), @keterangan_per_item varchar(60)
	declare @harga_total decimal(19,3)
	declare @detailnum smallint
	declare @linenum_increasedby smallint
	
	set @linenum_increasedby = 32
	set @linenum = 0
	set @detailnum=0
	
	-- Fajar: Trap untuk overlimit. Apabila melebihi dari overlimit maka commited = 0 dan tidak dimasukkan kedalam iciloc --
	declare @boolLimit bit, @customer varchar(50), @tbase1_header decimal(19,3), @tiamount1_header decimal(19,3)
	
	select @customer = customer, @tbase1_header = TBASE1, @tiamount1_header = TIAMOUNT1 from TRDAT..oeordh where orduniq = @orduniq
	
	select @boolLimit = 0--is_overlimit from TRDAT.dbo.ufnCekOverLimit(@totalorder, @customer)
	-- END TRAP --
	
	-- cari jumlah baris order_item
	declare @jumlah_row_order_item int, @row_order_item int, @total_order_tanpa_diskon	decimal(19,3), @header_tbase1 decimal(19,3), @header_tiamount1 decimal(19,3);
	select 
	@jumlah_row_order_item = COUNT(1), @row_order_item = 0, 
	@total_order_tanpa_diskon = sum( ( (a.harga* a.kuantitas) - ( case when a.diskon_total_persen <= 100 then (a.harga * a.kuantitas * a.diskon_total_persen / 100) else a.diskon_total end ) ) )  ,
	@header_tbase1 = @tbase1_header, --round(@totalorder / 1.1, 0) 
	@header_tiamount1 =  @tiamount1_header --round( round(@totalorder / 1.1, 0) / 10, 0 )
	from 
	
	(
	select order_id, user_id, item_seq, item_id, harga, kuantitas, diskon, tambahan_diskon, diskon_total, diskon_total_persen, convert(varchar(max), keterangan_order_item) keterangan, convert(varchar, paketid) paketid, urutan_parameter from order_item where order_id = @orderid union
	( select order_id, user_id, '''' item_seq, item_id, harga, kuantitas, harga diskon, 0 tambahan_diskon, harga diskon_total, 100 diskon_total_persen, convert(varchar(max), y.singkatan) keterangan, 
	convert(varchar, x.diskon_id) paketid, 0 urutan_parameter from order_diskon_freeitem x, diskon y where order_id = @orderid and x.diskon_id = y.diskon_id )  )
	
	a inner join TRDAT.dbo.icitem b on a.item_id=b.itemno 
	inner join [order] c on a.order_id=c.order_id
	inner join [user] d on c.user_id=d.user_id
	inner join TRDAT.dbo.arcus e on c.dealer_id=e.idcust
	where a.order_id=@orderid
	
	declare @tbase1_dialokasikan decimal(19, 3), @tiamount1_dialokasikan decimal(19, 3)
	set @tbase1_dialokasikan = 0
	set @tiamount1_dialokasikan = 0

	
	declare cDetails cursor for
	/*
	select 
		mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as audtdate
		,mesdb.dbo.fnSetAccpacTime(convert(varchar(12),getdate(),108)) as audttime
		,''TRDAT'' as audtorg
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
	from order_item a inner join TRDAT.dbo.icitem b on a.item_id=b.itemno 
	inner join [order] c on a.order_id=c.order_id
	inner join [user] d on c.user_id=d.user_id
	inner join TRDAT.dbo.arcus e on c.dealer_id=e.idcust
	where a.order_id=@orderid
	*/
	select 
		mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as audtdate
		,mesdb.dbo.fnSetAccpacTime(convert(varchar(12),getdate(),108)) as audttime
		,''TRDAT'' as audtorg
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
		,a.kuantitas * a.harga harga_total,
		left(isnull(a.keterangan, ''''), 60) keterangan_per_item
	from 
	
	(
	select order_id, user_id, item_seq, item_id, harga, kuantitas, diskon, tambahan_diskon, diskon_total, diskon_total_persen, convert(varchar(max), keterangan_order_item) keterangan, convert(varchar, paketid) paketid, urutan_parameter from order_item union
	( select order_id, user_id, '''' item_seq, item_id, harga, kuantitas, (harga * kuantitas) diskon, 0 tambahan_diskon, (harga * kuantitas) diskon_total, 100 diskon_total_persen, convert(varchar(max), y.singkatan) keterangan, 
	convert(varchar, x.diskon_id) paketid, 0 urutan_parameter from order_diskon_freeitem x, diskon y where x.diskon_id = y.diskon_id )  )
	
	a inner join TRDAT.dbo.icitem b on a.item_id=b.itemno 
	inner join [order] c on a.order_id=c.order_id
	inner join [user] d on c.user_id=d.user_id
	inner join TRDAT.dbo.arcus e on c.dealer_id=e.idcust
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
	
	declare		@category varchar(10), 
				@diskon_nominal decimal(19,3), 
				@diskon_nominal_total decimal(19,3),
				@diskon_nominal_total_semua_item decimal(19,3),
				@pripercent decimal(9,5), 
				@unitcost decimal(19,6), 
				@mostrec decimal(19,6),
				@extocost decimal(19,3),
				@pribasprc decimal(19,6)
	
	set @diskon_nominal_total_semua_item=0
		
	
	while @@FETCH_STATUS = 0
	begin
		
		if @boolLimit = 1 
		begin			
			set @qtyordered = 0
		end
		
		set @linenum = @linenum + @linenum_increasedby
		set @detailnum=@detailnum+1
		
		select @category=''PBJ''+location_pbj from TRDAT.dbo.mis_loc_active where location=@location
		set @diskon_nominal=@harga*(@diskon/100)
		set @diskon_nominal_total = round(@diskon_nominal*@qtyordered,0)
		set @diskon_nominal_total_semua_item=@diskon_nominal_total_semua_item + @diskon_nominal_total
		
		select @pripercent=case @custtype 
			when 1 then prcntlvl1 
			when 2 then prcntlvl2 
			when 3 then prcntlvl3
			when 4 then prcntlvl4
			when 5 then prcntlvl5
			else 0 end from TRDAT..icpric where itemno=@item and pricelist=@pricelist
		
		--select @unitcost=TOTALCOST/QTYONHAND, @mostrec=RECENTCOST from 
		--	TRDAT..iciloc where ITEMNO=@item and LOCATION=@location
			
		select @unitcost=case when QTYONHAND>0 then TOTALCOST/QTYONHAND else 0 end, @mostrec=RECENTCOST from 
		[TRDAT]..iciloc where ITEMNO=@item and LOCATION=@location			
		
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
		
		exec [TRDAT].[dbo].[sp_addWOdetil_Post]
			@orduniq			
			,@linenum			
			,@audtdate				
			,@audttime					
			,@audtuser				
			,@audtorg
			,@linetype			
			,@fmtitemno
			,'''' /*misccharge*/		
			,@itemdesc					
			,''01'' /*acctset*/			
			,0 /*''false'' usercostmd*/
			,@pricelist /*pricelist*/	
			,@category /*category*/	
			,@location				
			,'''' /*pickseq*/				
			,@expdate			
			,1 /*''true'' stockitem*/
			,@qtyordered		
			,0 /*qtyshipped*/	
			,@qtyordered /*qtybackord*/		
			,0 /*qtyshptodt*/			
			,@qtyordered /*origqty*/			
			,0 /*qtypo*/
			,''UNIT'' /*ordunit*/		
			,1 /*unitconv*/		
			,@harga /*unitprice*/		
			,0 /*''false'' priceover*/	
			,@unitcost /*unitcost*/			
			,@mostrec /*mostrec*/		
			,0 /*stdcost*/		
			,0 /*cost1*/		
			,0 /*cost2*/			
			,0 /* 6 unitprcdec*/			
			,''UNIT'' /*priceunit*/		
			,@harga /*priuntprc*/
			,1 /*priuntconv*/	
			,@pripercent /*pripercent*/	
			,0 /*priamount*/		
			,''UNIT'' /*baseunit*/			
			,@pribasprc /*pribasprc*/		
			,1 /*pribasconv*/
			,''UNIT'' /*costunit*/	
			,@unitcost /*cosuntcst*/	
			,1 /*cosuntconv*/		
			,0 /*extoprice*/			
			,@extocost /*extocost*/			
			,@harga_total /*extinvmisc*/
			,@diskon_nominal_total /*invdisc -- ini diisi diskon*/		
			,0 /*exticost*/		
			,0 /*''false'' extover*/	
			,0 /*unitweight*/			
			,0 /*extweight*/		
			,0 /*complete*/
			,1 /*''false'' addtoiloc*/ 
			,0 /*saleslost*/	
			,''PPN1'' /*tauth1*/		
			,'''' /*tauth2*/				
			,'''' /*tauth3*/			
			,'''' /*tauth4*/
			,'''' /*tauth5*/		
			,2 /*tclass1*/		
			,0 /*tclass2*/			
			,0 /*tclass3*/				
			,0 /*tclass4*/			
			,0 /*tclass5*/
			,1 /*''false'' tincluded1*/				
			,0/*''false'' tincluded2*/
			,0/*''false'' tincluded3*/	
			,0/*''false'' tincluded4*/
			,0/*''false'' tincluded5*/		
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
			,'''' /*miscacct*/			
			,@detailnum /*detailnum*/		
			,'''' /*haveserial*/
			,'''' /*comminst*/	
			,'''' /*glnonstkcr*/
			,@diskon
			,@diskon_per_invoice
			,@keterangan_per_item		
			
		--print @linenum
		--print @orduniq
		
		-- untuk update iciloc
		

		update [TRDAT].[dbo].iciloc set qtycommit=qtycommit+@qtyordered, 
		QTYSALORDR=QTYSALORDR+@qtyordered where itemno=@item and location=@location
		
		
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
	end
	close cDetails
	deallocate cDetails
	
	update TRDAT..OEORDH1 set ITEMDISTOT=@diskon_nominal_total_semua_item where ORDUNIQ=@orduniq
	
	
	
IF @@ERROR <> 0
	BEGIN
		ROLLBACK
		RAISERROR (''ERRORX'', 16, 1)
		RETURN
	END
COMMIT
--select * from [mobile_sales].[dbo].[order] 

--begin tran
--exec [mobile_sales].[dbo].[uspApvOrderD] ''ORD-00001'',12609027,''ADM''
--rollback tran





' 
END
GO
/****** Object:  StoredProcedure [dbo].[uspApvOrderH_Post_TRDAT]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[uspApvOrderH_Post_TRDAT]') AND type in (N'P', N'PC'))
BEGIN
EXEC dbo.sp_executesql @statement = N'-- =============================================
-- Author		:	Wong Tjliek, Isokuiki
-- Create date	:	20130507
-- Description	:	transfer order header from mobile_sales to TRDAT
-- =============================================
create PROCEDURE [dbo].[uspApvOrderH_Post_TRDAT]
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
	
	select @tempOrder = sum((harga - (harga*(diskon/100))) * kuantitas) from order_item where order_id = @orderid
	
	select @rCount = COUNT(*) from mobilesales..orderExec where order_id = @orderid
	
	if @rCount = 0
	begin
		insert into orderExec 
			values (@audtuser, @orderid, @sta, @totalorder, @subtotalorder, @subtotal_nodisc)
	end
	
BEGIN TRANSACTION

/*
declare @orderid varchar(100), @audtuser varchar(6), @sta smallint; 
set @orderid=''M-usr-00002-914-00108''; set @audtuser=''MSAS''; set @sta=1;
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
	set @CUSACCTSET=''AR1''
	declare @salesplt1 smallint
	set @salesplt1=100
	declare @diskon decimal(9,5)
	
	declare @ordlines int, @nextdtlnum int
	select @ordlines=COUNT(1) from order_item where order_id=@orderid
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
		set @orduniq = (select max(orduniq)+ 1 as xx from TRDAT.dbo.oeordh)
		
		declare cHeader cursor for
			select 
				mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as audtdate
				,mesdb.dbo.fnSetAccpacTime(convert(varchar(12),getdate(),108)) as audttime
				,''TRDAT'' as audtorg
				,a.order_id as ordnumber
				,a.dealer_id as customer
				,b.idgrp as custgroup
				,b.namecust as bilname
				,b.textstre1 as biladdr1
				,b.textstre2 as biladdr2
				,b.textstre3 as biladdr3
				,b.textstre4 as biladdr4
				,b.namecity as bilcity
				,b.codestte as bilstate
				,b.codepstl as bilzip
				,b.codectry as bilcountry
				,b.textphon1 as bilphone
				,b.textphon2 as bilfax
				,b.namectac as bilcontact
				,b.namecust as shpname
				,b.textstre1 as shpaddr1
				,b.textstre2 as shpaddr2
				,b.textstre3 as shpaddr3
				,b.textstre4 as shpaddr4
				,b.namecity as shpcity
				,b.codestte as shpstate
				,b.codepstl as shpzip
				,b.codectry as shpcountry
				,b.textphon1 as shpphone
				,b.textphon2 as shpfax
				,b.namectac as shpcontact
				,'''' as ponumber
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
				,b.codeslsp1 as salesper1
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
			from [order] a inner join TRDAT.dbo.arcus b on a.dealer_id=b.idcust
			where a.order_id=@orderid
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
			--exec procedure insert order to TRDAT.dbo.oeordh
			select @invdiscamt = ROUND(@invdiscamt,0)
			exec TRDAT.dbo.sp_addWO_Post
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
				,'''' /*shipto*/
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
				,''1'' /*custdisc*/
				,@priclist /*pricelist*/
				,@ponumber
				,@territory
				,@terms
				,@totalorder /*termttldue*/
				,''0'' /*discavail*/
				,''0'' /*termoverrd*/
				,@orderid /*MOBILE_SALES*//*reference*/
				,''1'' /*[type]*/
				,@orddate
				,@expdate
				,@qtexpdate
				,@ordfiscyr
				,@ordfisper /*ordfiscper*/
				,'''' /*shipvia*/
				,'''' /*viadesc*/
				,'''' /*lastinvnum*/
				,''0'' /*numinvoice*/
				,'''' /*fob*/
				,'''' /*template*/
				,@gudang /*location*/
				,''0'' /*onhold*/
				,'''' /*[desc]*/
				,'''' /*comment*/
				,''1'' /*printstat*/
				,@lastpost
				,''0'' /*ornoprepay*/
				,''0'' /*overcredit*/
				,''0'' /*approvelmt*/
				,'''' /*approveby*/
				,''0'' /*shiplabel*/
				,''0'' /*lblprinted*/
				,@orhomecurr
				,''SP'' /*orratetype*/
				,@orsourcurr
				,@orratedate
				,''1'' /*orrate*/
				,''0'' /*orspread*/
				,''3'' /*ordatemtch*/
				,''1'' /*orraterep*/
				,''0'' /*orrateover*/
				,@subtotal_nodisc /*ordtotal*/
				,''0'' /*ordmtotal*/
				,@ordlines /*ordlines*/
				,''1'' /*numlabels*/
				,''0'' /*ordpaytot*/
				,''0'' /*ordpydstot*/
				,@salesper1
				,@salesper2
				,@salesper3
				,@salesper4
				,@salesper5
				,@salesplt1 /*salesplt1*/
				,''0'' /*salesplt2*/
				,''0'' /*salesplt3*/
				,''0'' /*salesplt4*/
				,''0'' /*salesplt5*/
				,''0'' /*recalctac*/
				,''0'' /*taxoverrd*/
				,@taxgroup
				,@tauth1
				,'''' /*tauth2*/
				,'''' /*tauth3*/
				,'''' /*tauth4*/
				,'''' /*tauth5*/
				,@tclass1 /*tclass1*/
				,''0'' /*tclass2*/
				,''0'' /*tclass3*/
				,''0'' /*tclass4*/
				,''0'' /*tclass5*/
				,@tbase1  /*tbase1*/
				,''0'' /*tbase2*/
				,''0'' /*tbase3*/
				,''0'' /*tbase4*/
				,''0'' /*tbase5*/
				,''0'' /*teamount1*/
				,''0'' /*teamount2*/
				,''0'' /*teamount3*/
				,''0'' /*teamount4*/
				,''0'' /*teamount5*/
				,@pajak /*tiamount1*/
				,''0'' /*tiamount2*/
				,''0'' /*tiamount3*/
				,''0'' /*tiamount4*/
				,''0'' /*tiamount5*/
				,'''' /*texempt1*/
				,'''' /*texempt2*/
				,'''' /*texempt3*/
				,'''' /*texempt4*/
				,'''' /*texempt5*/
				,'''' /*optional1*/
				,'''' /*optional2*/
				,'''' /*optional3*/
				,'''' /*optional4*/
				,'''' /*optional5*/
				,'''' /*optional6*/
				,0 /*optdate*/
				,0 /*optamt*/
				,''1'' /*complete*/
				,''0'' /*compdate*/
				,''*** NEW ***'' /*invnumber*/
				,@shipdate
				,@invdate
				,@invfiscyr
				,@invfiscper /*invfiscper*/
				,''1'' /*numpayment*/
				,@paymntasof
				,''0'' /*invweight*/
				,@nextdtlnum /*nextdtlnum*/
				,''1'' /*postinv*/
				,''0'' /*idisonmisc*/
				,''0'' /*innoprepay*/
				,''0'' /*noshipline*/
				,'''' /*nomiscline*/
				,@tanpaPajak /* @totalorder /*invnetnotx*/ */
				,@pajak /*invitaxtot*/
				,@subtotal_nodisc  /*invitmtot*/
				,@subtotalorder /*invdiscbas*/
				,@diskon /*invdiscper*/
				,@invdiscamt /*invdiscamt*/
				,''0'' /*invmisc*/
				,@subtotal_nodisc  /*invsubtot*/
				,@totalorder  /*invnet*/
				,''0'' /*invetaxtot*/
				,@totalorder /*invnetwtx*/
				,@totalorder  /*invamtude*/
				,@inhomecurr
				,''SP'' /*inratetype*/
				,@insourcurr
				,@inratedate
				,''1'' /*inrate*/
				,''0'' /*inspread*/
				,''3'' /*indatemtch*/
				,''1'' /*inraterep*/
				,''0'' /*inrateover*/
				,@ordersourc
				,'''' /*csisusr*/
				,@CUSACCTSET /*CUSACCTSET*/
				
			-- insert di OETERMO
			/*insert into TRDAT..OETERMO(
				ORDUNIQ,
				PAYMENT,
				AUDTDATE,
				AUDTTIME,
				AUDTUSER,
				AUDTORG,
				DISCBASE,
				DISCDATE,
				DISCPER,
				DISCAMT,
				DUEBASE,
				DUEDATE,
				DUEPER,
				DUEAMT
			)values(
				@orduniq,
				32,
				@audtdate,
				@audttime,
				@audtuser,
				@audtorg,
				@totalorder,
				@audtdate,
				@diskon,
				0,
				@totalorder,
				mesdb.dbo.fnSetAccpacDate(convert(varchar(12),dateadd(day, 30, getdate()),101)),
				100,
				@totalorder				
			)*/
			
--print ''[uspApvOrderD] ''+convert(varchar, @orderid)+'',''+convert(varchar(50), @orduniq)+'',''+convert(varchar(10), @audtuser)+'',''+convert(varchar(100), @totalorder)+'',''+convert(varchar(100), @subtotalorder)
			exec [uspApvOrderD_TRDAT] @orderid,@orduniq,@audtuser,@totalorder,@subtotalorder 
			--exec [uspApvOrderD_TRDAT] @orderid,@orduniq,@audtuser,@tempOrder,@subtotalorder 
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
	--end
	--update approval
	/*	update [order] set
		[status] = @sta
		,approval=1
		,tanggal_approval=GETDATE()
		where order_id=@orderid*/
	
IF @@ERROR <> 0
	BEGIN
		ROLLBACK
		RAISERROR (''ERROR'', 16, 1)
		RETURN
	END
COMMIT

--select * from [order]
--begin tran
--exec [uspApvOrderH] ''ORD-00001'',''ADM''
--rollback tran
--commit tran

' 
END
GO
/****** Object:  StoredProcedure [dbo].[usp_reporting]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[usp_reporting]') AND type in (N'P', N'PC'))
BEGIN
EXEC dbo.sp_executesql @statement = N'
CREATE proc [dbo].[usp_reporting](@tanggal_awal date, @tanggal_akhir date) as begin

--	declare @tanggal_awal date, @tanggal_akhir date
	
	declare @tabel varchar(max), @sql_kolom varchar(max)
	declare @diskon_id int, @diskon varchar(500), @diskon_singkatan varchar(100)
	
	set @tabel = ''create table tabel_report ( order_id varchar(50), item_seq int,''
	set @sql_kolom = ''''
	
	declare cursor_tabel cursor for
		select diskon_id, diskon, singkatan from diskon where aktif_diskon = 1
	open cursor_tabel; fetch next from cursor_tabel into @diskon_id, @diskon, @diskon_singkatan 
	while(@@FETCH_STATUS=0) begin
		set @tabel = @tabel + ''['' + @diskon_singkatan + ''] float default 0,''
		set @sql_kolom = @sql_kolom + '',isnull(''+ @diskon_singkatan +'',0) '' + + @diskon_singkatan
		fetch next from cursor_tabel into @diskon_id, @diskon, @diskon_singkatan 
	end
	close cursor_tabel;
	deallocate cursor_tabel;	
	set @tabel = left(@tabel, len(@tabel)-1) + '')''

	exec(@tabel)
	
	declare @sql_insert_tabel_report varchar(max)
	declare @order_id varchar(50), @item_seq int, @diskon_singkatan_cursor varchar(100), @nilai_tambahan_diskon float
	declare cursor_tabel cursor for
		select a.order_id, d.item_seq, g.singkatan, 
			case when f.nilai_diskon <= 100 then f.nilai_diskon * (d.harga - (d.diskon / d.kuantitas)) * e.kuantitas_diskon_item / 100 else f.nilai_diskon * e.kuantitas_diskon_item end nilai_diskon
		from [order] a, SGTDAT..OEINVH b, SGTDAT..ARCUS c, order_item d, order_diskon_item e, order_diskon f, diskon g
		where a.kirim=1 and a.order_id = b.ordnumber and a.dealer_id = c.IDCUST and a.order_id = d.order_id and 
		d.order_id = e.order_id and d.user_id = e.user_id and d.item_seq = e.item_seq and e.order_id = f.order_id and e.user_id = f.user_id and e.diskon_id = f.diskon_id
		and f.diskon_id = g.diskon_id
	open cursor_tabel; fetch next from cursor_tabel into @order_id, @item_seq, @diskon_singkatan_cursor, @nilai_tambahan_diskon
	while(@@FETCH_STATUS=0) begin
		set @sql_insert_tabel_report = ''
					if(not exists (select 1 from tabel_report where order_id = ''''''+ @order_id +'''''' and item_seq = ''''''+ convert(varchar(10),@item_seq) +'''''')) begin
							insert into tabel_report(order_id, item_seq, ''+ @diskon_singkatan_cursor +'') 
							values(''''''+@order_id+'''''', ''''''+convert(varchar(10),@item_seq)+'''''',''''''+convert(varchar(50), @nilai_tambahan_diskon)+''''''); end
					else begin
						update tabel_report set ''+ @diskon_singkatan_cursor +'' = ''+ @diskon_singkatan_cursor +'' + ''+convert(varchar(50), @nilai_tambahan_diskon)+''
							where order_id = ''''''+ @order_id +'''''' and item_seq = ''''''+ convert(varchar(10),@item_seq) +'''''';
					end''

		exec(@sql_insert_tabel_report)
		fetch next from cursor_tabel into @order_id, @item_seq, @diskon_singkatan_cursor, @nilai_tambahan_diskon
	end
	close cursor_tabel;
	deallocate cursor_tabel;	

	declare @sql_select varchar(max), @sql_parameter varchar(500)
	set @sql_parameter = ''''
	if( @tanggal_awal <> '''' ) begin set @sql_parameter = @sql_parameter + '' and convert(date, CONVERT(varchar, a.tanggal, 101)) >= convert(date, ''''''+ CONVERT(varchar, @tanggal_awal, 101)+'''''')'' end
	if( @tanggal_akhir <> '''' ) begin set @sql_parameter = @sql_parameter + '' and convert(date, CONVERT(varchar, a.tanggal, 101)) <= convert(date, ''''''+ CONVERT(varchar, @tanggal_akhir, 101)+'''''')'' end
	set @sql_select = ''
	select c.NAMECUST nama_dealer , a.order_id nomor_order, a.tanggal tanggal_order, 
	b.INVNUMBER nomor_invoice, convert(date, substring(convert(varchar(8), b.INVDATE), 5, 2)+''''/''''+ right(b.INVDATE, 2) +''''/''''+ left(b.INVDATE, 4)) tanggal_invoice, b.SALESPER1 kode_sales,
	/*d.item_seq, */d.item_id kode_barang, 
	d.harga harga_per_unit, d.kuantitas, isnull(d.paketid, '''''''') campaign, d.diskon diskon_campaign, d.tambahan_diskon, d.diskon_total
	''+ @sql_kolom +'', (d.harga * d.kuantitas) - d.diskon_total subtotal
	from [order] a inner join SGTDAT..OEINVH b on a.order_id = b.ordnumber 
	inner join SGTDAT..ARCUS c on a.dealer_id = c.IDCUST 
	inner join order_item d on a.order_id = d.order_id 
	left outer join tabel_report e on d.order_id = e.order_id and d.item_seq = e.item_seq
	where a.kirim=1 ''+ @sql_parameter +''
	order by a.order_id, d.item_seq
	''
	exec(@sql_select)
	drop table tabel_report
end
' 
END
GO
/****** Object:  StoredProcedure [dbo].[usp_order_split]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[usp_order_split]') AND type in (N'P', N'PC'))
BEGIN
EXEC dbo.sp_executesql @statement = N'CREATE proc [dbo].[usp_order_split] ( @order_id varchar(50), @gudang varchar(10)) as begin
/*
declare @order_id varchar(50), @gudang varchar(10)
set @order_id = ''DRAFT-bagus-DA03A17010B0''
set @gudang = ''GDGPST''
*/
declare @jumlah_order_split smallint
set @jumlah_order_split = ISNULL( (select count(1) from order_split where order_id = @order_id) ,1)

if not exists( select 1 from order_split where order_id = @order_id and gudang = @gudang ) begin
	insert into order_split 
	select order_id, @jumlah_order_split, USER_ID, tanggal, dealer_id, @gudang, diskon_nominal, diskon, kirim, keterangan_order, pengajuan_diskon from [order]
	where order_id = @order_id
	
	insert into order_item_split
	select order_id, @jumlah_order_split, USER_ID, item_seq, item_id, harga, kuantitas, diskon_default, diskon, tambahan_diskon, diskon_total, diskon_total_persen,
	keterangan_order_item, paketid, urutan_parameter from order_item where order_id = @order_id and gudang = @gudang		
	
end
	
end' 
END
GO
/****** Object:  UserDefinedFunction [dbo].[ufn_order_split]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[ufn_order_split]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
BEGIN
execute dbo.sp_executesql @statement = N'
--drop function [ufn_order_split]
CREATE function [dbo].[ufn_order_split]( @order_id varchar(50) ) 
	returns @table table(order_id varchar(50), order_id_split varchar(10), gudang varchar(10)) as begin
/*declare @order_id varchar(50)
set @order_id = ''DRAFT-bagus-DA03A17010B0''*/

declare @jumlah_sumber_gudang smallint
select @jumlah_sumber_gudang = count(jumlah_gudang) from
	(
	select distinct gudang jumlah_gudang from [order_item] where order_id = @order_id union 
	select distinct gudang from order_diskon_freeitem where order_id = @order_id
	) a	

if @jumlah_sumber_gudang > 1 begin

		declare @string_sql varchar(1000), @cmd varchar(2000)
		
		set @string_sql = ''delete from order_split where order_id = '''''' + @order_id +''''''''
		set @cmd = ''sqlcmd -S '' + @@servername +
					  '' -d '' + db_name() + '' -Q "'' + @string_sql + ''"''
		EXEC master..xp_cmdshell @cmd , ''no_output''

	declare @gudang varchar(10)
	declare cur cursor for 
		select gudang from
			(
			select distinct gudang from [order_item] where order_id = @order_id union 
			select distinct gudang from order_diskon_freeitem where order_id = @order_id
			) a
	open cur; fetch next from cur into @gudang
	while( @@fetch_status = 0 ) begin
					
		set @string_sql = ''dbo.usp_order_split '''''' + @order_id +'''''', ''''''+ @gudang+''''''''
		set @cmd = ''sqlcmd -S '' + @@servername +
					  '' -d '' + db_name() + '' -Q "'' + @string_sql + ''"''
		EXEC master..xp_cmdshell @cmd , ''no_output''
		
		insert into @table select order_id, order_id_split, gudang from order_split where order_id = @order_id and gudang = @gudang
			
		fetch next from cur into @gudang
		
	end 
	close cur; deallocate cur;
			
end

return 
end
' 
END
GO
/****** Object:  UserDefinedFunction [dbo].[ufn_nilai_diskon_peritem]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[ufn_nilai_diskon_peritem]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
BEGIN
execute dbo.sp_executesql @statement = N'CREATE function [dbo].[ufn_nilai_diskon_peritem](@order_id varchar(50), @diskon_id int, @mode smallint, @item_seq smallint) returns float as begin
	--declare @order_id varchar(50), @diskon_id int, @mode smallint
	--set @order_id = ''IM/MS-0317-00003''
	--set @diskon_id = 7
	--set @mode = 0 /* 0: semua tambahan diskon, 1: diskon yg disetujui saja */
	
	declare @nilai_diskon float
	
	if( @mode = 1 ) begin
		select @nilai_diskon = (
		case 
			when a.nilai_diskon <= 100 then a.nilai_diskon * b.kuantitas_diskon_item * ( ( ( c.harga * c.kuantitas) - c.diskon ) / c.kuantitas ) / 100
			else a.nilai_diskon
		end)
		from order_diskon a, order_diskon_item b, [order_item] c 
		where 
		a.order_id = c.order_id and a.user_id = c.user_id and 
		b.order_id = a.order_id and b.user_id = a.user_id and b.item_seq = c.item_seq and
		a.order_id = b.order_id and a.user_id = b.user_id and a.diskon_id = b.diskon_id and
		b.order_id = @order_id and b.diskon_id = @diskon_id and b.item_seq = @item_seq and a.disetujui = 1
	end	
	else begin  
	
		select @nilai_diskon = (
		case 
			when a.nilai_diskon <= 100 then a.nilai_diskon * b.kuantitas_diskon_item * ( ( ( c.harga * c.kuantitas) - c.diskon ) / c.kuantitas ) / 100
			else a.nilai_diskon
		end)
		from order_diskon a, order_diskon_item b, [order_item] c 
		where 
		a.order_id = c.order_id and a.user_id = c.user_id and 
		b.order_id = a.order_id and b.user_id = a.user_id and b.item_seq = c.item_seq and
		a.order_id = b.order_id and a.user_id = b.user_id and a.diskon_id = b.diskon_id and
		b.order_id = @order_id and b.diskon_id = @diskon_id and b.item_seq = @item_seq
	
	end
		
		--select @nilai_diskon
		return @nilai_diskon
		
		end' 
END
GO
/****** Object:  UserDefinedFunction [dbo].[ufn_diskon_approval]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[ufn_diskon_approval]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
BEGIN
execute dbo.sp_executesql @statement = N'
create function [dbo].[ufn_diskon_approval](@order_id varchar(50), @mode smallint) 
returns @table table(order_id varchar(50), user_id varchar(50), diskon_id int, urutan int)
as begin
/*#################*/
/*
mode 
0 : urutan pertama yg belum mendapatkan persetujuan 
1 : urutan terakhir yg sudah mendapatkan persetujuan (tidak disetujui atau disetujui)
*/

/*declare @order_id varchar(50), @diskon_id int
set @order_id = ''DRAFT-bambang-DA12112020B0''
set @diskon_id = 6

declare @table table(order_id varchar(50), user_id varchar(50), diskon_id int, urutan int)*/

declare @diskon_id int

declare cur_diskon cursor for
	select diskon_id from order_diskon where order_id = @order_id
	open cur_diskon
	fetch next from cur_diskon into @diskon_id

	while( @@fetch_status = 0 ) begin

		declare @user_id varchar(50), @urutan int, @disetujui int
		if @mode = 0 begin
			declare cur cursor for
				select user_id, urutan, isnull(disetujui, -1) from order_diskon_approval where order_id = @order_id and diskon_id = @diskon_id
				order by urutan asc
		end
		else begin
			declare cur cursor for
				select user_id, urutan, isnull(disetujui, -1) from order_diskon_approval where order_id = @order_id and diskon_id = @diskon_id
				order by urutan desc
		end
		open cur	
		fetch next from cur into @user_id, @urutan, @disetujui
		while( @@FETCH_STATUS = 0 )	begin
			
			if @mode = 0 begin
				if @disetujui = -1 begin
					insert into @table values(@order_id, @user_id, @diskon_id, @urutan)
					break
				end
			end
			
			else begin
				if @disetujui <> -1 begin
					insert into @table values(@order_id, @user_id, @diskon_id, @urutan)
					break
				end
			end
			
			fetch next from cur into @user_id, @urutan, @disetujui
		end
		close cur
		deallocate cur
		
	fetch next from cur_diskon into @diskon_id
	
end
close cur_diskon
deallocate cur_diskon

--select * from @table
return
end 
' 
END
GO
/****** Object:  UserDefinedFunction [dbo].[ufn_daftar_order_item_split]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[ufn_daftar_order_item_split]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
BEGIN
execute dbo.sp_executesql @statement = N'
CREATE function [dbo].[ufn_daftar_order_item_split]( @order_id_input varchar(50) ) 
returns @ret_tabel table(order_id varchar(50), user_id varchar(50), 
item_seq int, item_id varchar(50), harga float, kuantitas int, 
diskon float, tambahan_diskon float, diskon_total float, diskon_total_persen float, keterangan varchar(500), paketid varchar(50), urutan_parameter smallint, 
kuantitas_diskon_item int, nama_item varchar(500), keterangan_diskon_tambahan varchar(500) default '''', tambahan_diskon_belum_disetujui float, 
sub_total_campaign float, sub_total float, kategori_gift varchar(10)) as begin

/*
declare @ret_tabel table(order_id varchar(50), user_id varchar(50), 
item_seq int, item_id varchar(50), harga float, kuantitas int, 
diskon float, tambahan_diskon float, diskon_total float, diskon_total_persen float, keterangan varchar(500), paketid varchar(50), urutan_parameter smallint, 
kuantitas_diskon_item int, nama_item varchar(500), keterangan_diskon_tambahan varchar(500), tambahan_diskon_belum_disetujui float, 
sub_total_campaign float, sub_total float)
declare @order_id_input varchar(50)
set @order_id_input = ''IM/MS-0417-00689''
*/


declare 
@order_id varchar(50), @user_id varchar(50), 
@item_seq int, @item_id varchar(50), @harga float, @kuantitas int, 
@diskon float, @tambahan_diskon float, @diskon_total float, @diskon_total_persen float, @keterangan varchar(500), @paketid varchar(50), @urutan_parameter smallint, @diskon_id int, 
@kuantitas_diskon_item int, @nama_item varchar(500), @keterangan_diskon_tambahan varchar(500), @tambahan_diskon_belum_disetujui float, 
@sub_total_campaign float, @sub_total float, @diskon_tambahan_default float, @kategori_gift varchar(10)

declare @gudang varchar(10), @order_id_nosplit varchar(50), @order_id_split varchar(10)
select @gudang = gudang, @order_id_nosplit = order_id, @order_id_split = order_id_split 
	from order_split where dbo.sambung_order_id(order_id, order_id_split, ''-'') = @order_id_input

declare cursor_item cursor for

select 
a.*,
case when a.item_seq = 0 then a.kuantitas else case when isnull(c.item_seq, 0) > 0 then c.kuantitas_diskon_item end end kuantitas_diskon_item
, case when isnull(e.model, '''') = '''' then b.[desc] else e.model end nama_item, 
case when a.item_seq = 0 then h.singkatan else case when isnull(c.item_seq, 0) > 0 then d.singkatan end end keterangan_diskon_tambahan, 
				case when a.tambahan_diskon > 0 then 0 /*a.tambahan_diskon*/ else dbo.ufn_nilai_diskon_peritem(@order_id_nosplit, c.diskon_id, 0, a.item_seq) * c.kuantitas_diskon_item end tambahan_diskon_belum_disetujui,
				(a.harga * (case when c.kuantitas_diskon_item < a.kuantitas then a.kuantitas - c.kuantitas_diskon_item else a.kuantitas end) ) - a.diskon sub_total_campaign,
				(a.harga * ( case when c.kuantitas_diskon_item < a.kuantitas then a.kuantitas - c.kuantitas_diskon_item else a.kuantitas end )) sub_total,
				case when a.item_seq = 0 then 100 else case when isnull(c.item_seq, 0) > 0 then isnull(f.nilai_diskon, 0) else 0 end end nilai_diskon, h.kategori_gift
					from 						
						(
						select @order_id_input order_id, user_id, item_seq, item_id, harga, kuantitas, isnull(diskon, 0) diskon, isnull(tambahan_diskon, 0) tambahan_diskon, isnull(diskon_total, 0) diskon_total, isnull(diskon_total_persen, 0) diskon_total_persen, convert(varchar(max), keterangan_order_item) keterangan, convert(varchar, paketid) paketid, urutan_parameter, '''' diskon_id from order_item_split where dbo.sambung_order_id(order_id, order_id_split, ''-'')=@order_id_input union
						( select @order_id_input, user_id, '''' item_seq, item_id, harga, kuantitas, 0 diskon, isnull(harga * kuantitas, 0) tambahan_diskon, isnull(harga * kuantitas, 0) diskon_total, 100 diskon_total_persen, convert(varchar(max), y.singkatan) keterangan, 
						/*convert(varchar, x.diskon_id)*/ '''' paketid, 0 urutan_parameter, x.diskon_id from order_diskon_freeitem x, diskon y where x.diskon_id = y.diskon_id and x.order_id=@order_id_nosplit and x.gudang = @gudang )  
						)						
						a inner join sgtdat.dbo.icitem b on a.item_id=b.itemno 
						left outer join order_diskon_item c on c.order_id = @order_id_nosplit and a.user_id = c.user_id and a.item_seq = c.item_seq
						left outer join diskon d on c.diskon_id = d.diskon_id
						left outer join mesdb.dbo.tbl_icitem e on b.itemno = e.itemno
						left outer join order_diskon f on f.order_id = c.order_id and f.user_id = c.user_id and f.diskon_id = c.diskon_id
						left outer join order_diskon_freeitem g on g.order_id = @order_id_nosplit and a.user_id = g.user_id and a.item_id = g.item_id and 
							(f.diskon_id = g.diskon_id or a.diskon_id = g.diskon_id) and g.gudang = @gudang
						left outer join diskon h on g.diskon_id = h.diskon_id
						
						where a.order_id=@order_id_input
						order by case a.item_seq when 0 then (select MAX(item_seq)+1 from order_item_split where dbo.sambung_order_id(order_id, order_id_split, ''-'') = a.order_id) else a.item_seq end asc																		


open cursor_item ; fetch next from cursor_item into @order_id, @user_id, @item_seq, @item_id, @harga, @kuantitas, @diskon, @tambahan_diskon, @diskon_total, @diskon_total_persen, @paketid, @paketid, @urutan_parameter, @diskon_id, @kuantitas_diskon_item, @nama_item, @keterangan_diskon_tambahan, @tambahan_diskon_belum_disetujui, @sub_total_campaign, @sub_total, @diskon_tambahan_default, @kategori_gift
while( @@FETCH_STATUS =  0 ) begin
	
	set @diskon = case when @harga <= 0 then 100 * @kuantitas else @diskon end
	set @harga = case when @harga <= 0 then 100 else @harga end	
	
	if( ( isnull(@kuantitas_diskon_item, 0) > 0 and @kuantitas = @kuantitas_diskon_item ) or
			isnull(@kuantitas_diskon_item, 0) = 0 ) begin
		set @diskon_tambahan_default = case when @diskon_tambahan_default <= 100 then @diskon_tambahan_default * ( (@harga * @kuantitas) - @diskon)/100 else @diskon_tambahan_default * @kuantitas  end		
		if not exists(select 1 from @ret_tabel where order_id=@order_id and user_id=@user_id and item_seq=@item_seq and item_seq > 0) begin
			insert into @ret_tabel values(@order_id, @user_id, @item_seq, @item_id, @harga, @kuantitas, @diskon, @diskon_tambahan_default, @diskon+@diskon_tambahan_default, 100*(@diskon+@diskon_tambahan_default)/(@harga*@kuantitas), @paketid, @paketid, @urutan_parameter, @kuantitas_diskon_item, @nama_item, @keterangan_diskon_tambahan, @diskon_tambahan_default, @sub_total_campaign, @sub_total_campaign-@diskon_tambahan_default, @kategori_gift)
		end
		else begin
			update @ret_tabel set 
				tambahan_diskon=(case when tambahan_diskon > 0 then tambahan_diskon else tambahan_diskon end) +@diskon_tambahan_default, 
				diskon_total=(case when diskon_total>0 then diskon_total else diskon_total end) +@diskon_tambahan_default,
				diskon_total_persen=100 * ( (case when diskon_total>0 then diskon_total else diskon_total end) +@diskon_tambahan_default)/(harga*kuantitas),
				tambahan_diskon_belum_disetujui=tambahan_diskon_belum_disetujui+@diskon_tambahan_default,
				keterangan_diskon_tambahan=isnull(keterangan_diskon_tambahan, '''') + (case when len(ltrim(rtrim(keterangan_diskon_tambahan))) > 0 then '','' + @keterangan_diskon_tambahan else @keterangan_diskon_tambahan end),
				sub_total_campaign = harga * kuantitas,
				sub_total = ( harga * kuantitas ) - diskon - ( (case when tambahan_diskon > 0 then tambahan_diskon else tambahan_diskon end) +@diskon_tambahan_default)
			where order_id=@order_id and user_id=@user_id and item_seq=@item_seq --and tambahan_diskon>0		
		end
	end
	else begin
		declare @diskon_item_tambahan_tambahan_diskon float, @diskon_item_tambahan_diskon_total_persen float
		set @diskon_item_tambahan_tambahan_diskon = case when @diskon_tambahan_default <= 100 then ( ( @harga - (@diskon/@kuantitas) ) * @kuantitas_diskon_item) * @diskon_tambahan_default / 100 else @kuantitas_diskon_item  * @diskon_tambahan_default end;
		select @diskon_item_tambahan_tambahan_diskon = case ISNULL(@diskon_item_tambahan_tambahan_diskon, 0) when 0 then 0 else @diskon_item_tambahan_tambahan_diskon end
		set @diskon_item_tambahan_diskon_total_persen = 100 * ( @diskon_item_tambahan_tambahan_diskon + @diskon ) / ( @harga * @kuantitas_diskon_item )

		
		if not exists(select 1 from @ret_tabel where order_id=@order_id and user_id=@user_id and item_seq=@item_seq and tambahan_diskon=0) begin
			insert into @ret_tabel values(@order_id, @user_id, @item_seq, @item_id, @harga, @kuantitas - @kuantitas_diskon_item, @diskon, 0, @diskon+0, (@diskon+0) / (@harga * (@kuantitas - @kuantitas_diskon_item)), @paketid, @paketid, @urutan_parameter, @kuantitas_diskon_item, @nama_item, '''', 0, @sub_total_campaign, @sub_total, @kategori_gift)
			set @sub_total_campaign = (@harga * @kuantitas_diskon_item) - @diskon;
			set @sub_total = @sub_total_campaign - @diskon_item_tambahan_tambahan_diskon
			insert into @ret_tabel values(@order_id, @user_id, @item_seq, @item_id, @harga, @kuantitas_diskon_item, @diskon, @diskon_item_tambahan_tambahan_diskon, (@diskon/@kuantitas)+@diskon_item_tambahan_tambahan_diskon, @diskon_item_tambahan_diskon_total_persen, @paketid, @paketid, @urutan_parameter, @kuantitas_diskon_item, @nama_item, @keterangan_diskon_tambahan, @tambahan_diskon_belum_disetujui, @sub_total_campaign, @sub_total, @kategori_gift)
		end
		else begin
			update @ret_tabel set 
				tambahan_diskon=isnull(tambahan_diskon, 0)+@diskon_item_tambahan_tambahan_diskon, 
				diskon_total=diskon_total+@diskon_item_tambahan_tambahan_diskon,
				diskon_total_persen=100 * (diskon_total+@diskon_item_tambahan_tambahan_diskon)/(harga*kuantitas),
				tambahan_diskon_belum_disetujui=tambahan_diskon_belum_disetujui+@tambahan_diskon_belum_disetujui,
				keterangan_diskon_tambahan=isnull(keterangan_diskon_tambahan, '''') + (case when len(ltrim(rtrim(keterangan_diskon_tambahan))) > 0 then '','' + @keterangan_diskon_tambahan else @keterangan_diskon_tambahan end),
				sub_total_campaign = harga * kuantitas,
				sub_total = ( harga * kuantitas ) - diskon - (tambahan_diskon+@diskon_item_tambahan_tambahan_diskon)
			where order_id=@order_id and user_id=@user_id and item_seq=@item_seq and tambahan_diskon>0
		end
	end
	
	fetch next from cursor_item into @order_id, @user_id, @item_seq, @item_id, @harga, @kuantitas, @diskon, @tambahan_diskon, @diskon_total, @diskon_total_persen, @keterangan, @paketid, @urutan_parameter, @diskon_id, @kuantitas_diskon_item, @nama_item, @keterangan_diskon_tambahan, @tambahan_diskon_belum_disetujui, @sub_total_campaign, @sub_total, @diskon_tambahan_default, @kategori_gift
end
close cursor_item ;
deallocate cursor_item

--select * from @ret_tabel


return

end
' 
END
GO
/****** Object:  UserDefinedFunction [dbo].[ufn_daftar_order_item]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[ufn_daftar_order_item]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
BEGIN
execute dbo.sp_executesql @statement = N'
CREATE function [dbo].[ufn_daftar_order_item]( @order_id_input varchar(50) ) 
returns @ret_tabel table(order_id varchar(50), user_id varchar(50), 
item_seq int, item_id varchar(50), harga float, kuantitas int, 
diskon float, tambahan_diskon float, diskon_total float, diskon_total_persen float, keterangan varchar(500), paketid varchar(50), urutan_parameter smallint, 
kuantitas_diskon_item int, nama_item varchar(500), keterangan_diskon_tambahan varchar(500) default '''', tambahan_diskon_belum_disetujui float, 
sub_total_campaign float, sub_total float, gudang varchar(10), gudang_asal varchar(10), kategori_gift varchar(10)) as begin

/*
declare @ret_tabel table(order_id varchar(50), user_id varchar(50), 
item_seq int, item_id varchar(50), harga float, kuantitas int, 
diskon float, tambahan_diskon float, diskon_total float, diskon_total_persen float, keterangan varchar(500), paketid varchar(50), urutan_parameter smallint, 
kuantitas_diskon_item int, nama_item varchar(500), keterangan_diskon_tambahan varchar(500), tambahan_diskon_belum_disetujui float, 
sub_total_campaign float, sub_total float)
declare @order_id_input varchar(50)
set @order_id_input = ''IM/MS-0417-00689''
*/


declare 
@order_id varchar(50), @user_id varchar(50), 
@item_seq int, @item_id varchar(50), @harga float, @kuantitas int, 
@diskon float, @tambahan_diskon float, @diskon_total float, @diskon_total_persen float, @keterangan varchar(500), @paketid varchar(50), @urutan_parameter smallint, @gudang varchar(10), @diskon_id int,
@kuantitas_diskon_item int, @nama_item varchar(500), @keterangan_diskon_tambahan varchar(500), @tambahan_diskon_belum_disetujui float, 
@sub_total_campaign float, @sub_total float, @diskon_tambahan_default float, @gudang_asal varchar(10), @kategori_gift varchar(10)

declare cursor_item cursor for
select 
a.*,
case when a.item_seq = 0 then a.kuantitas else case when isnull(c.item_seq, 0) > 0 then c.kuantitas_diskon_item end end kuantitas_diskon_item
, case when isnull(e.model, '''') = '''' then b.[desc] else e.model end nama_item, 
case when a.item_seq = 0 then h.singkatan else case when isnull(c.item_seq, 0) > 0 then d.singkatan end end keterangan_diskon_tambahan, 
				case when a.tambahan_diskon > 0 then 0 /*a.tambahan_diskon*/ else dbo.ufn_nilai_diskon_peritem(a.order_id, c.diskon_id, 0, a.item_seq) * c.kuantitas_diskon_item end tambahan_diskon_belum_disetujui,
				(a.harga * (case when c.kuantitas_diskon_item < a.kuantitas then a.kuantitas - c.kuantitas_diskon_item else a.kuantitas end) ) - a.diskon sub_total_campaign,
				(a.harga * ( case when c.kuantitas_diskon_item < a.kuantitas then a.kuantitas - c.kuantitas_diskon_item else a.kuantitas end )) sub_total,
				case when a.item_seq = 0 then 100 else case when isnull(c.item_seq, 0) > 0 then isnull(f.nilai_diskon, 0) else 0 end end nilai_diskon, i.gudang, h.kategori_gift
					from 						
						(
						select order_id, user_id, item_seq, item_id, harga, kuantitas, isnull(diskon, 0) diskon, isnull(tambahan_diskon, 0) tambahan_diskon, isnull(diskon_total, 0) diskon_total, isnull(diskon_total_persen, 0) diskon_total_persen, convert(varchar(max), keterangan_order_item) keterangan, convert(varchar, paketid) paketid, urutan_parameter, gudang, '''' diskon_id from order_item where order_id=@order_id_input union
						( select order_id, user_id, '''' item_seq, item_id, harga, kuantitas, 0 diskon, isnull(harga * kuantitas, 0) tambahan_diskon, isnull(harga * kuantitas, 0) diskon_total, 100 diskon_total_persen, convert(varchar(max), y.singkatan) keterangan, 
						/*convert(varchar, x.diskon_id)*/ '''' paketid, 0 urutan_parameter, gudang, x.diskon_id from order_diskon_freeitem x, diskon y where x.diskon_id = y.diskon_id and x.order_id=@order_id_input )  
						)						
						a inner join sgtdat.dbo.icitem b on a.item_id=b.itemno 
						left outer join order_diskon_item c on a.order_id = c.order_id and a.user_id = c.user_id and a.item_seq = c.item_seq
						left outer join diskon d on c.diskon_id = d.diskon_id
						left outer join mesdb.dbo.tbl_icitem e on b.itemno = e.itemno
						left outer join order_diskon f on f.order_id = c.order_id and f.user_id = c.user_id and f.diskon_id = c.diskon_id
						left outer join order_diskon_freeitem g on a.order_id = g.order_id and a.user_id = g.user_id and a.item_id = g.item_id and 
							(f.diskon_id = g.diskon_id or a.diskon_id = g.diskon_id)
						left outer join diskon h on g.diskon_id = h.diskon_id
						left outer join [order] i on a.order_id = i.order_id and a.user_id = i.user_id 
						
						where a.order_id=@order_id_input
						order by case a.item_seq when 0 then (select MAX(item_seq)+1 from order_item where order_id = a.order_id) else a.item_seq end asc																		


open cursor_item ; fetch next from cursor_item into @order_id, @user_id, @item_seq, @item_id, @harga, @kuantitas, @diskon, @tambahan_diskon, @diskon_total, @diskon_total_persen, @paketid, @paketid, @urutan_parameter, @gudang, @diskon_id, @kuantitas_diskon_item, @nama_item, @keterangan_diskon_tambahan, @tambahan_diskon_belum_disetujui, @sub_total_campaign, @sub_total, @diskon_tambahan_default, @gudang_asal, @kategori_gift
while( @@FETCH_STATUS =  0 ) begin

	set @diskon = case when @harga <= 0 then 100 * @kuantitas else @diskon end
	set @harga = case when @harga <= 0 then 100 else @harga end	
	
	if( ( isnull(@kuantitas_diskon_item, 0) > 0 and @kuantitas = @kuantitas_diskon_item ) or
			isnull(@kuantitas_diskon_item, 0) = 0 ) begin
		set @diskon_tambahan_default = case when @diskon_tambahan_default <= 100 then @diskon_tambahan_default * ( (@harga * @kuantitas) - @diskon)/100 else @diskon_tambahan_default * @kuantitas  end		
		if not exists(select 1 from @ret_tabel where order_id=@order_id and user_id=@user_id and item_seq=@item_seq and item_seq > 0) begin
			insert into @ret_tabel values(@order_id, @user_id, @item_seq, @item_id, @harga, @kuantitas, @diskon, @diskon_tambahan_default, @diskon+@diskon_tambahan_default, 100*(@diskon+@diskon_tambahan_default)/(@harga*@kuantitas), @paketid, @paketid, @urutan_parameter, @kuantitas_diskon_item, @nama_item, @keterangan_diskon_tambahan, @diskon_tambahan_default, @sub_total_campaign, @sub_total_campaign-@diskon_tambahan_default, @gudang, @gudang_asal, @kategori_gift)
		end
		else begin
			update @ret_tabel set 
				tambahan_diskon=(case when tambahan_diskon > 0 then tambahan_diskon else tambahan_diskon end) +@diskon_tambahan_default, 
				diskon_total=(case when diskon_total>0 then diskon_total else diskon_total end) +@diskon_tambahan_default,
				diskon_total_persen=100 * ( (case when diskon_total>0 then diskon_total else diskon_total end) +@diskon_tambahan_default)/(harga*kuantitas),
				tambahan_diskon_belum_disetujui=tambahan_diskon_belum_disetujui+@diskon_tambahan_default,
				keterangan_diskon_tambahan=isnull(keterangan_diskon_tambahan, '''') + (case when len(ltrim(rtrim(keterangan_diskon_tambahan))) > 0 then '','' + @keterangan_diskon_tambahan else @keterangan_diskon_tambahan end),
				sub_total_campaign = harga * kuantitas,
				sub_total = ( harga * kuantitas ) - diskon - ( (case when tambahan_diskon > 0 then tambahan_diskon else tambahan_diskon end) +@diskon_tambahan_default)
			where order_id=@order_id and user_id=@user_id and item_seq=@item_seq --and tambahan_diskon>0		
		end
	end
	else begin
		declare @diskon_item_tambahan_tambahan_diskon float, @diskon_item_tambahan_diskon_total_persen float
		set @diskon_item_tambahan_tambahan_diskon = case when @diskon_tambahan_default <= 100 then ( ( @harga - (@diskon/@kuantitas) ) * @kuantitas_diskon_item) * @diskon_tambahan_default / 100 else @kuantitas_diskon_item  * @diskon_tambahan_default end;
		select @diskon_item_tambahan_tambahan_diskon = case ISNULL(@diskon_item_tambahan_tambahan_diskon, 0) when 0 then 0 else @diskon_item_tambahan_tambahan_diskon end
		set @diskon_item_tambahan_diskon_total_persen = 100 * ( @diskon_item_tambahan_tambahan_diskon + @diskon ) / ( @harga * @kuantitas_diskon_item )

		
		if not exists(select 1 from @ret_tabel where order_id=@order_id and user_id=@user_id and item_seq=@item_seq and tambahan_diskon=0) begin
			insert into @ret_tabel values(@order_id, @user_id, @item_seq, @item_id, @harga, @kuantitas - @kuantitas_diskon_item, @diskon, 0, @diskon+0, (@diskon+0) / (@harga * (@kuantitas - @kuantitas_diskon_item)), @paketid, @paketid, @urutan_parameter, @kuantitas_diskon_item, @nama_item, '''', 0, @sub_total_campaign, @sub_total, @gudang, @gudang_asal, @kategori_gift)
			set @sub_total_campaign = (@harga * @kuantitas_diskon_item) - @diskon;
			set @sub_total = @sub_total_campaign - @diskon_item_tambahan_tambahan_diskon
			insert into @ret_tabel values(@order_id, @user_id, @item_seq, @item_id, @harga, @kuantitas_diskon_item, @diskon, @diskon_item_tambahan_tambahan_diskon, (@diskon/@kuantitas)+@diskon_item_tambahan_tambahan_diskon, @diskon_item_tambahan_diskon_total_persen, @paketid, @paketid, @urutan_parameter, @kuantitas_diskon_item, @nama_item, @keterangan_diskon_tambahan, @tambahan_diskon_belum_disetujui, @sub_total_campaign, @sub_total, @gudang, @gudang_asal, @kategori_gift)
		end
		else begin
			update @ret_tabel set 
				tambahan_diskon=isnull(tambahan_diskon, 0)+@diskon_item_tambahan_tambahan_diskon, 
				diskon_total=diskon_total+@diskon_item_tambahan_tambahan_diskon,
				diskon_total_persen=100 * (diskon_total+@diskon_item_tambahan_tambahan_diskon)/(harga*kuantitas),
				tambahan_diskon_belum_disetujui=tambahan_diskon_belum_disetujui+@tambahan_diskon_belum_disetujui,
				keterangan_diskon_tambahan=isnull(keterangan_diskon_tambahan, '''') + (case when len(ltrim(rtrim(keterangan_diskon_tambahan))) > 0 then '','' + @keterangan_diskon_tambahan else @keterangan_diskon_tambahan end),
				sub_total_campaign = harga * kuantitas,
				sub_total = ( harga * kuantitas ) - diskon - (tambahan_diskon+@diskon_item_tambahan_tambahan_diskon)
			where order_id=@order_id and user_id=@user_id and item_seq=@item_seq and tambahan_diskon>0
		end
	end
	
	fetch next from cursor_item into @order_id, @user_id, @item_seq, @item_id, @harga, @kuantitas, @diskon, @tambahan_diskon, @diskon_total, @diskon_total_persen, @paketid, @paketid, @urutan_parameter, @gudang, @diskon_id, @kuantitas_diskon_item, @nama_item, @keterangan_diskon_tambahan, @tambahan_diskon_belum_disetujui, @sub_total_campaign, @sub_total, @diskon_tambahan_default, @gudang_asal, @kategori_gift
end
close cursor_item ;
deallocate cursor_item

--select * from @ret_tabel


return

end
' 
END
GO
/****** Object:  StoredProcedure [dbo].[DM_uspApvOrderH_Post_TRDAT]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[DM_uspApvOrderH_Post_TRDAT]') AND type in (N'P', N'PC'))
BEGIN
EXEC dbo.sp_executesql @statement = N'-- =============================================
-- Author		:	Wong Tjliek, Isokuiki
-- Create date	:	20130507
-- Description	:	transfer order header from mobile_sales to TRDAT
-- =============================================
CREATE PROCEDURE [dbo].[DM_uspApvOrderH_Post_TRDAT]
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
	
	select @tempOrder = sum((harga - (harga*(diskon_total_persen/100))) * kuantitas) from order_item where order_id = @orderid
	
	select @rCount = COUNT(*) from mobilesales..orderExec where order_id = @orderid
	
	if @rCount = 0
	begin
		insert into orderExec 
			values (@audtuser, @orderid, @sta, @totalorder, @subtotalorder, @subtotal_nodisc)
	end
	
BEGIN TRANSACTION

/*
declare @orderid varchar(100), @audtuser varchar(6), @sta smallint; 
set @orderid=''M-usr-00002-914-00108''; set @audtuser=''MSAS''; set @sta=1;
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
	set @CUSACCTSET=''AR1''
	declare @salesplt1 smallint
	set @salesplt1=100
	declare @diskon decimal(9,5)
	
	declare @ordlines int, @nextdtlnum int
	select @ordlines=COUNT(1) from order_item where order_id=@orderid
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
		set @orduniq = (select max(orduniq)+ 1 as xx from TRDAT.dbo.oeordh)
		
		declare cHeader cursor for
			select 
				mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as audtdate
				,mesdb.dbo.fnSetAccpacTime(convert(varchar(12),getdate(),108)) as audttime
				,''TRDAT'' as audtorg
				,a.order_id as ordnumber
				,a.dealer_id as customer
				,b.idgrp as custgroup
				,b.namecust as bilname
				,b.textstre1 as biladdr1
				,b.textstre2 as biladdr2
				,b.textstre3 as biladdr3
				,b.textstre4 as biladdr4
				,b.namecity as bilcity
				,b.codestte as bilstate
				,b.codepstl as bilzip
				,b.codectry as bilcountry
				,b.textphon1 as bilphone
				,b.textphon2 as bilfax
				,b.namectac as bilcontact
				,b.namecust as shpname
				,b.textstre1 as shpaddr1
				,b.textstre2 as shpaddr2
				,b.textstre3 as shpaddr3
				,b.textstre4 as shpaddr4
				,b.namecity as shpcity
				,b.codestte as shpstate
				,b.codepstl as shpzip
				,b.codectry as shpcountry
				,b.textphon1 as shpphone
				,b.textphon2 as shpfax
				,b.namectac as shpcontact
				,'''' as ponumber
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
			from [order] a inner join TRDAT.dbo.arcus b on a.dealer_id=b.idcust
			inner join [user] c on a.user_id = c.user_id
			where a.order_id=@orderid
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
			--exec procedure insert order to TRDAT.dbo.oeordh
			select @invdiscamt = ROUND(@invdiscamt,0)
			exec TRDAT.dbo.sp_addWO_Post
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
				,'''' /*shipto*/
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
				,''1'' /*custdisc*/
				,@priclist /*pricelist*/
				,@ponumber
				,@territory
				,@terms
				,@totalorder /*termttldue*/
				,''0'' /*discavail*/
				,''0'' /*termoverrd*/
				,@orderid /*MOBILE_SALES*//*reference*/
				,''1'' /*[type]*/
				,@orddate
				,@expdate
				,@qtexpdate
				,@ordfiscyr
				,@ordfisper /*ordfiscper*/
				,'''' /*shipvia*/
				,'''' /*viadesc*/
				,'''' /*lastinvnum*/
				,''0'' /*numinvoice*/
				,'''' /*fob*/
				,'''' /*template*/
				,@gudang /*location*/
				,''0'' /*onhold*/
				,'''' /*[desc]*/
				,'''' /*comment*/
				,''1'' /*printstat*/
				,@lastpost
				,''0'' /*ornoprepay*/
				,''0'' /*overcredit*/
				,''0'' /*approvelmt*/
				,'''' /*approveby*/
				,''0'' /*shiplabel*/
				,''0'' /*lblprinted*/
				,@orhomecurr
				,''SP'' /*orratetype*/
				,@orsourcurr
				,@orratedate
				,''1'' /*orrate*/
				,''0'' /*orspread*/
				,''3'' /*ordatemtch*/
				,''1'' /*orraterep*/
				,''0'' /*orrateover*/
				,@subtotal_nodisc /*ordtotal*/
				,''0'' /*ordmtotal*/
				,@ordlines /*ordlines*/
				,''1'' /*numlabels*/
				,''0'' /*ordpaytot*/
				,''0'' /*ordpydstot*/
				,@salesper1
				,@salesper2
				,@salesper3
				,@salesper4
				,@salesper5
				,@salesplt1 /*salesplt1*/
				,''0'' /*salesplt2*/
				,''0'' /*salesplt3*/
				,''0'' /*salesplt4*/
				,''0'' /*salesplt5*/
				,''0'' /*recalctac*/
				,''0'' /*taxoverrd*/
				,@taxgroup
				,@tauth1
				,'''' /*tauth2*/
				,'''' /*tauth3*/
				,'''' /*tauth4*/
				,'''' /*tauth5*/
				,@tclass1 /*tclass1*/
				,''0'' /*tclass2*/
				,''0'' /*tclass3*/
				,''0'' /*tclass4*/
				,''0'' /*tclass5*/
				,@tbase1  /*tbase1*/
				,''0'' /*tbase2*/
				,''0'' /*tbase3*/
				,''0'' /*tbase4*/
				,''0'' /*tbase5*/
				,''0'' /*teamount1*/
				,''0'' /*teamount2*/
				,''0'' /*teamount3*/
				,''0'' /*teamount4*/
				,''0'' /*teamount5*/
				,@pajak /*tiamount1*/
				,''0'' /*tiamount2*/
				,''0'' /*tiamount3*/
				,''0'' /*tiamount4*/
				,''0'' /*tiamount5*/
				,'''' /*texempt1*/
				,'''' /*texempt2*/
				,'''' /*texempt3*/
				,'''' /*texempt4*/
				,'''' /*texempt5*/
				,'''' /*optional1*/
				,'''' /*optional2*/
				,'''' /*optional3*/
				,'''' /*optional4*/
				,'''' /*optional5*/
				,'''' /*optional6*/
				,0 /*optdate*/
				,0 /*optamt*/
				,''1'' /*complete*/
				,''0'' /*compdate*/
				,''*** NEW ***'' /*invnumber*/
				,@shipdate
				,@invdate
				,@invfiscyr
				,@invfiscper /*invfiscper*/
				,''1'' /*numpayment*/
				,@paymntasof
				,''0'' /*invweight*/
				,@nextdtlnum /*nextdtlnum*/
				,''1'' /*postinv*/
				,''0'' /*idisonmisc*/
				,''0'' /*innoprepay*/
				,''0'' /*noshipline*/
				,'''' /*nomiscline*/
				,@tanpaPajak /* @totalorder /*invnetnotx*/ */
				,@pajak /*invitaxtot*/
				,@subtotal_nodisc  /*invitmtot*/
				,@subtotalorder /*invdiscbas*/
				,@diskon /*invdiscper*/
				,@invdiscamt /*invdiscamt*/
				,''0'' /*invmisc*/
				,@subtotal_nodisc  /*invsubtot*/
				,@totalorder  /*invnet*/
				,''0'' /*invetaxtot*/
				,@totalorder /*invnetwtx*/
				,@totalorder  /*invamtude*/
				,@inhomecurr
				,''SP'' /*inratetype*/
				,@insourcurr
				,@inratedate
				,''1'' /*inrate*/
				,''0'' /*inspread*/
				,''3'' /*indatemtch*/
				,''1'' /*inraterep*/
				,''0'' /*inrateover*/
				,@ordersourc
				,'''' /*csisusr*/
				,@CUSACCTSET /*CUSACCTSET*/
				
			-- insert di OETERMO
			/*insert into TRDAT..OETERMO(
				ORDUNIQ,
				PAYMENT,
				AUDTDATE,
				AUDTTIME,
				AUDTUSER,
				AUDTORG,
				DISCBASE,
				DISCDATE,
				DISCPER,
				DISCAMT,
				DUEBASE,
				DUEDATE,
				DUEPER,
				DUEAMT
			)values(
				@orduniq,
				32,
				@audtdate,
				@audttime,
				@audtuser,
				@audtorg,
				@totalorder,
				@audtdate,
				@diskon,
				0,
				@totalorder,
				mesdb.dbo.fnSetAccpacDate(convert(varchar(12),dateadd(day, 30, getdate()),101)),
				100,
				@totalorder				
			)*/
			
--print ''[uspApvOrderD] ''+convert(varchar, @orderid)+'',''+convert(varchar(50), @orduniq)+'',''+convert(varchar(10), @audtuser)+'',''+convert(varchar(100), @totalorder)+'',''+convert(varchar(100), @subtotalorder)
			exec [DM_uspApvOrderD_TRDAT] @orderid,@orduniq,@audtuser,@totalorder,@subtotalorder 
			--exec [uspApvOrderD_TRDAT] @orderid,@orduniq,@audtuser,@tempOrder,@subtotalorder 
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
	--end
	--update approval
	--	update [order] set
	--	[status] = @sta
	--	,approval=1
	--	,tanggal_approval=GETDATE()
	--	where order_id=@orderid
	
IF @@ERROR <> 0
	BEGIN
		ROLLBACK
		RAISERROR (''ERROR'', 16, 1)
		RETURN
	END
COMMIT

--select * from [order]
--begin tran
--exec [uspApvOrderH] ''ORD-00001'',''ADM''
--rollback tran
--commit tran

' 
END
GO
/****** Object:  StoredProcedure [dbo].[DM_uspApvOrderD_split_SGTDAT]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[DM_uspApvOrderD_split_SGTDAT]') AND type in (N'P', N'PC'))
BEGIN
EXEC dbo.sp_executesql @statement = N'-- =============================================
-- Author		:	Wong Tjliek, Isokuiki
-- Create date	:	20130507
-- Description	:	transfer order details from mobile_sales to SGTDAT
-- =============================================

CREATE PROCEDURE [dbo].[DM_uspApvOrderD_split_SGTDAT]
	(
		@orderid nvarchar(50),
		@orduniq decimal(19,0),
		@audtuser char(8),
		@totalorder decimal(19,3),
		@subtotalorder decimal(19,3),
		@boolLimit smallint
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
	declare @diskon_per_invoice decimal(19,3), @keterangan_per_item varchar(60), @codeterr varchar(10), @kategori_gift varchar(10), @lokasi_asal varchar(10), @acctset varchar(5)
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

	select @tmpCategory = case b.bm
					when ''MBSD02'' then ''KLM''
					when ''MBSD32'' then ''JKT''
					when ''MBSD20'' then ''JKM''end, @salesDealer = kode_sales
					from [order_split] a
					left join [user] b on a.[user_id] = b.[user_id]
					where dbo.sambung_order_id(a.order_id, a.order_id_split, ''-'') = @orderid
					
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
	dbo.ufn_daftar_order_item_split(@orderid) a
	
	declare @tbase1_dialokasikan decimal(19, 3), @tiamount1_dialokasikan decimal(19, 3)
	set @tbase1_dialokasikan = 0
	set @tiamount1_dialokasikan = 0

	
	declare cDetails cursor for
	select mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as audtdate
		,mesdb.dbo.fnSetAccpacTime(convert(varchar(12),getdate(),108)) as audttime
		,''SGTDAT'' as audtorg
		,1 as linetype
		,b.itemno
		,b.fmtitemno as item
		,b.[desc] as itemdesc
		,c.gudang as location
		,mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),106)) as expdate
		,a.kuantitas as qtyordered
		,a.diskon_total_persen
		,a.harga
		,e.priclist
		,e.custtype
		,c.diskon diskon_per_invoice
		,a.kuantitas * a.harga harga_total,
		left(isnull(a.keterangan+(case when isnull(a.keterangan,'''')='''' then '''' else case when isnull(a.keterangan_diskon_tambahan, '''') = '''' then '''' else '','' end end)+isnull(a.keterangan_diskon_tambahan, ''''), ''''), 60) keterangan_per_item
		,e.codeterr, isnull(a.kategori_gift, '''') kategori_gift, f.gudang lokasi_asal, b.cntlacct
		from
dbo.ufn_daftar_order_item_split( @orderid )
	a inner join SGTDAT.dbo.icitem b on a.item_id=b.itemno 
	inner join [order_split] c on a.order_id=dbo.sambung_order_id(c.order_id, c.order_id_split, ''-'')
	inner join [user] d on c.user_id=d.user_id
	inner join SGTDAT.dbo.arcus e on c.dealer_id=e.idcust
	inner join [order] f on c.order_id = f.order_id
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
		,@lokasi_asal
		,@acctset
	
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
		
		select @category=
			( case when @kategori_gift = '''' then ''PBJ'' + location_pbj else replace(@kategori_gift, ''#'', location_pbj) end )
			from SGTDAT.dbo.mis_loc_active where location=@lokasi_asal
			
		-- Added by Fajar 2016-12-08
		-- Set category by product focus
		if @salesDealer <> ''MBSD42'' -- agus sukamto tidak masuk product focus
		begin
			if @idGrp = ''B'' OR @idGrp = ''C'' OR @idGrp = ''M''
			begin
				select @checkFocus = count(*) from SGTDAT..MIS_ITEM_FOCUS where item = LEFT(@item,2)
				-- Jika product focus, kategori mengikuti produk
				if @checkFocus > 0
				begin
					select @category = category from SGTDAT..MIS_ITEM_FOCUS where item = LEFT(@item,2)
				end
				else -- jika proffesional appliances
				begin				
					select @category = @tmpCategory
				end
			end
		end
		else if @salesDealer = ''MBSD42''
		begin
			select @category = ''PBJJKM''
		end
		
		-- UNTUK PAMERAN DAN DEALERNYA TIM PROJECT
		if @idGrp IN (''A1'')
			begin
				if @codeterr = ''221'' begin
					select @category = ''PBJBC''
				end
				else begin
					select @category = ''PBJOTH''
				end
			end
		
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
			,'''' /*misccharge*/		
			,@itemdesc					
			,@acctset /*acctset*/			
			,0 /*''false'' usercostmd*/
			,@pricelist /*pricelist*/	
			,@category /*category*/	
			,@location				
			,'''' /*pickseq*/				
			,@expdate			
			,1 /*''true'' stockitem*/
			,@qtyordered		
			,0 /*qtyshipped*/	
			,@qtyordered /*qtybackord*/		
			,0 /*qtyshptodt*/			
			,@qtyordered /*origqty*/			
			,0 /*qtypo*/
			,''UNIT'' /*ordunit*/		
			,1 /*unitconv*/		
			,@harga /*unitprice*/		
			,0 /*''false'' priceover*/	
			,@unitcost /*unitcost*/			
			,@mostrec /*mostrec*/		
			,0 /*stdcost*/		
			,0 /*cost1*/		
			,0 /*cost2*/			
			,0 /* 6 unitprcdec*/			
			,''UNIT'' /*priceunit*/		
			,@harga /*priuntprc*/
			,1 /*priuntconv*/	
			,@pripercent /*pripercent*/	
			,0 /*priamount*/		
			,''UNIT'' /*baseunit*/			
			,@pribasprc /*pribasprc*/		
			,1 /*pribasconv*/
			,''UNIT'' /*costunit*/	
			,@unitcost /*cosuntcst*/	
			,1 /*cosuntconv*/		
			,0 /*extoprice*/			
			,@extocost /*extocost*/			
			,@harga_total /*extinvmisc*/
			,@diskon_nominal_total /*invdisc -- ini diisi diskon*/		
			,0 /*exticost*/		
			,0 /*''false'' extover*/	
			,0 /*unitweight*/			
			,0 /*extweight*/		
			,0 /*complete*/
			,1 /*''false'' addtoiloc*/ 
			,0 /*saleslost*/	
			,''PPN1'' /*tauth1*/		
			,'''' /*tauth2*/				
			,'''' /*tauth3*/			
			,'''' /*tauth4*/
			,'''' /*tauth5*/		
			,2 /*tclass1*/		
			,0 /*tclass2*/			
			,0 /*tclass3*/				
			,0 /*tclass4*/			
			,0 /*tclass5*/
			,1 /*''false'' tincluded1*/				
			,0/*''false'' tincluded2*/
			,0/*''false'' tincluded3*/	
			,0/*''false'' tincluded4*/
			,0/*''false'' tincluded5*/		
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
			,'''' /*miscacct*/			
			,@detailnum /*detailnum*/		
			,'''' /*haveserial*/
			,'''' /*comminst*/	
			,'''' /*glnonstkcr*/
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
			,@lokasi_asal
			,@acctset
	end
	close cDetails
	deallocate cDetails
	
	update SGTDAT..OEORDH1 set ITEMDISTOT=@diskon_nominal_total_semua_item where ORDUNIQ=@orduniq
	
	
	
IF @@ERROR <> 0
	BEGIN
		ROLLBACK
		RAISERROR (''ERRORX'', 16, 1)
		RETURN
	END
COMMIT
--select * from [mobile_sales].[dbo].[order] 

--begin tran
--exec [mobile_sales].[dbo].[uspApvOrderD] ''ORD-00001'',12609027,''ADM''
--rollback tran





' 
END
GO
/****** Object:  StoredProcedure [dbo].[DM_uspApvOrderD_SGTDAT]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[DM_uspApvOrderD_SGTDAT]') AND type in (N'P', N'PC'))
BEGIN
EXEC dbo.sp_executesql @statement = N'-- =============================================
-- Author		:	Wong Tjliek, Isokuiki
-- Create date	:	20130507
-- Description	:	transfer order details from mobile_sales to SGTDAT
-- =============================================

CREATE PROCEDURE [dbo].[DM_uspApvOrderD_SGTDAT]
	(
		@orderid nvarchar(50),
		@orduniq decimal(19,0),
		@audtuser char(8),
		@totalorder decimal(19,3),
		@subtotalorder decimal(19,3),
		@boolLimit smallint
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
	declare @diskon_per_invoice decimal(19,3), @keterangan_per_item varchar(60), @codeterr varchar(10), @kategori_gift varchar(10), @acctset varchar(5)
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

	select @tmpCategory = case b.bm
					when ''MBSD02'' then ''KLM''
					when ''MBSD32'' then ''JKT''
					when ''MBSD20'' then ''JKM''end, @salesDealer = kode_sales
					from [order] a
					left join [user] b on a.[user_id] = b.[user_id]
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
	dbo.ufn_daftar_order_item(@orderid) a
	/*
	(
	select order_id, user_id, item_seq, item_id, harga, kuantitas, diskon, tambahan_diskon, diskon_total, diskon_total_persen, convert(varchar(max), keterangan_order_item) keterangan, convert(varchar, paketid) paketid, urutan_parameter from order_item where order_id = @orderid union
	( select order_id, user_id, '''' item_seq, item_id, harga, kuantitas, harga diskon, 0 tambahan_diskon, harga diskon_total, 100 diskon_total_persen, convert(varchar(max), y.singkatan) keterangan, 
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
		,''SGTDAT'' as audtorg
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
		,''SGTDAT'' as audtorg
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
		,a.kuantitas * a.harga harga_total,
		left(isnull(a.keterangan+(case when isnull(a.keterangan,'''')='''' then '''' else case when isnull(a.keterangan_diskon_tambahan, '''') = '''' then '''' else '','' end end)+isnull(a.keterangan_diskon_tambahan, ''''), ''''), 60) keterangan_per_item
		,e.codeterr, isnull(a.kategori_gift, '''') kategori_gift, b.cntlacct
	from 
	/*
	(
	select order_id, user_id, item_seq, item_id, harga, kuantitas, diskon, tambahan_diskon, diskon_total, diskon_total_persen, convert(varchar(max), keterangan_order_item) keterangan, convert(varchar, paketid) paketid, urutan_parameter from order_item union
	( select order_id, user_id, '''' item_seq, item_id, harga, kuantitas, (harga * kuantitas) diskon, 0 tambahan_diskon, (harga * kuantitas) diskon_total, 100 diskon_total_persen, convert(varchar(max), y.singkatan) keterangan, 
	convert(varchar, x.diskon_id) paketid, 0 urutan_parameter from order_diskon_freeitem x, diskon y where x.diskon_id = y.diskon_id )  )
	*/
	dbo.ufn_daftar_order_item( @orderid )
	a inner join SGTDAT.dbo.icitem b on a.item_id=b.itemno 
	inner join [order] c on a.order_id=c.order_id
	inner join [user] d on c.user_id=d.user_id
	inner join SGTDAT.dbo.arcus e on c.dealer_id=e.idcust
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
		
		select @category=
			( case when @kategori_gift = '''' then ''PBJ'' + location_pbj else replace(@kategori_gift, ''#'', location_pbj) end )
			from SGTDAT.dbo.mis_loc_active where location=@location
			
		-- Added by Fajar 2016-12-08
		-- Set category by product focus
		if @salesDealer <> ''MBSD42'' -- agus sukamto tidak masuk product focus
		begin
			if @idGrp = ''B'' OR @idGrp = ''C'' OR @idGrp = ''M''
			begin
				select @checkFocus = count(*) from SGTDAT..MIS_ITEM_FOCUS where item = LEFT(@item,2)
				-- Jika product focus, kategori mengikuti produk
				if @checkFocus > 0
				begin
					select @category = category from SGTDAT..MIS_ITEM_FOCUS where item = LEFT(@item,2)
				end
				else -- jika proffesional appliances
				begin				
					select @category = @tmpCategory
				end
			end
		end
		else if @salesDealer = ''MBSD42''
		begin
			select @category = ''PBJJKM''
		end
		
		-- UNTUK PAMERAN DAN DEALERNYA TIM PROJECT
		if @idGrp IN (''A1'')
			begin
				if @codeterr = ''221'' begin
					select @category = ''PBJBC''
				end
				else begin
					select @category = ''PBJOTH''
				end
			end
		
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
			,'''' /*misccharge*/		
			,@itemdesc					
			,@acctset /*acctset*/			
			,0 /*''false'' usercostmd*/
			,@pricelist /*pricelist*/	
			,@category /*category*/	
			,@location				
			,'''' /*pickseq*/				
			,@expdate			
			,1 /*''true'' stockitem*/
			,@qtyordered		
			,0 /*qtyshipped*/	
			,@qtyordered /*qtybackord*/		
			,0 /*qtyshptodt*/			
			,@qtyordered /*origqty*/			
			,0 /*qtypo*/
			,''UNIT'' /*ordunit*/		
			,1 /*unitconv*/		
			,@harga /*unitprice*/		
			,0 /*''false'' priceover*/	
			,@unitcost /*unitcost*/			
			,@mostrec /*mostrec*/		
			,0 /*stdcost*/		
			,0 /*cost1*/		
			,0 /*cost2*/			
			,0 /* 6 unitprcdec*/			
			,''UNIT'' /*priceunit*/		
			,@harga /*priuntprc*/
			,1 /*priuntconv*/	
			,@pripercent /*pripercent*/	
			,0 /*priamount*/		
			,''UNIT'' /*baseunit*/			
			,@pribasprc /*pribasprc*/		
			,1 /*pribasconv*/
			,''UNIT'' /*costunit*/	
			,@unitcost /*cosuntcst*/	
			,1 /*cosuntconv*/		
			,0 /*extoprice*/			
			,@extocost /*extocost*/			
			,@harga_total /*extinvmisc*/
			,@diskon_nominal_total /*invdisc -- ini diisi diskon*/		
			,0 /*exticost*/		
			,0 /*''false'' extover*/	
			,0 /*unitweight*/			
			,0 /*extweight*/		
			,0 /*complete*/
			,1 /*''false'' addtoiloc*/ 
			,0 /*saleslost*/	
			,''PPN1'' /*tauth1*/		
			,'''' /*tauth2*/				
			,'''' /*tauth3*/			
			,'''' /*tauth4*/
			,'''' /*tauth5*/		
			,2 /*tclass1*/		
			,0 /*tclass2*/			
			,0 /*tclass3*/				
			,0 /*tclass4*/			
			,0 /*tclass5*/
			,1 /*''false'' tincluded1*/				
			,0/*''false'' tincluded2*/
			,0/*''false'' tincluded3*/	
			,0/*''false'' tincluded4*/
			,0/*''false'' tincluded5*/		
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
			,'''' /*miscacct*/			
			,@detailnum /*detailnum*/		
			,'''' /*haveserial*/
			,'''' /*comminst*/	
			,'''' /*glnonstkcr*/
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
	end
	close cDetails
	deallocate cDetails
	
	update SGTDAT..OEORDH1 set ITEMDISTOT=@diskon_nominal_total_semua_item where ORDUNIQ=@orduniq
	
	
	
IF @@ERROR <> 0
	BEGIN
		ROLLBACK
		RAISERROR (''ERRORX'', 16, 1)
		RETURN
	END
COMMIT
--select * from [mobile_sales].[dbo].[order] 

--begin tran
--exec [mobile_sales].[dbo].[uspApvOrderD] ''ORD-00001'',12609027,''ADM''
--rollback tran





' 
END
GO
/****** Object:  StoredProcedure [dbo].[DM_uspApvOrderH_Post_split_SGTDAT]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[DM_uspApvOrderH_Post_split_SGTDAT]') AND type in (N'P', N'PC'))
BEGIN
EXEC dbo.sp_executesql @statement = N'-- =============================================
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
		where dbo.sambung_order_id(order_id, order_id_split, ''-'') = @orderid
	
	select @rCount = COUNT(*) from orderExec where order_id = @orderid
	
	if @rCount = 0
	begin
		insert into orderExec 
			values (@audtuser, @orderid, @sta, @totalorder, @subtotalorder, @subtotal_nodisc)
	end
	
BEGIN TRANSACTION

/*
declare @orderid varchar(100), @audtuser varchar(6), @sta smallint; 
set @orderid=''M-usr-00002-914-00108''; set @audtuser=''MSAS''; set @sta=1;
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
	set @CUSACCTSET=''AR1''
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
				,''SGTDAT'' as audtorg
				,dbo.sambung_order_id(a.order_id, a.order_id_split, ''-'') as ordnumber
				,a.dealer_id as customer
				,b.idgrp as custgroup
				,b.namecust as bilname
				,b.textstre1 as biladdr1
				,b.textstre2 as biladdr2
				,b.textstre3 as biladdr3
				,b.textstre4 as biladdr4
				,b.namecity as bilcity
				,b.codestte as bilstate
				,b.codepstl as bilzip
				,b.codectry as bilcountry
				,b.textphon1 as bilphone
				,b.textphon2 as bilfax
				,b.namectac as bilcontact
				,b.namecust as shpname
				,b.textstre1 as shpaddr1
				,b.textstre2 as shpaddr2
				,b.textstre3 as shpaddr3
				,b.textstre4 as shpaddr4
				,b.namecity as shpcity
				,b.codestte as shpstate
				,b.codepstl as shpzip
				,b.codectry as shpcountry
				,b.textphon1 as shpphone
				,b.textphon2 as shpfax
				,b.namectac as shpcontact
				,'''' as ponumber
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
			where dbo.sambung_order_id(a.order_id, a.order_id_split, ''-'')=@orderid
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
				,'''' /*shipto*/
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
				,''1'' /*custdisc*/
				,@priclist /*pricelist*/
				,@ponumber
				,@territory
				,@terms
				,@totalorder /*termttldue*/
				,''0'' /*discavail*/
				,''0'' /*termoverrd*/
				,@orderid /*MOBILE_SALES*//*reference*/
				,''1'' /*[type]*/
				,@orddate
				,@expdate
				,@qtexpdate
				,@ordfiscyr
				,@ordfisper /*ordfiscper*/
				,'''' /*shipvia*/
				,'''' /*viadesc*/
				,'''' /*lastinvnum*/
				,''0'' /*numinvoice*/
				,'''' /*fob*/
				,'''' /*template*/
				,@gudang /*location*/
				,''0'' /*onhold*/
				,'''' /*[desc]*/
				,'''' /*comment*/
				,''1'' /*printstat*/
				,@lastpost
				,''0'' /*ornoprepay*/
				,''0'' /*overcredit*/
				,''0'' /*approvelmt*/
				,'''' /*approveby*/
				,''0'' /*shiplabel*/
				,''0'' /*lblprinted*/
				,@orhomecurr
				,''SP'' /*orratetype*/
				,@orsourcurr
				,@orratedate
				,''1'' /*orrate*/
				,''0'' /*orspread*/
				,''3'' /*ordatemtch*/
				,''1'' /*orraterep*/
				,''0'' /*orrateover*/
				,@subtotal_nodisc /*ordtotal*/
				,''0'' /*ordmtotal*/
				,@ordlines /*ordlines*/
				,''1'' /*numlabels*/
				,''0'' /*ordpaytot*/
				,''0'' /*ordpydstot*/
				,@salesper1
				,@salesper2
				,@salesper3
				,@salesper4
				,@salesper5
				,@salesplt1 /*salesplt1*/
				,''0'' /*salesplt2*/
				,''0'' /*salesplt3*/
				,''0'' /*salesplt4*/
				,''0'' /*salesplt5*/
				,''0'' /*recalctac*/
				,''0'' /*taxoverrd*/
				,@taxgroup
				,@tauth1
				,'''' /*tauth2*/
				,'''' /*tauth3*/
				,'''' /*tauth4*/
				,'''' /*tauth5*/
				,@tclass1 /*tclass1*/
				,''0'' /*tclass2*/
				,''0'' /*tclass3*/
				,''0'' /*tclass4*/
				,''0'' /*tclass5*/
				,@tbase1  /*tbase1*/
				,''0'' /*tbase2*/
				,''0'' /*tbase3*/
				,''0'' /*tbase4*/
				,''0'' /*tbase5*/
				,''0'' /*teamount1*/
				,''0'' /*teamount2*/
				,''0'' /*teamount3*/
				,''0'' /*teamount4*/
				,''0'' /*teamount5*/
				,@pajak /*tiamount1*/
				,''0'' /*tiamount2*/
				,''0'' /*tiamount3*/
				,''0'' /*tiamount4*/
				,''0'' /*tiamount5*/
				,'''' /*texempt1*/
				,'''' /*texempt2*/
				,'''' /*texempt3*/
				,'''' /*texempt4*/
				,'''' /*texempt5*/
				,'''' /*optional1*/
				,'''' /*optional2*/
				,'''' /*optional3*/
				,'''' /*optional4*/
				,'''' /*optional5*/
				,'''' /*optional6*/
				,0 /*optdate*/
				,0 /*optamt*/
				,''1'' /*complete*/
				,''0'' /*compdate*/
				,''*** NEW ***'' /*invnumber*/
				,@shipdate
				,@invdate
				,@invfiscyr
				,@invfiscper /*invfiscper*/
				,''1'' /*numpayment*/
				,@paymntasof
				,''0'' /*invweight*/
				,@nextdtlnum /*nextdtlnum*/
				,''1'' /*postinv*/
				,''0'' /*idisonmisc*/
				,''0'' /*innoprepay*/
				,''0'' /*noshipline*/
				,'''' /*nomiscline*/
				,@tanpaPajak /* @totalorder /*invnetnotx*/ */
				,@pajak /*invitaxtot*/
				,@subtotal_nodisc  /*invitmtot*/
				,@subtotalorder /*invdiscbas*/
				,@diskon /*invdiscper*/
				,@invdiscamt /*invdiscamt*/
				,''0'' /*invmisc*/
				,@subtotal_nodisc  /*invsubtot*/
				,@totalorder  /*invnet*/
				,''0'' /*invetaxtot*/
				,@totalorder /*invnetwtx*/
				,@totalorder  /*invamtude*/
				,@inhomecurr
				,''SP'' /*inratetype*/
				,@insourcurr
				,@inratedate
				,''1'' /*inrate*/
				,''0'' /*inspread*/
				,''3'' /*indatemtch*/
				,''1'' /*inraterep*/
				,''0'' /*inrateover*/
				,@ordersourc
				,'''' /*csisusr*/
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
		RAISERROR (''ERROR'', 16, 1)
		RETURN
	END
COMMIT

--select * from [order]
--begin tran
--exec [uspApvOrderH] ''ORD-00001'',''ADM''
--rollback tran
--commit tran

' 
END
GO
/****** Object:  StoredProcedure [dbo].[DM_uspApvOrderH_Post_SGTDAT]    Script Date: 07/11/2017 16:41:32 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[DM_uspApvOrderH_Post_SGTDAT]') AND type in (N'P', N'PC'))
BEGIN
EXEC dbo.sp_executesql @statement = N'-- =============================================
-- Author		:	Wong Tjliek, Isokuiki
-- Create date	:	20130507
-- Description	:	transfer order header from mobile_sales to SGTDAT
-- =============================================
CREATE PROCEDURE [dbo].[DM_uspApvOrderH_Post_SGTDAT]
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
	
	select @tempOrder = sum((harga - (harga*(diskon_total_persen/100))) * kuantitas) from order_item where order_id = @orderid
	
	select @rCount = COUNT(*) from mobilesales..orderExec where order_id = @orderid
	
	if @rCount = 0
	begin
		insert into orderExec 
			values (@audtuser, @orderid, @sta, @totalorder, @subtotalorder, @subtotal_nodisc)
	end
	
BEGIN TRANSACTION

/*
declare @orderid varchar(100), @audtuser varchar(6), @sta smallint; 
set @orderid=''M-usr-00002-914-00108''; set @audtuser=''MSAS''; set @sta=1;
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
	set @CUSACCTSET=''AR1''
	declare @salesplt1 smallint
	set @salesplt1=100
	declare @diskon decimal(9,5)
	
	declare @ordlines int, @nextdtlnum int
	select @ordlines=COUNT(1) from dbo.ufn_daftar_order_item(@orderid)
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
				,''SGTDAT'' as audtorg
				,a.order_id as ordnumber
				,a.dealer_id as customer
				,b.idgrp as custgroup
				,b.namecust as bilname
				,b.textstre1 as biladdr1
				,b.textstre2 as biladdr2
				,b.textstre3 as biladdr3
				,b.textstre4 as biladdr4
				,b.namecity as bilcity
				,b.codestte as bilstate
				,b.codepstl as bilzip
				,b.codectry as bilcountry
				,b.textphon1 as bilphone
				,b.textphon2 as bilfax
				,b.namectac as bilcontact
				,b.namecust as shpname
				,b.textstre1 as shpaddr1
				,b.textstre2 as shpaddr2
				,b.textstre3 as shpaddr3
				,b.textstre4 as shpaddr4
				,b.namecity as shpcity
				,b.codestte as shpstate
				,b.codepstl as shpzip
				,b.codectry as shpcountry
				,b.textphon1 as shpphone
				,b.textphon2 as shpfax
				,b.namectac as shpcontact
				,'''' as ponumber
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
			from [order] a inner join SGTDAT.dbo.arcus b on a.dealer_id=b.idcust
				inner join [user] c on a.user_id = c.user_id
			where a.order_id=@orderid
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
				,'''' /*shipto*/
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
				,''1'' /*custdisc*/
				,@priclist /*pricelist*/
				,@ponumber
				,@territory
				,@terms
				,@totalorder /*termttldue*/
				,''0'' /*discavail*/
				,''0'' /*termoverrd*/
				,@orderid /*MOBILE_SALES*//*reference*/
				,''1'' /*[type]*/
				,@orddate
				,@expdate
				,@qtexpdate
				,@ordfiscyr
				,@ordfisper /*ordfiscper*/
				,'''' /*shipvia*/
				,'''' /*viadesc*/
				,'''' /*lastinvnum*/
				,''0'' /*numinvoice*/
				,'''' /*fob*/
				,'''' /*template*/
				,@gudang /*location*/
				,''0'' /*onhold*/
				,'''' /*[desc]*/
				,'''' /*comment*/
				,''1'' /*printstat*/
				,@lastpost
				,''0'' /*ornoprepay*/
				,''0'' /*overcredit*/
				,''0'' /*approvelmt*/
				,'''' /*approveby*/
				,''0'' /*shiplabel*/
				,''0'' /*lblprinted*/
				,@orhomecurr
				,''SP'' /*orratetype*/
				,@orsourcurr
				,@orratedate
				,''1'' /*orrate*/
				,''0'' /*orspread*/
				,''3'' /*ordatemtch*/
				,''1'' /*orraterep*/
				,''0'' /*orrateover*/
				,@subtotal_nodisc /*ordtotal*/
				,''0'' /*ordmtotal*/
				,@ordlines /*ordlines*/
				,''1'' /*numlabels*/
				,''0'' /*ordpaytot*/
				,''0'' /*ordpydstot*/
				,@salesper1
				,@salesper2
				,@salesper3
				,@salesper4
				,@salesper5
				,@salesplt1 /*salesplt1*/
				,''0'' /*salesplt2*/
				,''0'' /*salesplt3*/
				,''0'' /*salesplt4*/
				,''0'' /*salesplt5*/
				,''0'' /*recalctac*/
				,''0'' /*taxoverrd*/
				,@taxgroup
				,@tauth1
				,'''' /*tauth2*/
				,'''' /*tauth3*/
				,'''' /*tauth4*/
				,'''' /*tauth5*/
				,@tclass1 /*tclass1*/
				,''0'' /*tclass2*/
				,''0'' /*tclass3*/
				,''0'' /*tclass4*/
				,''0'' /*tclass5*/
				,@tbase1  /*tbase1*/
				,''0'' /*tbase2*/
				,''0'' /*tbase3*/
				,''0'' /*tbase4*/
				,''0'' /*tbase5*/
				,''0'' /*teamount1*/
				,''0'' /*teamount2*/
				,''0'' /*teamount3*/
				,''0'' /*teamount4*/
				,''0'' /*teamount5*/
				,@pajak /*tiamount1*/
				,''0'' /*tiamount2*/
				,''0'' /*tiamount3*/
				,''0'' /*tiamount4*/
				,''0'' /*tiamount5*/
				,'''' /*texempt1*/
				,'''' /*texempt2*/
				,'''' /*texempt3*/
				,'''' /*texempt4*/
				,'''' /*texempt5*/
				,'''' /*optional1*/
				,'''' /*optional2*/
				,'''' /*optional3*/
				,'''' /*optional4*/
				,'''' /*optional5*/
				,'''' /*optional6*/
				,0 /*optdate*/
				,0 /*optamt*/
				,''1'' /*complete*/
				,''0'' /*compdate*/
				,''*** NEW ***'' /*invnumber*/
				,@shipdate
				,@invdate
				,@invfiscyr
				,@invfiscper /*invfiscper*/
				,''1'' /*numpayment*/
				,@paymntasof
				,''0'' /*invweight*/
				,@nextdtlnum /*nextdtlnum*/
				,''1'' /*postinv*/
				,''0'' /*idisonmisc*/
				,''0'' /*innoprepay*/
				,''0'' /*noshipline*/
				,'''' /*nomiscline*/
				,@tanpaPajak /* @totalorder /*invnetnotx*/ */
				,@pajak /*invitaxtot*/
				,@subtotal_nodisc  /*invitmtot*/
				,@subtotalorder /*invdiscbas*/
				,@diskon /*invdiscper*/
				,@invdiscamt /*invdiscamt*/
				,''0'' /*invmisc*/
				,@subtotal_nodisc  /*invsubtot*/
				,@totalorder  /*invnet*/
				,''0'' /*invetaxtot*/
				,@totalorder /*invnetwtx*/
				,@totalorder  /*invamtude*/
				,@inhomecurr
				,''SP'' /*inratetype*/
				,@insourcurr
				,@inratedate
				,''1'' /*inrate*/
				,''0'' /*inspread*/
				,''3'' /*indatemtch*/
				,''1'' /*inraterep*/
				,''0'' /*inrateover*/
				,@ordersourc
				,'''' /*csisusr*/
				,@CUSACCTSET /*CUSACCTSET*/
				
			-- insert di OETERMO
			/*insert into SGTDAT..OETERMO(
				ORDUNIQ,
				PAYMENT,
				AUDTDATE,
				AUDTTIME,
				AUDTUSER,
				AUDTORG,
				DISCBASE,
				DISCDATE,
				DISCPER,
				DISCAMT,
				DUEBASE,
				DUEDATE,
				DUEPER,
				DUEAMT
			)values(
				@orduniq,
				32,
				@audtdate,
				@audttime,
				@audtuser,
				@audtorg,
				@totalorder,
				@audtdate,
				@diskon,
				0,
				@totalorder,
				mesdb.dbo.fnSetAccpacDate(convert(varchar(12),dateadd(day, 30, getdate()),101)),
				100,
				@totalorder				
			)*/
			
--print ''[uspApvOrderD] ''+convert(varchar, @orderid)+'',''+convert(varchar(50), @orduniq)+'',''+convert(varchar(10), @audtuser)+'',''+convert(varchar(100), @totalorder)+'',''+convert(varchar(100), @subtotalorder)
			exec [DM_uspApvOrderD_SGTDAT] @orderid,@orduniq,@audtuser,@totalorder,@subtotalorder, @sta 
			--exec [uspApvOrderD_SGTDAT] @orderid,@orduniq,@audtuser,@tempOrder,@subtotalorder 
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
	--end
	--update approval
	--	update [order] set
	--	[status] = @sta
	--	,approval=1
	--	,tanggal_approval=GETDATE()
	--	where order_id=@orderid
	
IF @@ERROR <> 0
	BEGIN
		ROLLBACK
		RAISERROR (''ERROR'', 16, 1)
		RETURN
	END
COMMIT

--select * from [order]
--begin tran
--exec [uspApvOrderH] ''ORD-00001'',''ADM''
--rollback tran
--commit tran

' 
END
GO
