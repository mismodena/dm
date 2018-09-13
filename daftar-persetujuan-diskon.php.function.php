<?

unset( $parameter );
$parameter["a.order_id"] = array( " in ",  "( select  order_id from order_diskon_approval where disetujui is null and disetujui_oleh = '". main::formatting_query_string( $_SESSION["sales_nik"] ) ."' )" );
$parameter["a.kirim"] = array( " = ",  "0" );
$rs_pengajuan_diskon = tambahan_diskon::daftar_order( $parameter );

$template_order = file_get_contents("template/data-dealer-diskon.html");
$data_dealer = "";
$counter = 0;

while( $dealer = sqlsrv_fetch_array( $rs_pengajuan_diskon ) ){
	
	// cek order dealer yg blm dikirimkan
	if( $dealer["order_id"] != "" ){
		$nominal_order = order::nominal_order( $dealer["idcust"], array( "b.order_id" => array("=",  "'". $dealer["order_id"] ."'" ) ) );
		if( $nominal_order["nominal_order_net"] <= 0 )
			continue;
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