<?
include "../lib/var.php";
include "../lib/cls_main.php";
include "../lib/sql.php";

$sql = "
select 
convert(varchar, MONTH(a.tanggal))+'/'+convert(varchar, YEAR(a.tanggal)) periode, e.cabang, 
'<a href=\"http://air.modena.co.id/dm/paket-detail-simulasi.php?paketid='+c.paketid+'\" target=\"_blank\">'+c.paketid+'</a>' paketid,
count(a.order_id)  jumlah_order
from [order] a inner join [order_item] b on a.order_id = b.order_id and a.user_id = b.user_id
left outer join paket c on b.paketid = c.paketid 
inner join [user] d on a.user_id = d.user_id inner join [user] e on d.bm = e.kode_sales
where a.kirim = 1  and isnull(c.paketid, '') <>'' 
group by convert(varchar, MONTH(a.tanggal))+'/'+convert(varchar, YEAR(a.tanggal)), e.cabang, c.paketid 
order by convert(varchar, MONTH(a.tanggal))+'/'+convert(varchar, YEAR(a.tanggal)), COUNT(a.order_id) desc
";
$rs = sql::execute($sql);
echo "<table border=1 width=\"100%\">";
while( $data = sqlsrv_fetch_array( $rs ) ){
	echo "<tr>
		<td>". $data["periode"] ."</td>
		<td>". $data["cabang"] ."</td>
		<td>". $data["paketid"] ."</td>
		<td>". $data["jumlah_order"] ."</td>
		
	</tr>";
}
echo "</table>";
?>