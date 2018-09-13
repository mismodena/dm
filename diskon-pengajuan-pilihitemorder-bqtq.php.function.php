<?

if( file_exists("mekanisme_prosedur_diskon/". $_REQUEST["diskonid"] .".php") )
	include_once  "mekanisme_prosedur_diskon/". $_REQUEST["diskonid"] .".php";
else
	include_once "lib/cls_prosedur_khusus_tambahan_diskon.php";

$diskon_belum_dialokasikan = false;

// load dealer
$_POST["sc"] = "cl";

include_once "dealer.php";
$rs_dealer = sql::execute( $sql );
$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("Gagal mendapatkan data dealer!");

//if( $data_dealer["order_id"] == "" ||  $data_dealer["kode_sales"] != $_SESSION["sales_kode"] )
//	$data_dealer["order_id"] = order::orderid( $data_dealer["idcust"], 0 /*$data_dealer["disc"]*/ );

$data_dealer["order_id"] = $_REQUEST["order_id"];

// cek data diskon
$rs_diskon = tambahan_diskon::daftar_tambahan_diskon( array("b.diskon_id" => array("=", "'". main::formatting_query_string( $_REQUEST["diskonid"] ) ."'") ), $_REQUEST["order_id"], true );
$data_diskon = sqlsrv_fetch_array( $rs_diskon );

// loading object diskon
unset( $arr_parameter );
$arr_parameter["a3.dealer_id"] = array( "=", "'" . main::formatting_query_string( $data_dealer["idcust"] ) . "'" );				
$arr_parameter["a.order_id"] = array( "=", "'" . main::formatting_query_string( $data_dealer["order_id"] ) . "'" );				
$mekanisme_prosedur_diskon = "mekanisme_prosedur_diskon_" . $_REQUEST["diskonid"];
$obyek_diskon = ( new $mekanisme_prosedur_diskon($arr_parameter, $readonly) );

// saldo budget tersisa
$nominal_order = order::nominal_order( $data_dealer["idcust"], array( "b.order_id" => array("=",  "'". $data_dealer["order_id"] ."'" ), "b.kirim" => array("=",  "'0'" ) ) );
$nominal_order_setelah_diskon = $nominal_order["nominal_order"];
$arr_budget_diskon_tersedia_terkait = array();
$rs_daftar_diskon = tambahan_diskon::daftar_tambahan_diskon( array( "b.order_id" => array( "=", "'". $data_dealer["order_id"] ."'" ) ), $data_dealer["order_id"] );
while( $diskon = sqlsrv_fetch_array( $rs_daftar_diskon ) ){		

	$arr_content = tambahan_diskon_persetujuan::detail_order_diskon_single($counter, $diskon,  $data_dealer["idcust"], $data_dealer["order_id"], $nominal_order, $rs_daftar_diskon, "", $readonly, $item_stok , $arr_budget_diskon_tersedia_terkait, $nominal_order_setelah_diskon);
	if( $diskon["diskon_id"] == $_REQUEST["diskonid"] ){
		$saldo_budget[ $obyek_diskon->prefiks_identifikasi_bqtq() . "Avail" ] = $arr_content["saldo_tersedia_awal"];

	}
	$arr_budget_diskon_tersedia_terkait = $arr_content["arr_budget_diskon_tersedia_terkait"];
	$nominal_order_setelah_diskon = $arr_content["nominal_order_setelah_diskon"];
	$counter++;
}

$arr_tambahan_diskon_share = $obyek_diskon->arr_diskon_id_sebudget(); 

unset( $arr_parameter );
$arr_parameter["a1.order_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'");
$arr_parameter["a1.diskon_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["diskonid"] ) ."'");
$arr_parameter["a0.kuantitas"] = array("=", "a.kuantitas_bqtq");
$pemakaian_saldo = @sqlsrv_fetch_array( prosedur_khusus_tambahan_diskon::daftar_order_diskon_bqtq( "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'", $arr_parameter, "sum(diskon_bqtq) total_nilai_diskon") );

$item_order_nominal = $pemakaian_saldo["total_nilai_diskon"];
$item_order_nominal_formatted = main::number_format_dec( $item_order_nominal ) . " (-) (Pemotongan Saldo BQ Rp" .  main::number_format_dec( $item_order_nominal / $obyek_diskon->persentase_budget_bisa_digunakan() ) . ")" ;

// saldo budget tersedia
foreach( $arr_tambahan_diskon_share as $diskon_id ){	
	$saldo_tersedia[ $diskon_id ] = ( $saldo_budget[ $obyek_diskon->prefiks_identifikasi_bqtq() . "Avail" ] * $obyek_diskon->persentase_budget_bisa_digunakan() )+ $item_order_nominal;
	$saldo_tersedia_formatted[ $diskon_id ] = main::number_format_dec( $saldo_tersedia[ $diskon_id ] );
	$saldo_awal[ $diskon_id ] = $saldo_tersedia[ $diskon_id ];
	$saldo_awal_formatted[ $diskon_id ] = $saldo_tersedia_formatted[ $diskon_id ];
}


if( $saldo_tersedia[ $_REQUEST["diskonid"] ]  < 0 ) $warning = true;

// load command	
include_once "diskon-pengajuan-pilihitemorder-bqtq.php.command.php";

$order_id = $_REQUEST["order_id"];
$dm = new order( $order_id, $_REQUEST["diskonid"] );
include_once "transaksi-2-order.php";

?>