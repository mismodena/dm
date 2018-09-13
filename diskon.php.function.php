<?

$_POST["sc"] = "cl";
$_POST["kode_sales"] = $_SESSION["sales_kode"];
$_POST["cari_dealer"] = @$_REQUEST["t_dealer"];
$jumlah_data = 0;

include_once "dealer.php";

if( isset($_REQUEST["t_dealer"]) ){
	// load dealer
	$rs_dealer = sql::execute( $sql );

	$template_dealer = file_get_contents("template/data-dealer-diskon.html");
	$data_dealer = "";
	$counter = 0;
	//$jumlah_data = sqlsrv_num_rows( $rs_dealer );
	while( $dealer = sqlsrv_fetch_array( $rs_dealer ) ){
		
		if( $dealer["user_id"] != "" ){
			if( $dealer["user_id"] != $_SESSION["sales_id"] ) continue;
		}
		
		// cek order dealer yg blm dikirimkan
		if( $dealer["order_id"] != "" ){
			$nominal_order = order::nominal_order( $dealer["idcust"], array( "b.order_id" => array("=",  "'". $dealer["order_id"] ."'" ), "b.kirim" => array("=",  "'0'" ) ) );
			if( $nominal_order["nominal_order"] <= 0 )
				$dealer["order_id"] = "";
		}
		
		$arr_data["#kelas#"] = $counter % 2 ? "1" : "2";
		$arr_data["#kode_dealer#"] = $dealer["idcust"];
		$arr_data["#order_id#"] =$nominal_order["order_id"];
		$arr_data["#nama_dealer#"] = $dealer["namecust"];
		$arr_data["#alamat#"] = $dealer["addr"] . " " . $dealer["namecity"];
		$arr_data["#diskon#"] = $dealer["disc"];
		$arr_data["#nilai-order#"] = $dealer["order_id"] != "" ? main::number_format_dec( $nominal_order["nominal_order"] ) : "";
		$arr_data["#display#"] = $dealer["order_id"] != "" ? "block" : "none";
		$arr_src = array_keys( $arr_data );
		$arr_rpl = array_values( $arr_data );
		$data_dealer .= str_replace($arr_src, $arr_rpl, $template_dealer);
		$counter++;
	}
	$jumlah_data = $counter;
}




?>