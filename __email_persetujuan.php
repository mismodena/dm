<?
set_time_limit(60);

$_SESSION["sales_id"] = "ADMIN";

include "lib/mainclass.php";

error_reporting(E_ALL);
/* 
untuk pengiriman email OP / email utk info pengajuan diskon tambahan ke BM
variabel GET dibutuhkan :
order_id : sudah jelas
email : email dari pejabat pemberi persetujuan
*/

$sql = "select a.order_id, a.dealer_id idcust, b.kode_sales, d.email, c.namecust from [order] a, [user] b, sgtdat.dbo.arcus c, [user] d
		where a.user_id = b.user_id and a.dealer_id = c.idcust and b.bm = d.kode_sales
		and a.order_id = '". main::formatting_query_string($_REQUEST["order_id"]) ."';";
$rs_data_dealer = sql::execute( $sql );
$data_dealer = sqlsrv_fetch_array( $rs_data_dealer ) or die("gagal dapetin data dealer!");

$sql = "select * from [user] where email = '". main::formatting_query_string($_REQUEST["email"]) ."' ";
$rs_data_user = sql::execute( $sql );
$data_user = sqlsrv_fetch_array( $rs_data_user ) or die ("gagal dapetin data user persetujuan!");

tambahan_diskon_persetujuan::kirim_email_persetujuan( $data_dealer["idcust"], $data_dealer["namecust"], $data_dealer["order_id"], $data_user["email"], $data_user["nik"] );		

echo "<h1>RAMPUNG!!!</h1>";

?>