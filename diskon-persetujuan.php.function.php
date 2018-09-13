<?

// load dealer
$_POST["sc"] = "cl";
$_POST["pengajuan_diskon"] = 1;

include_once "dealer.php";
$rs_dealer = sql::execute( $sql . " and c.order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."' " );
$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("<script>location.href='diskon.php'</script>");

$data_dealer["order_id"] = $_REQUEST["order_id"];

// cek order dealer yg blm dikirimkan
$nominal_order = array("nominal_order" => 0);
if( $data_dealer["order_id"] != "" )
	$nominal_order = order::nominal_order( $data_dealer["idcust"], array( "b.order_id" => array("=",  "'". $data_dealer["order_id"] ."'" ), "b.kirim" => array("=",  "'0'" ) ) );
else die("<script>alert('Mohon lakukan drafting order produk untuk dealer tersebut terlebih dahulu!');location.href='transaksi.php'</script>");

//$nominal_order_setelah_diskon = $nominal_order["nominal_order"];

// daftar diskon
$rs_daftar_diskon = tambahan_diskon::daftar_tambahan_diskon( array( "b.order_id" => array( "=", "'". $data_dealer["order_id"] ."'" ) ), $data_dealer["order_id"] );

list( $data_persetujuan_diskon, $nominal_order_setelah_diskon ) = 
	tambahan_diskon_persetujuan::isi_email_persetujuan( $data_dealer["idcust"], $data_dealer["order_id"], $nominal_order, $rs_daftar_diskon, $_SESSION["sales_nik"] );

$data_persetujuan_diskon = str_replace( "persetujuan_diskon", "persetujuan_diskon&sc=" . sha1( rand( 0, 1000 ) ), $data_persetujuan_diskon );

?>