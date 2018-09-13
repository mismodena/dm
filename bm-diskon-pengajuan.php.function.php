<?

$arr_parameter["/*load pengajuan*/"] = array("/*load pengajuan*/", " b.bm = '". main::formatting_query_string( $_SESSION["sales_kode"] ) ."' ");
$arr_parameter["a.kirim"] = array("<>", "1");
$arr_parameter["a.pengajuan_diskon"] = array("=", "1");

$rs_pengajuan_diskon = tambahan_diskon::daftar_order( $arr_parameter );

$template_order = file_get_contents("template/data-dealer-diskon.html");
$data_dealer = "";
$counter = 0;

while( $dealer = sqlsrv_fetch_array( $rs_pengajuan_diskon ) ){
	
	// cek order dealer yg blm dikirimkan
	if( $dealer["order_id"] != "" ){
		
		$nominal_order = order::nominal_order( $dealer["idcust"], array( "b.order_id" => array("=",  "'". main::formatting_query_string( $dealer["order_id"] ) ."'" ) ) );
		$data_order_accpac = @sqlsrv_fetch_array( order::daftar_order_histori( array("a.order_id" => array( "=", "'". main::formatting_query_string( $dealer["order_id"] ) ."'" ) ) ) );
		
		if( /*$nominal_order["nominal_order_net"] <= 0 ||*/ $data_order_accpac["nilai_order"] != "" ){
			if( $data_order_accpac["nilai_order"] != "" ) sql::execute("update [order] set kirim = 1 where order_id = '". main::formatting_query_string( $data_order_accpac["order_id"] ) ."';");
			continue;
		}
		
	}
	
	$arr_data["#kelas#"] = $counter % 2 ? "1" : "2";
	$arr_data["#kode_dealer#"] = $dealer["idcust"];
	$arr_data["#order_id#"] =$nominal_order["order_id"];
	$arr_data["#nama_dealer#"] = $dealer["namecust"];
	$arr_data["#alamat#"] = $dealer["addr"] . " " . $dealer["namecity"];
	$arr_data["#diskon#"] = $dealer["disc"];
	$arr_data["#nilai-order#"] = $dealer["order_id"] != "" ? main::number_format_dec( $nominal_order["nominal_order_net"] ) : "";
	$arr_data["#display#"] = $dealer["order_id"] != "" ? "block" : "none";
	$arr_data["#nama-sales#"] = $dealer["nama_lengkap"];
	$arr_src = array_keys( $arr_data );
	$arr_rpl = array_values( $arr_data );
	$data_dealer .= str_replace($arr_src, $arr_rpl, $template_order);
	$counter++;
	
}

$jumlah_data = $counter;
?>