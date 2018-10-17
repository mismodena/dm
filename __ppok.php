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

$sql = "select a.order_id, a.dealer_id idcust, b.kode_sales, b.email, b.nama_lengkap, a.keterangan_order, c.email email_bm, ar.idgrp, ar.codeterr 
		from [order] a, [user] b, [user] c, sgtdat.dbo.ARCUS ar
		where a.user_id = b.user_id AND b.bm = c.kode_sales AND a.dealer_id = ar.IDCUST and a.order_id = '". main::formatting_query_string($_REQUEST["order_id"]) ."';";
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
$rs_cek_split = sql::execute( $sql );

// auto ppok jika overlimit --
include "auto_ppok.php";

echo "<h1>Selesai!!!</h1>";

?>