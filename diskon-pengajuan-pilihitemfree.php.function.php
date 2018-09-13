<?

// load dealer
$_POST["sc"] = "cl";
//$_POST["kode_sales"] = $_SESSION["sales_kode"];
//$_POST["kode_dealer"] = $_REQUEST["dealer_id"];
$_SESSION["order_id"] = $_REQUEST["order_id"];

include_once "dealer.php";
$rs_dealer = sql::execute( $sql );
$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("Gagal mendapatkan data dealer!");

//if( $data_dealer["order_id"] == "" ||  $data_dealer["kode_sales"] != $_SESSION["sales_kode"] )
//	$data_dealer["order_id"] = order::orderid( $data_dealer["idcust"], 0 /*$data_dealer["disc"]*/ );

// cek data diskon
$rs_diskon = tambahan_diskon::daftar_tambahan_diskon( array("b.diskon_id" => array("=", "'". main::formatting_query_string( $_REQUEST["diskonid"] ) ."'") ) );
$data_diskon = sqlsrv_fetch_array( $rs_diskon );

// cek data order per gudang
$arr_order_gudang = array();
$rs_data_order_gudang = order::daftar_order_item( $data_dealer["order_id"] );
while( $data_order_gudang = sqlsrv_fetch_array( $rs_data_order_gudang ) )
	@$arr_order_gudang[ $data_order_gudang["gudang"] ] += $data_order_gudang["kuantitas"];

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
		$arr["#disabled_lokal#"] = $item["qty_lokal"] < 1 ||  (array_key_exists($_SESSION["cabang"], $arr_order_gudang) && @$arr_order_gudang[ $_SESSION["cabang"] ] <= 0 )  ? "disabled" : "";
		$arr["#disabled_pusat#"] = $item["qty_pst"] < 1 || (array_key_exists("GDGPST", $arr_order_gudang) && @$arr_order_gudang[ "GDGPST" ] <= 0 ) ? "disabled" : "";
		
		$arr["#saranpaket#"] = "";
	
		$data_item .= str_replace( array_keys( $arr ), array_values( $arr ), $item_template );
		$counter++;
	}
}


?>