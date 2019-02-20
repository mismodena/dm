<?

if( file_exists("mekanisme_prosedur_diskon/". $_REQUEST["diskonid"] .".php") )
	include_once  "mekanisme_prosedur_diskon/". $_REQUEST["diskonid"] .".php";
else
	include_once "lib/cls_prosedur_khusus_tambahan_diskon.php";

$diskon_belum_dialokasikan = false;

// load dealer
$_POST["sc"] = "cl";
$_SESSION["order_id"] = $_REQUEST["order_id"];

include_once "dealer.php";
$rs_dealer = sql::execute( $sql );
$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("Gagal mendapatkan data dealer!");


// cek data diskon
$rs_diskon = tambahan_diskon::daftar_tambahan_diskon( array("b.diskon_id" => array("=", "'". main::formatting_query_string( $_REQUEST["diskonid"] ) ."'") ) );
$data_diskon = sqlsrv_fetch_array( $rs_diskon );

// cek data order per gudang
$arr_order_gudang = array();
$rs_data_order_gudang = order::daftar_order_item( $data_dealer["order_id"] );
while( $data_order_gudang = sqlsrv_fetch_array( $rs_data_order_gudang ) )
	@$arr_order_gudang[ $data_order_gudang["gudang"] ] += $data_order_gudang["sub_total"];

// loading object diskon
unset( $arr_parameter );
$arr_parameter["a3.dealer_id"] = array( "=", "'" . main::formatting_query_string( $data_dealer["idcust"] ) . "'" );				
$arr_parameter["a.order_id"] = array( "=", "'" . main::formatting_query_string( $data_dealer["order_id"] ) . "'" );				
$mekanisme_prosedur_diskon = "mekanisme_prosedur_diskon_" . $_REQUEST["diskonid"];
$obyek_diskon = ( new $mekanisme_prosedur_diskon($arr_parameter, $readonly) );


// kombinasi budget diskon
$display_diskon_share = "none";
$alternatif_kombinasi_budget_diskon = "";
if( count( $obyek_diskon->arr_diskon_id_share_budget() ) > 1 ) {
	
	foreach( $obyek_diskon->arr_diskon_id_share_budget() as $diskon_id ){
				
		if( $diskon_id == $_REQUEST["diskonid"] ) continue;
		
		$display_diskon_share = "block";
				
		$file_mekanisme_diskon = "mekanisme_prosedur_diskon/". $diskon_id .".php";
		if( file_exists( $file_mekanisme_diskon ) ){
			include_once $file_mekanisme_diskon;
			unset( $arr_parameter );
			$arr_parameter["a3.dealer_id"] = array( "=", "'" . main::formatting_query_string( $data_dealer["idcust"] ) . "'" );				
			$arr_parameter["a.order_id"] = array( "=", "'" . main::formatting_query_string( $data_dealer["order_id"] ) . "'" );				
			$mekanisme_prosedur_diskon = "mekanisme_prosedur_diskon_" . $diskon_id;
			$obyek_diskon_kombinasi = ( new $mekanisme_prosedur_diskon($arr_parameter, $readonly, "template/diskon-konfigurasi-bqtq.html") );
			$template_diskon = $obyek_diskon_kombinasi->mekanisme_prosedur_diskon();

			if( !$diskon_belum_dialokasikan ) $diskon_belum_dialokasikan = $obyek_diskon_kombinasi->ada_yg_blm_dialokasikan();
			
			if($diskon_id==32 or $diskon_id==43 or $_REQUEST["diskonid"]==49 or $_REQUEST["diskonid"]==50)
				$saldo_budget_kombinasi = prosedur_khusus_tambahan_diskon::saldo_bqtq_pusat( "'" . main::formatting_query_string( $data_dealer["idcust"] ) . "'" )[ $obyek_diskon_kombinasi->prefiks_identifikasi_bqtq() . "Avail"]  * $obyek_diskon_kombinasi->persentase_budget_bisa_digunakan;
			else
				$saldo_budget_kombinasi = prosedur_khusus_tambahan_diskon::saldo_bqtq( "'" . main::formatting_query_string( $data_dealer["idcust"] ) . "'" )[ $obyek_diskon_kombinasi->prefiks_identifikasi_bqtq() . "Avail"]  * $obyek_diskon_kombinasi->persentase_budget_bisa_digunakan;
			
			if( $obyek_diskon_kombinasi->saldo_tersedia_akhir() <= 0 ) $display_diskon_share = "none";

		}
		
		$alternatif_kombinasi_budget_diskon .= $template_diskon;
	}
}

// saldo budget tersisa
if($_REQUEST["diskonid"]==32 or $_REQUEST["diskonid"]==43 or $_REQUEST["diskonid"]==49 or $_REQUEST["diskonid"]==50)
	$saldo_budget = prosedur_khusus_tambahan_diskon::saldo_bqtq_pusat( "'" .  main::formatting_query_string( $data_dealer["idcust"] ) . "'", "" );
else
	$saldo_budget = prosedur_khusus_tambahan_diskon::saldo_bqtq( "'" .  main::formatting_query_string( $data_dealer["idcust"] ) . "'", "" );

$arr_tambahan_diskon_share[] = main::formatting_query_string( $_REQUEST["diskonid"] ); 
if( trim(@$_REQUEST["dk"]) != "" ){
	$arr_dk = explode(",", $_REQUEST["dk"]);
	foreach( $arr_dk as $dk )	$arr_tambahan_diskon_share[] = main::formatting_query_string( $dk );
}

// saldo budget tersedia
foreach( $arr_tambahan_diskon_share as $diskon_id ){
	$jenis_saldo = "";
	if($diskon_id == 13) $jenis_saldo = "bq";
	elseif($diskon_id == 14 or $diskon_id == 32 or $diskon_id == 43) $jenis_saldo = "tq";
	elseif($diskon_id == 49) $jenis_saldo = "bbt";
	elseif($diskon_id == 50 or $diskon_id == 51) $jenis_saldo = "tt";
	$saldo_tersedia[ $diskon_id ] = $saldo_budget[ $jenis_saldo . "Avail" ];
	//$saldo_tersedia[ $diskon_id ] = $saldo_budget[ ( $diskon_id == 13 ? "bq" : "tq" ) . "Avail" ];
	$saldo_tersedia_formatted[ $diskon_id ] = main::number_format_dec( $saldo_tersedia[ $diskon_id ] );
	if( $saldo_tersedia[ $diskon_id ] <= 0 ) $display_diskon_share = "none";
}

// identifikasi item
if( @$_REQUEST["item_id"] != "" ){
	$_REQUEST["item"] = $_REQUEST["item_id"];
	$data_item = sqlsrv_fetch_array( order::daftar_item($data_dealer["disc"]) );
	$item_free = $data_item["desc"];
	$item_free_kuantitas = $_REQUEST["qty"];
	$item_free_nominal = $data_item["unitprice"] * $_REQUEST["qty"];
	$item_free_nominal_formattted = main::number_format_dec( $data_item["unitprice"] * $_REQUEST["qty"] );	
}

// load command	
include_once "diskon-pengajuan-pilihitemfree-bqtq.php.command.php";


$data_item = "<div>Mohon cari item terlebih dahulu!</div>";
if( @$_REQUEST["item"] != ""){
	$rs_item = order::daftar_item( $data_dealer["disc"], @$_REQUEST["cbx"] );
	$item_template = file_get_contents("template/item.html");
	$counter = 1;
	$data_item = "<div>Sebanyak ". sqlsrv_num_rows( $rs_item ) ." data item ditemukan.<br /></div>";
	while( $item = sqlsrv_fetch_array( $rs_item ) ){
		$arr["#kelas#"] = $counter % 2 == 0 ? 1 : 2;
		$arr["#item#"] = $item["itemno"];
		$arr["#order_id#"] = $data_dealer["order_id"];
		$arr["#harga#"] = main::number_format_dec($item["unitprice"]);
		$arr["#harga_unformatted#"] = $item["unitprice"];
		$arr["#itemdesc#"] = $item["desc"];
		$arr["#stok_lokal#"] = $item["qty_lokal"];
		$arr["#stok_pusat#"] = $item["qty_pst"];
		$arr["#gudang_lokal#"] = $_SESSION["cabang"];
		$arr["#qty#"] = $item["qty_lokal"] > 0 || $item["qty_pst"] > 0 ? 1 : 0;
		$arr["#disabled#"] = $item["qty_lokal"] <= 0 && $item["qty_pst"] <= 0 ? "disabled" : "";
		$arr["#disabled_lokal#"] = $item["qty_lokal"] < 1 ||  @$arr_order_gudang[ $_SESSION["cabang"] ] <= 0 ? "disabled" : "";
		$arr["#disabled_pusat#"] = $item["qty_pst"] < 1 || @$arr_order_gudang[ "GDGPST" ] <= 0 ? "disabled" : "";
		
		$arr["#saranpaket#"] = "";
	
		$data_item .= str_replace( array_keys( $arr ), array_values( $arr ), $item_template );
		$counter++;
	}
}


?>