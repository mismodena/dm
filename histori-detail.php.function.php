<?

if( @$_REQUEST["order_id"] == "" ){
	echo "<script>location.href='histori.php';</script>";
	exit;
}

// load dealer
$_POST["sc"] = "cl";
include_once "dealer.php";

$rs_dealer = sql::execute( $sql . " /*and c.user_id = '".  $_SESSION["sales_id"]."'*/ and c.order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."' " );
$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("Gagal mendapatkan data dealer!");

$link_kembali = "<a href=\"histori.php\" style=\"color:blue\">Kembali ke daftar penjualan</a>";
$tampilan_tabel = false;
if( @$_POST["pengajuan_diskon"] == 1 ){
	$link_kembali = "<a href=\"diskon-pengajuan.php?dealer_id=". $data_dealer["idcust"] ."&order_id=". $data_dealer["order_id"] ."\" style=\"color:blue\">Kembali ke pengajuan tambahan diskon</a>";
	$tampilan_tabel = true;
}
if( @$_POST["persetujuan_diskon"] == 1 ){
	$link_kembali = "<a href=\"diskon-persetujuan.php?dealer=". $data_dealer["idcust"] ."&order_id=". $data_dealer["order_id"] ."\" style=\"color:blue\">Kembali ke pengajuan tambahan diskon</a>";
	$tampilan_tabel = true;
}

$data_dealer["order_id"] = $_REQUEST["order_id"];
$kelas_order = "tambahan_diskon_persetujuan";
if( @$_REQUEST["order_id_split"] != "" )	{
	$data_dealer["order_id_split"] = $_REQUEST["order_id_split"];
	$kelas_order = "tambahan_diskon_persetujuan_split";
}
$daftar_order_and_rasio = $kelas_order::isi_detail_order( $data_dealer, $tampilan_tabel ? "xxxx" : "" );

$daftar_order = $daftar_order_and_rasio[0] ;


?>