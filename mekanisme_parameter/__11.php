/* ITEM PERDANA */
select case when not exists
	(
		select 1 from order_item a, [order] b where a.order_id = b.order_id and a.user_id = b.user_id and
		a.order_id <> '#order_id#' and a.user_id = '#user_id#'  and b.dealer_id = (select dealer_id from [order] where order_id = '#order_id#')
		and a.item_id in (select item from paket_item where paketid = '#paketid#') 
				and a.paketid = '#paketid#' and a.kuantitas > 0
	)
	then sum(kuantitas) 
	else 0 end paket_perdana
from
(
	select sum(kuantitas) kuantitas
	from order_item a 
	where 
	a.paketid = '#paketid#' and a.order_id = '#order_id#' and a.user_id = '#user_id#'  and a.item_id not in 
		(
			select a.item_id from order_item a, [order] b where 
				a.order_id = b.order_id and a.user_id = b.user_id 
				and b.dealer_id = (select dealer_id from [order] where order_id = '#order_id#')
				and a.item_id in (select item from paket_item where paketid = '#paketid#') 
				/*and a.paketid <> '#paketid#' *//* mencari paket apakah pernah diambil */
				and b.order_id <> '#order_id#' /* mencari item apakah pernah dibeli, walopun tanpa paket */
				and a.kuantitas > 0
		)
	/*
	union
	select sum(kuantitas) 
	from order_item a, [order] b where 
		a.order_id = b.order_id and a.user_id = b.user_id and
		a.order_id <> '#order_id#' and a.user_id = '#user_id#'  and b.dealer_id = (select dealer_id from [order] where order_id = '#order_id#')
	*/
) a
