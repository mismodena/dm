<?
// load dealer
$_POST["sc"] = "cl";
$arr_diskon_display_tidak_perlu_persetujuan = array(7,36);

include_once "dealer.php";
$rs_dealer = sql::execute( $sql );
$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("Gagal mendapatkan data dealer!");

//if( $data_dealer["order_id"] == "" ||  $data_dealer["kode_sales"] != $_SESSION["sales_kode"] )
//	$data_dealer["order_id"] = order::orderid( $data_dealer["idcust"], 0 /*$data_dealer["disc"]*/ );

// cek data diskon
$rs_diskon = tambahan_diskon::daftar_tambahan_diskon( array("b.diskon_id" => array("=", "'". main::formatting_query_string( $_REQUEST["diskonid"] ) ."'") ), $_REQUEST["order_id"], true );
$data_diskon = sqlsrv_fetch_array( $rs_diskon );

include_once $page . ".command.php";

$order_id = $_REQUEST["order_id"];
$dm = new order( $order_id, $_REQUEST["diskonid"] );
include_once "transaksi-2-order.php";

?>