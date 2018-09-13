/*
select * from OEORDH where ORDNUMBER = 'MZF-XXX4'
select * from OEORDH where ORDNUMBER = 'M-1116-26054'
OEORDH

total order net : @totalorder
termttldue
25751699.000

total order gross (belum termasuk diskon) :
ordtotal
28072875.000

tbase1 :
total order net / 1.1
dihilangkan semua desimalnya tanpa pembulatan
23410635.000

tiamount1 :
tbase1 / 10 
dihilangkan semua desimalnya dengan pembulatan ke atas
2341064.000

invnetnotx :
tbase1
23410635.000

invitaxtot :
tiamount1
2341064.000

invitmtot :
ordtotal
28072875.000

invdiscbas : @subtotalorder
total harga net per item termasuk diskon per item (campaign) .. belum termasuk diskon tambahan dalam satu faktur
27501699.000

invdiscper :
persentase diskon tambahan dalam satu faktur
6.36324

invdiscamt :
nominal diskon tambahan dalam satu faktur
1750000.000

invsubtot :
ordtotal
28072875.000

invnet :
termttldue
25751699.000

invnetwtx :
termttldue
25751699.000

invamtdue :
termttldue
25751699.000

========================================================================================

select * from OEORDD where ORDUNIQ=20453763
--select * from OEORDD where ORDUNIQ=20453765
select * from OEORDD where ORDUNIQ=20453768
select * from OEORDD where ORDUNIQ=20453769
select * from OEORDD where ORDUNIQ=20453770
OEORDD 

unitprice :
harga net per item (PL dikurangi diskon dealer)
7993625.000000

unitcost :
select @unitcost = case when TOTALCOST/QTYONHAND>0 then TOTALCOST/QTYONHAND else RECENTCOST  end, @recentcost = RECENTCOST * from 
		[SGTDAT]..iciloc where ITEMNO='BH0725L0413S13' and LOCATION='GDGBDG' and totalcost>0		
3724717.000000

priuntprc :
unitprice per item
7993625.000000

pribasprc :
PL per item
12111553.000000

cosuntcst :
unitcost
3724717.000000

extocost :
unitcost * qtyordered
11174151.000

extinvmisc :
unitprice * qtyordered
23980875.000

invdisc :
nominal diskon item (sub total diskon per baris item)
425399.000

tbase1 :
-. loop pertama dst
oeordh.tbase1 * ( (unitprice * qtyordered) - invdisc ) / sum( ( (unitprice * qtyordered) - invdisc ) ), hasilnya round tanpa desimal
-. loop terakhir
oeordh.tbase1 - sum( oeordd.tbase1 )
20051440.000

tamount1 :
-. loop pertama dst
oeordd.tbase1 / 10, hasilnya round tanpa desimal
-. loop terakhir
oeordh.tiamount1 - sum( oeordd.tamount1 )

2005144.000

discper :
persentase diskon item (sub total diskon per baris item)
1.77391

hdrdisc :
oeordh.invdiscamt * ( (unitprice * qtyordered) - invdisc ) / sum( ( (unitprice * qtyordered) - invdisc ) )
*/

update ICILOC set QTYONHAND=100 where ITEMNO='FC7942S0210B01' and LOCATION='GDGPST'
select case when convert(int, qtyonhand+qtyadnocst+qtyrenocst-qtyshnocst-qtycommit)-3 < 0 then 0 else 1 end qty_available from iciloc where itemno='FC7942S0210B01' and location='GDGPST'

select top 10 * from OEORDH order by ORDDATE desc
select * from mobilesales_dev..[order] where order_id='M-1116-26054'
select * from mobilesales_dev..[order_item] where order_id='M-1116-26054'

select 
	COUNT(1), 0	, sum( ( (a.harga* a.kuantitas) - a.diskon) )  total_order_tanpa_diskon
	from order_item a inner join TRDAT.dbo.icitem b on a.item_id=b.itemno 
	inner join [order] c on a.order_id=c.order_id
	inner join [user] d on c.user_id=d.user_id
	inner join TRDAT.dbo.arcus e on c.dealer_id=e.idcust
	inner join TRDAT.dbo.OEORDH f on c.order_id = f.ORDNUMBER
	where a.order_id='M-1116-26054'

select 
	--b.fmtitemno as item,
	f.tbase1 * ( (a.harga * a.kuantitas) - a.diskon) / sum( ( (a.harga* a.kuantitas) - a.diskon) ) tbase
	/*	mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),101)) as audtdate
		,mesdb.dbo.fnSetAccpacTime(convert(varchar(12),getdate(),108)) as audttime
		,'TRDAT' as audtorg
		,1 as linetype
		,b.itemno
		,b.fmtitemno as item
		,b.[desc] as itemdesc
		,d.cabang as location
		,mesdb.dbo.fnSetAccpacDate(convert(varchar(12),getdate(),106)) as expdate
		,a.kuantitas as qtyordered
		,a.diskon
		,a.harga
		,e.priclist
		,e.custtype
		,c.diskon diskon_per_invoice
		,a.kuantitas * a.harga harga_total*/
	from order_item a inner join TRDAT.dbo.icitem b on a.item_id=b.itemno 
	inner join [order] c on a.order_id=c.order_id
	inner join [user] d on c.user_id=d.user_id
	inner join TRDAT.dbo.arcus e on c.dealer_id=e.idcust
	inner join TRDAT.dbo.OEORDH f on c.order_id = f.ORDNUMBER
	where a.order_id='M-1116-26054' and b.ITEMNO = 'FC7942S0210B01' group by f.TBASE1
	
		delete from trdat..OEORDH where ordnumber = 'IM/MS-1216-00005'
	delete from trdat..OEORDH1 where orduniq = 20453782
	delete from trdat..OEORDD where orduniq = 20453782
	delete from trdat..OEORDHO  where orduniq = 20453782
	
	select TBASE1, * from trdat..OEORDH where ordnumber = 'IM/MS-1216-00005'
	select TBASE1, * from trdat..OEORDD where orduniq = 20453782
	
	exec dbo.DM_uspApvOrderH_Post_trdat 'IM/MS-1216-00005', 'ADMIN', 1, 31624016, 32105600, 34848000;