<?

function filter_diskon( $data_dealer, $diskon_id ){

	$return = true;
	$arr_diskon_id = array();

	include_once "lib/cls_prosedur_khusus_tambahan_diskon.php";
	$front_margin = prosedur_khusus_tambahan_diskon::front_margin_dc_charge( $data_dealer );
	
	if( @$front_margin["tradPercentage"] > 0 ) $ada_front_margin = true;

	// dealer modern
	if( in_array( trim( $data_dealer["idgrp"] ), explode(",", str_replace("'", "", $GLOBALS["arr_dealer_modern"] ) ) ) ) {
		$arr_diskon_id = array(1,2,4,7,8,9,10,12,13,14,16,20,22,23,24,26,27,28,29,/*32*/43,41,42,44);

		// dealer modern non-TT
		if( in_array( trim( $data_dealer["idgrp"] ), array('Y1','Y11','Y12') ) ) 
			$arr_diskon_id = array(1,2,4,7,8,9,10,12,13,14,16,20,21,22,23,24,26,27,28,/*32*/43,41,42,44);
		
		if( $ada_front_margin ) $arr_diskon_id[]  = 27;
	
	// pameran
	}elseif( in_array( substr(trim( $data_dealer["idcust"] ), 0, 2 ), explode(",", str_replace("'", "", $GLOBALS["arr_dealer_pameran"] ) ) )  ){
		$arr_diskon_id = array(31);

		if( substr(trim( $data_dealer["idcust"] ), 0, 2 ) =='WG' )
			$arr_diskon_id = array(31,34,42);
		
	// project + bank
	}elseif( in_array( substr(trim( $data_dealer["idcust"] ), 0, 1 ), explode(",", str_replace("'", "", $GLOBALS["arr_dealer_project"] ) ) ) )
		$arr_diskon_id = array(20,30,4,9,10,18,12,22,23,24);
	
	// project ecommerce
	elseif( in_array( substr(trim( $data_dealer["idcust"] ), 0, 2 ), explode(",", str_replace("'", "", $GLOBALS["arr_dealer_project_ecommerce"] ) ) ) )
		$arr_diskon_id = array(20,30,4,9,10,18,12,22,23,24);
		
	// professional
	elseif( in_array( trim( $data_dealer["email1"] ), explode(",", str_replace("'", "", $GLOBALS["arr_dealer_professional"] ) ) )  )
		$arr_diskon_id = array(1,20, 13,18,35,8,36,37,38,39,35,4,9,10,42,12,44);
	
	// cash barang jadi professional
	elseif( $data_dealer["idcust"] ==  str_replace("'", "",$arr_dealer_wajib_professional) ) 
		$arr_diskon_id = array(21);
		
	// project lain (cash barang jadi project)
	elseif( $data_dealer["idcust"] ==  str_replace("'", "",$arr_dealer_wajib_project) ) 
		$arr_diskon_id = array(30);
		
	// tradisional
	else
		$arr_diskon_id = array(1,2,4,7,33,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,32,35,42,44);
	
	if( count( $arr_diskon_id ) > 0 && !in_array( $diskon_id, $arr_diskon_id ) ) $return = false;

	return $return;
	
}

// load dealer
$_POST["sc"] = "cl";
//$_POST["kode_sales"] = $_SESSION["sales_kode"];
//$_POST["kode_dealer"] = $_REQUEST["dealer_id"];

include_once "dealer.php";
$rs_dealer = sql::execute( $sql );
$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("Gagal mendapatkan data dealer!");

//if( $data_dealer["order_id"] == "" ||  $data_dealer["kode_sales"] != $_SESSION["sales_kode"] )
//	$data_dealer["order_id"] = order::orderid( $data_dealer["idcust"], 0 /*$data_dealer["disc"]*/ );
$data_dealer["order_id"] = $_REQUEST["order_id"];

// nilai total order
$nominal_order = order::nominal_order( "", array( "b.order_id" => array("=",  "'". $data_dealer["order_id"] ."'" ) ) );

// daftar tambahan diskon
$list_diskon = "";
$counter = 1;
$template_diskon = file_get_contents("template/diskon.html");

$rs_daftar_diskon = tambahan_diskon::daftar_tambahan_diskon( array(), $data_dealer["order_id"], true );

$counter_belum_diterapkan = 0;

while( $diskon = sqlsrv_fetch_array( $rs_daftar_diskon ) ){
	
	// filter penampilan diskon sesuai dengan kondisi order
	$bisa_terapkan = true;
	$mekanisme_filter_diskon = __DIR__ . "/mekanisme_filter_diskon/" . $diskon["diskon_id"] . ".php";
	if( file_exists( $mekanisme_filter_diskon ) )
		require_once $mekanisme_filter_diskon;
	if( !$bisa_terapkan || $diskon["aktif_diskon"] != "1" || !filter_diskon( $data_dealer, $diskon["diskon_id"] ) ) continue;
	
	if( $diskon["order_diskon"] == "" ) $counter_belum_diterapkan++;
		
	$arr["#kelas#"] = $counter % 2 == 0 ? 1 : 2;
	$arr["#counter#"] = $counter;
	$arr["#img-gift-display#"] = $diskon["gift_diskon"] == 1 ? "block" : "none";
	$arr["#diskon-label#"] = $diskon["diskon"];
	$arr["#diskonid#"] = $diskon["diskon_id"];
	$arr["#diskon#"] = $diskon["default_nilai"];
	$arr["#opsi_tampilan-pilih-item#"] = "sembunyikan";
	$arr["#opsi_tampilan-pilih-free#"] = "sembunyikan" ;
	$arr["#diskon-label-keterangan#"] = $diskon["keterangan_diskon"] != "" ? "<br />" . $diskon["keterangan_diskon"] : "";
	$arr["#display-nilai-diskon#"] = "none";
	$arr["#diskon-keterangan#"] = "";
	$tambahan_diskon_persetujuan = new tambahan_diskon_persetujuan( $data_dealer["order_id"], $diskon["diskon_id"] );
	$arr["#persetujuan-melalui#"] = count( $tambahan_diskon_persetujuan->posisi ) > 0 ? implode(", ", $tambahan_diskon_persetujuan->posisi ) : 'Tidak memerlukan persetujuan';
	unset($tambahan_diskon_persetujuan);
	$arr["#display-persetujuan#"] = "none";
	
	if( $diskon["order_diskon"] == $diskon["diskon_id"] ){
		$arr["#checked-cb#"] = "checked";
		$arr["#diskon-diterapkan#"] = "diskon-diterapkan";
	}else {
		$arr["#checked-cb#"] = "";
		$arr["#diskon-diterapkan#"] = "";
	}

	$list_diskon .= str_replace( array_keys( $arr ), array_values( $arr ), $template_diskon );
	
	$counter++;
}

?>