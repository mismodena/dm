<?
set_time_limit(60);

$_SESSION["sales_id"] = "ADMIN";

include "lib/mainclass.php";

error_reporting(E_ALL);
/* 
untuk pengiriman email OP / email utk info pengajuan diskon tambahan ke BM
variabel GET dibutuhkan :
order_id : sudah jelas
*/

$sql = "select a.order_id, a.dealer_id idcust, b.kode_sales, d.email, c.namecust from [order] a, [user] b, sgtdat.dbo.arcus c, [user] d
		where a.user_id = b.user_id and a.dealer_id = c.idcust and b.bm = d.kode_sales
		and a.order_id = '". main::formatting_query_string($_REQUEST["order_id"]) ."';";
$rs_data_dealer = sql::execute( $sql );
$data_dealer = sqlsrv_fetch_array( $rs_data_dealer ) or die("gagal dapetin data dealer!");

$_POST["sc"] = "cl";
$_POST["kode_sales"] = $data_dealer["kode_sales"];

include "dealer.php";

$arr_set["kirim"] = array("=", "'0'");
$arr_set["pengajuan_diskon"] = array("=", "'1'");
$arr_set["tanggal"] = array("=", "getdate()");
$arr_parameter["order_id"] = array("=", "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'");
sql_dm::update_order( $arr_set, $arr_parameter );

tambahan_diskon_persetujuan::kirim_email_persetujuan( $data_dealer["idcust"], $data_dealer["namecust"], $data_dealer["order_id"], $data_dealer["email"], "" );

echo "<h1>RAMPUNG!!!</h1>";

?>