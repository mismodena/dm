<?

// load dealer
$_POST["sc"] = "cl";
//$_POST["kode_sales"] = $_SESSION["sales_kode"];
//$_POST["kode_dealer"] = $_REQUEST["dealer_id"];

include_once "dealer.php";
$rs_dealer = sql::execute( $sql );
$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("Gagal mendapatkan data dealer!");

if( $data_dealer["order_id"] == "" ||  $data_dealer["kode_sales"] != $_SESSION["sales_kode"] )
	$data_dealer["order_id"] = order::orderid( $data_dealer["idcust"], 0 /*$data_dealer["disc"]*/ );

// cek data diskon
$rs_diskon = tambahan_diskon::daftar_tambahan_diskon( array("b.diskon_id" => array("=", "'". main::formatting_query_string( $_REQUEST["diskonid"] ) ."'") ), $_REQUEST["order_id"], true );
$data_diskon = sqlsrv_fetch_array( $rs_diskon );

$order_id = $_SESSION["order_id"];
$dm = new order( $order_id );
include_once "transaksi-2-order.php";
goto HABIS;

// cek ketersediaan stok
$arr_stok = order::cek_cek_stok_item_order( $_REQUEST["order_id"] );
while( $stok = sqlsrv_fetch_array( $arr_stok ) )
	$item_stok[ $stok["item_id"] ] = $stok["kuantitas"];

$subtotal_noncampaign = $subtotal_campaign = $total_diskon = 0;

// load item order non paket
$data_order = "<h3 class=\"sub-judul\">:: Daftar Item Non Campaign :: </h3>";

$parameter["a.dealer_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["dealer_id"] ) ."'");
$parameter["a.order_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'");
$parameter["/*b.paketid*/"] = array("", "( b.paketid = '' or b.paketid is null)");
$rs_order = sql_dm::browse_cart( $parameter );

$template = file_get_contents("template/item-non-paket.html");
$item_non_paket = "";
$counter = 1;

if( sqlsrv_num_rows($rs_order) > 0 ){

	while( $order = sqlsrv_fetch_array($rs_order) ){
		$arr_list = array( $order["item_seq"], $order["item_id"], $order["harga"], $order["kuantitas"], "" ); 
		list($itemseq, $item, $harga, $kuantitas, $saran_paket) = $arr_list;
		@$subtotal_noncampaign += $harga * $kuantitas;
		
		$arr_data["#kelas#"] = $counter % 2 ==0 ? 1 : 2; 
		$arr_data["#item#"] = $item; 
		$arr_data["#item_seq#"] = $itemseq; 
		$arr_data["#itemdesc#"] = $order["item_nama"]; 
		$arr_data["#harga#"] = main::number_format_dec( $harga ); 
		$arr_data["#stok#"] = $item_stok[ $item ] < 0 ? 0 : $item_stok[ $item ]; 
		$arr_data["#kuantitas#"] = $kuantitas;
		$arr_data["#subtotal#"] = main::number_format_dec( $harga * $kuantitas ) . ( $item_stok[ $item ] < 0 ? "<br /><sup class=\"peringatan\">Mohon ubah kuantitas sesuai ketersediaan stok</sup>" : "" );
		$arr_data["#saranpaket#"] = @implode("<br />", $arr_paket); 
		$arr_data["#btn_display#"] = isset( $arr_paket ) ? "block" : "none"; 
		
		$arr_data["#data-tambahan-diskon#"] = ""; 
		$arr_data["#diskonid#"] = $itemseq; 
		$arr_data["#disabled#"] = $item_stok[ $item ] < 0 ? "disabled" : ""; 
		
		if( $order["diskon_id"] == $_REQUEST["diskonid"] ){
			$arr_data["#checked-cb#"] = "checked";
			$arr_data["#diskon-diterapkan#"] = "diskon-diterapkan";
		}else {
			//if( $order["diskon_id"] != "" ) continue; // tidak bisa double tambahan diskon
			$arr_data["#checked-cb#"] = "";
			$arr_data["#diskon-diterapkan#"] = "";
		}
		
		$item_non_paket .= str_replace( array_keys( $arr_data ), array_values( $arr_data ),  $template);
		$counter++;
	}
	$data_order .= $item_non_paket  . "<br /><h3>Total Order Non Campaign :: Rp" . main::number_format_dec($subtotal_noncampaign) . "</h3>" ;

}else $data_order .= "Tidak ada item non campaign!";

// load item paket
$data_order .= "<h3 class=\"sub-judul\">:: Daftar Item Campaign :: </h3>";

unset($parameter);
$parameter["a.dealer_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["dealer_id"] ) ."'");
$parameter["a.order_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'");
$parameter["/*b.paketid*/"] = array("", "( ltrim(rtrim(b.paketid)) <> '' and b.paketid is not null)");
$parameter["/*b.harga*/"] = array("", "( b.harga <> '' and b.harga is not null and b.harga > 0)");

$rs_order = sql_dm::browse_cart( $parameter );

$template = file_get_contents("template/item-paket.html");
$item_paket = "";
$counter = 1;

if( sqlsrv_num_rows($rs_order) > 0 ){
	
	// untuk sort item berdasarkan campaign
	$data_order .= "<div style=\"margin-bottom:13px\">Lihat item berdasarkan campaign : <br />
		<a href=\"javascript:tunjukkan_semua_item_paket()\">Semua Item</a>#daftar-paket-item-order#
		</div>";	
	
	while( $order = sqlsrv_fetch_array( $rs_order ) ){
		$arr_list = array( $order["item_seq"], $order["item_id"], $order["harga"], $order["kuantitas"], $order["paketid"] );
		list($itemseq, $item, $harga, $kuantitas, $paketid) = $arr_list;
		
		$arr_daftar_paket[ $paketid ] = $paketid;
		
		$total_diskon += @$order["diskon"];	
		$subtotal_diskon = ( $harga * $kuantitas ) - @$order["diskon"];
		@$subtotal_campaign += $subtotal_diskon;
		
		$arr_data["#kelas#"] = $counter % 2 ==0 ? 1 : 2; 
		$arr_data["#item#"] = $item; 
		$arr_data["#item_seq#"] = $itemseq; 
		$arr_data["#paketid#"] = $paketid; 
		$arr_data["#itemdesc#"] = $order["item_nama"]; 
		$arr_data["#harga#"] = main::number_format_dec( $harga ); 
		$arr_data["#stok#"] = $item_stok[ $item ] < 0 ? 0 : $item_stok[ $item ]; 
		$arr_data["#kuantitas#"] = $kuantitas; 
		$arr_data["#diskon#"] = main::number_format_dec( $order["diskon"] ); 
		$arr_data["#subtotal#"] = main::number_format_dec( $harga * $kuantitas ) . ( $item_stok[ $item ] < 0 ? "<br /><sup class=\"peringatan\">Mohon ubah kuantitas sesuai ketersediaan stok</sup>" : "" );
		$arr_data["#subtotal_diskon#"] = main::number_format_dec( $subtotal_diskon ); 
		$arr_data["#display-reward-non-diskon#"] = @$dm->arr_item_reward_non_diskon[ $itemseq ][ $paketid ] != "" ? "line-height:27px" : "display:none"; 
		$arr_data["#reward-non-diskon#"] = strtoupper( @$dm->arr_item_reward_non_diskon[ $itemseq ][ $paketid ] ); 
		$arr_data["#paket#"] = "<strong><a href=\"paket-detail.php?paketid=". $paketid ."\" style=\"color:blue;\">" . $paketid . "</a></strong> - " . $order["keterangan_paket"]; 
		
		$arr_data["#data-tambahan-diskon#"] = ""; 
		$arr_data["#diskonid#"] = $itemseq; 
		$arr_data["#disabled#"] = $item_stok[ $item ] < 0 ? "disabled" : ""; 

		if( $order["diskon_id"] == $_REQUEST["diskonid"] ){
			$arr_data["#checked-cb#"] = "checked";
			$arr_data["#diskon-diterapkan#"] = "diskon-diterapkan";
		}else {
			//if( $order["diskon_id"] != "" ) continue; // tidak bisa double tambahan diskon
			$arr_data["#checked-cb#"] = "";
			$arr_data["#diskon-diterapkan#"] = "";
		}
		
		$item_paket .= str_replace( array_keys( $arr_data ), array_values( $arr_data ),  $template);
		$counter++;
	}

	foreach( $arr_daftar_paket as $paketid )
		@$s_daftar_paket .= " | <a href=\"javascript:filter_item_paket('". $paketid ."')\">". $paketid ."</a>";
	
	$data_order = str_replace("#daftar-paket-item-order#", $s_daftar_paket, $data_order);
		
	$data_order .=  $item_paket  . "<br /><h3> Total Order Campaign :: Rp" . main::number_format_dec($subtotal_campaign) . "</h3>";
	
}else $data_order .= "Tidak ada item campaign!";
HABIS:
?>