<?

unset( $_SESSION["disc_dealer"] );

if( @$_SESSION["kode_dealer"] == "" && @$_REQUEST["dealer"] == "" )
	die("<script>location.href='transaksi.php'</script>");

// load dealer
$_POST["sc"] = "cl";
$_POST["kode_sales"] = $_SESSION["sales_kode"];
if (@$_REQUEST["dealer"] != ""){
	if( $_SESSION["kode_dealer"] != @$_REQUEST["dealer"] )	{
		$_SESSION["t_po"] = "";
		$_SESSION["alamat_kirim"] = array();
	}
	$_SESSION["kode_dealer"] = @$_REQUEST["dealer"];
}
$_POST["kode_dealer"] = $_SESSION["kode_dealer"];
$_POST["pengajuan_diskon"] = 0;

include "dealer.php";

$rs_dealer = sql::execute( $sql . " and e.user_id = '". main::formatting_query_string($_SESSION["sales_id"]) ."' " );

if( sqlsrv_num_rows( $rs_dealer ) <= 0 ){
	order::orderid( $_SESSION["kode_dealer"], 0, true );
	$rs_dealer = sql::execute( $sql . " and e.user_id = '". main::formatting_query_string($_SESSION["sales_id"]) ."' " );
}

$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("<script>alert('Gagal mendapatkan data dealer!');location.href='transaksi.php';</script>");

// cek order
if( $data_dealer["order_id"] == "" ||  $data_dealer["user_id"] != $_SESSION["sales_id"] )
	$data_dealer["order_id"] = order::orderid( $data_dealer["idcust"], 0, true );

$_SESSION["order_id"] = $data_dealer["order_id"];

if( @$_REQUEST["dealer"] =="" &&  @$_SESSION["kode_dealer"] == "")
	die("<script>alert('Gagal mendapatkan data dealer!');location.href='transaksi.php';");

if( @$_REQUEST["c"] == "" ) goto SkipCommand;

include "transaksi-2.php.command.php";

SkipCommand:

if( $data_dealer["user_id_bm"] == "" ) $style = "<style>#r_cek_2, #label_r_cek_2{display:none}</style>";

// load data shipment default
$sql = "select NAMELOCN, ltrim(rtrim(TEXTSTRE1))+' '+ltrim(rtrim(TEXTSTRE2))+' '+ltrim(rtrim(TEXTSTRE3))+' '+ltrim(rtrim(TEXTSTRE4)) alamat, NAMECITY, CODESTTE, TEXTPHON1 telpon, TEXTPHON2 hp 
				from ". $database_accpac ."..arcsp where IDCUST = '". main::formatting_query_string( $_SESSION["kode_dealer"] ) ."'";
$rs_alamat_pengiriman_default = sql::execute( $sql );
if( sqlsrv_num_rows( $rs_alamat_pengiriman_default ) <= 0 ){
	$sql = "select NAMECUST NAMELOCN, ltrim(rtrim(TEXTSTRE1))+' '+ltrim(rtrim(TEXTSTRE2))+' '+ltrim(rtrim(TEXTSTRE3)) alamat, NAMECITY, CODESTTE, TEXTPHON1 telpon, TEXTPHON2 hp 
				from ". $database_accpac ."..arcus where IDCUST = '". main::formatting_query_string( $_SESSION["kode_dealer"] ) ."'";
	$rs_alamat_pengiriman_default = sql::execute( $sql );
}
$alamat_pengiriman_default = sqlsrv_fetch_array( $rs_alamat_pengiriman_default );
$arr_alamat_pengiriman = array(
				"t_nama_konsumen"=> @$_SESSION["alamat_kirim"]["t_nama_konsumen"] != "" ? $_SESSION["alamat_kirim"]["t_nama_konsumen"] : $alamat_pengiriman_default["NAMELOCN"], 
				"t_alamat_konsumen"=> @$_SESSION["alamat_kirim"]["t_alamat_konsumen"] != "" ? $_SESSION["alamat_kirim"]["t_alamat_konsumen"] : $alamat_pengiriman_default["alamat"], 
				"t_kota_konsumen"=> @$_SESSION["alamat_kirim"]["t_kota_konsumen"] != "" ? $_SESSION["alamat_kirim"]["t_kota_konsumen"] : $alamat_pengiriman_default["NAMECITY"], 
				"t_propinsi_konsumen"=> @$_SESSION["alamat_kirim"]["t_propinsi_konsumen"] != "" ? $_SESSION["alamat_kirim"]["t_propinsi_konsumen"] : $alamat_pengiriman_default["CODESTTE"], 
				"t_telepon_konsumen"=> @$_SESSION["alamat_kirim"]["t_telepon_konsumen"] != "" ? $_SESSION["alamat_kirim"]["t_telepon_konsumen"] : $alamat_pengiriman_default["telpon"], 
				"t_hp_konsumen"=> @$_SESSION["alamat_kirim"]["t_hp_konsumen"] != "" ? $_SESSION["alamat_kirim"]["t_hp_konsumen"] : $alamat_pengiriman_default["hp"]
			);
foreach( $arr_alamat_pengiriman as $isian => $data )
	if( trim( @$_REQUEST[ $isian ] ) == "" )	$_REQUEST[ $isian ] = trim( $data );

$order_id = $_SESSION["order_id"];
$dm = new order( $order_id );

include_once "transaksi-2-order.php";

if( @$_SESSION["t_po"]  != "" ){
	include_once "transaksi-2.php.po-cek.php";
	$_REQUEST["t_po"] = trim( $_SESSION["t_po"] );
	$data_po = cek_po();
}

// cek data dealer modern, paksa isi nomor PO
$script_eksekusi = "lanjut_proses()";
if( in_array( trim( $data_dealer["idgrp"] ), explode(",", str_replace("'", "", $arr_dealer_modern) ) )  ) {
	$script_eksekusi = "lanjut_proses_modern()";
	$script_tambahan = "
		try{
			var t_po = document.getElementById('t_po');
			if(t_po.value == ''){
				alert('Dealer modern, mohon isikan nomor PO!');
				t_po.focus();
			}
		}catch(e){}
		";
}


?>