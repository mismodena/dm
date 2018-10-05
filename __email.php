<?
set_time_limit(60);

//$_SESSION["sales_id"] = "ADMIN";

include "lib/mainclass.php";
$_SESSION["sales_id"] = "ADMIN";

error_reporting(E_ALL);
/* 
untuk pengiriman email OP / email utk info order masuk ke ACCPAC 
variabel GET dibutuhkan :
order_id : sudah jelas
[kirim_accpac] : opsional, text bebas.. asal tidak kosong saja... 
*/

$sql = "select a.order_id, a.dealer_id idcust, b.kode_sales from [order] a, [user] b where a.user_id = b.user_id and a.order_id = '". main::formatting_query_string($_REQUEST["order_id"]) ."';";
//echo $sql;
$rs_data_dealer = sql::execute( $sql );
$data_dealer = sqlsrv_fetch_array( $rs_data_dealer ) or die("gagal dapetin data dealer!");

$_POST["sc"] = "cl";
$_POST["kode_sales"] = $data_dealer["kode_sales"];

include "dealer.php";

$nominal_order = order::nominal_order( $data_dealer["idcust"], array( "b.order_id" => array("=",  "'". $data_dealer["order_id"] ."'" ) ) );

$status_order = 0;
$overlimit = order::check_overlimit( $data_dealer["idcust"], $nominal_order["nominal_order_net"] ) ;
if( $overlimit["is_overlimit"] ) $status_order = 1;

// cek order split
$sql = "select 1 splitted from [order_split] where order_id = '". main::formatting_query_string($_REQUEST["order_id"]) ."';";
$sql = "select * from dbo.[ufn_order_split]('". main::formatting_query_string( $_REQUEST["order_id"] ) ."')";
$rs_cek_split = sql::execute( $sql );

if( @$_REQUEST["kirim_accpac"] != "" )
	$order_split = order::kirim_data_ke_accpac( $data_dealer, $nominal_order, $status_order );

$nodiskon = "NODISKON";
if( sqlsrv_num_rows( sql::execute("select 1 from order_diskon where order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."'") ) > 0 )
	$nodiskon = "";

if( @$order_split !== false && sqlsrv_num_rows( $rs_cek_split ) <= 0 )	tambahan_diskon_persetujuan::kirim_email_tanggapan( $data_dealer["idcust"], $data_dealer["order_id"], "", $nodiskon );
else{

	$rs_order_split = order::daftar_order_original_split( array("a.order_id" => array("=", "'". main::formatting_query_string($data_dealer["order_id"]) ."'") ) );
	while( $data_order_split = sqlsrv_fetch_array( $rs_order_split ) ){
		tambahan_diskon_persetujuan_split::kirim_email_tanggapan_split( $data_dealer["idcust"], $data_dealer["order_id"], $data_order_split["order_id_split"], $data_order_split["gudang"], "", $nodiskon );	
	}
}

// auto ppok jika overlimit --
include "auto_ppok.php";

echo "<h1>Selesai!!!</h1>";

if( isset( $_REQUEST["auto_close"] ) ) echo "<script>window.close()</script>";  

?>