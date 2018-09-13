<?
set_time_limit(60);

if( @$_SESSION["sales_id"] == "" )	$_SESSION["sales_id"] = "ADMIN";

include "lib/mainclass.php";

$_POST["sc"] = "cl";
$_REQUEST["order_id"] = $_REQUEST["order_id"];

include __DIR__ . "/dealer.php";

$rs_dealer = sql::execute( $sql );
$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die( "<script>alert('Gagal mendapatkan info dealer. [fungsi : kirim email persetujuan]')</script>");

$detail_order =  tambahan_diskon_persetujuan::isi_detail_order( $data_dealer, "xxxx" );

echo "<h3>Keterangan:</h3>"
	. $data_dealer["keterangan_order"]
	. "<hr />"
	. $detail_order[0]
	;

?>