<?

$_POST["sc"] = "cl";
$_POST["kode_sales"] = $_SESSION["sales_kode"];
$_POST["cari_dealer"] = @$_REQUEST["t_dealer"];
$jumlah_data = 0;

include_once "dealer.php";

$rs_spesifik_dealer = order::daftar_dealer_spesifik_utk_user();

if( isset($_REQUEST["t_dealer"]) || sqlsrv_num_rows( $rs_spesifik_dealer ) ){
	// load dealer
	$rs_dealer = sql::execute( $sql );
	
	if( sqlsrv_num_rows( $rs_spesifik_dealer ) ) $rs_dealer = $rs_spesifik_dealer;

	$template_dealer = file_get_contents("template/data-dealer.html");
	$data_dealer = "";
	$counter = 0;
	$arr_dealer_id = array();
	//$jumlah_data = sqlsrv_num_rows( $rs_dealer );
	while( $dealer = sqlsrv_fetch_array( $rs_dealer ) ){
		
		if( in_array($dealer["idcust"], $arr_dealer_id) ) continue;
		else	$arr_dealer_id[] = $dealer["idcust"];
		
		$arr_data["#kelas#"] = $counter % 2 ? "1" : "2";
		$arr_data["#kode_dealer#"] = $dealer["idcust"];
		$arr_data["#nama_dealer#"] = $dealer["namecust"];
		$arr_data["#alamat#"] = $dealer["addr"] . " " . $dealer["namecity"];
		$arr_data["#diskon#"] = $dealer["disc"];
		$arr_data["#order#"] = "";//$dealer["order_id"] != "" ? "<br /><strong>Order belum diselesaikan : ". $dealer["order_id"] ."</strong>" : "";
		$arr_src = array_keys( $arr_data );
		$arr_rpl = array_values( $arr_data );
		$data_dealer .= str_replace($arr_src, $arr_rpl, $template_dealer);
		$counter++;
	}
	$jumlah_data = $counter;
}




?>