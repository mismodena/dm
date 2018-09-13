/*
select sum(kuantitas) from(
	/* ITEM SINGLE */
	select 
		sum(a.kuantitas) kuantitas
		from order_item a, [order] b, paket_item c where 
		a.order_id = b.order_id and a.user_id = b.user_id and a.item_id = c.item and a.paketid = c.paketid and c.mode = '1' and a.harga > 0 and
		c.paketid = '#paketid#' and a.order_id = '#order_id#' and a.user_id = '#user_id#' 
	union
	/* ITEM DALAM SUB KATEGORI */
	select
		sum(a.kuantitas) kuantitas
		from order_item a inner join [order] b on a.order_id = b.order_id and a.user_id = b.user_id and a.harga > 0
		inner join sub_kategori c on substring(a.item_id, 1, 2) in (select * from dbo.[ufn_split_string](c.kode_prefiks, ','))
		inner join paket_item d on convert(varchar, c.sub_kategoriid) = d.item and a.paketid = d.paketid
		where 
		d.paketid = '#paketid#' and a.order_id = '#order_id#' and a.user_id = '#user_id#' 
) a

=========================================================
*/
select 
sum(a.kuantitas) 
from order_item a inner join [order] b on a.order_id = b.order_id and a.user_id = b.user_id 
left outer join paket_item d on 
a.item_id = d.item and a.paketid = d.paketid 
left outer join (select * from paket_item x left outer join sub_kategori y on x.item = convert(varchar, y.sub_kategoriid) and x.mode = 2 ) c on
	SUBSTRING(a.item_id, 1, 2) in (select * from dbo.ufn_split_string(c.kode_prefiks, ',')) and a.paketid = c.paketid 
where 
a.paketid = '#paketid#' and a.order_id = '#order_id#' and a.user_id = '#user_id#' 	
