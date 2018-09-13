<?

// load dealer
$_POST["sc"] = "cl";
$_POST["pengajuan_diskon"] = 1;

include_once "dealer.php";
$rs_dealer = sql::execute( $sql . " and c.order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."' " );
$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("<script>location.href='diskon.php'</script>");

$daftar_order_and_rasio = tambahan_diskon_persetujuan::isi_detail_order($data_dealer, "xxxx");

$daftar_order = $daftar_order_and_rasio[0] ;
$rasio_campaign_non_campaign = $daftar_order_and_rasio[1] ;

?>