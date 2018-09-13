<?

if( @$_SESSION["kode_dealer"] == "" && @$_REQUEST["dealer"] == "" )
	die("<script>location.href='transaksi.php'</script>");

// load dealer
$_POST["sc"] = "cl";
$_POST["kode_sales"] = $_SESSION["sales_kode"];
if (@$_REQUEST["dealer"] != "") 
	$_SESSION["kode_dealer"] = @$_REQUEST["dealer"];
$_POST["kode_dealer"] = $_SESSION["kode_dealer"];
$_POST["pengajuan_diskon"] = 0;

include "dealer.php";
//$rs_dealer = sql::execute( $sql . " and e.kode_sales = '". main::formatting_query_string($_POST["kode_sales"]) ."' " );
$rs_dealer = sql::execute( $sql . " and e.user_id = '". main::formatting_query_string($_SESSION["sales_id"]) ."' " );

if( sqlsrv_num_rows( $rs_dealer ) <= 0 ){
	order::orderid( $_SESSION["kode_dealer"], 0, true );
	//$rs_dealer = sql::execute( $sql . " and e.kode_sales = '". main::formatting_query_string($_POST["kode_sales"]) ."' " );
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

$dm = new order( $_SESSION["order_id"] );
include_once "transaksi-2-order.php";
goto SkipDM;

$_REQUEST["dealer_id"] = $_SESSION["kode_dealer"];
$_REQUEST["order_id"] = $_SESSION["order_id"];

$data_order = "";
$subtotal_noncampaign = $subtotal_campaign = $total_diskon = 0;

// cek ketersediaan stok
$arr_stok = order::cek_cek_stok_item_order($_REQUEST["order_id"]);
while( $stok = sqlsrv_fetch_array( $arr_stok ) )
	$item_stok[ $stok["item_id"] ][ $stok["gudang"] ] = $stok["kuantitas"];

// load item order non paket
$data_order = "<h3 class=\"sub-judul\" style=\"background-color:#fafc9f\">:: Daftar Item Non Campaign :: </h3>";

$parameter["a.dealer_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["dealer_id"] ) ."'");
$parameter["a.order_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'");
$parameter["/*b.paketid*/"] = array("", "( b.paketid = '' or b.paketid is null)");
$rs_order = sql_dm::browse_cart( $parameter );

$template = file_get_contents("template/item-non-paket.html");
$item_non_paket = "";
$counter = 1;
$item_stok_habis = false;

if( sqlsrv_num_rows($rs_order) > 0 ){
	while( $order = sqlsrv_fetch_array($rs_order) ){
		
		$saran_paket = array();
		
		$rs_paket_tersedia = order::browse_paket_per_item( $order["item_id"] );		
		if( sqlsrv_num_rows($rs_paket_tersedia) > 0 ){
			while( $paket_tersedia = sqlsrv_fetch_array( $rs_paket_tersedia ) )
				$saran_paket[ $paket_tersedia["paketid"] ] = $paket_tersedia["keterangan_paket"];
		}
		
		$arr_list = array( $order["item_seq"], $order["item_id"], $order["harga"], $order["kuantitas"], $saran_paket );
		list($itemseq, $item, $harga, $kuantitas, $saran_paket) = $arr_list;
		@$subtotal_noncampaign += $harga * $kuantitas;
		unset( $arr_paket );
		
		if( is_array( $saran_paket ) && count( $saran_paket ) > 0 )
		foreach( $saran_paket as $paket => $keterangan_paket )
			$arr_paket[] =  "<input type=\"radio\" name=\"r_". $itemseq ."\" id=\"r_". $itemseq ."_". $paket ."\" value=\"". $paket ."\" />&nbsp;&nbsp;&nbsp;
				<label for=\"r_". $itemseq ."_". $paket ."\"><strong><a href=\"paket-detail.php?paketid=". $paket ."\" style=\"color:blue;\">" . $paket . "</a></strong> :: " . $keterangan_paket . "</label>";
		
		$arr_data["#kelas#"] = $counter % 2 ==0 ? 1 : 2; 
		$arr_data["#item#"] = $item; 
		$arr_data["#item_seq#"] = $itemseq; 
		$arr_data["#itemdesc#"] = $order["item_nama"]; 
		$arr_data["#harga#"] = main::number_format_dec( $harga ); 
		
		 if( $item_stok[ $item ][ $order["gudang"] ] < 0 )	$item_stok_habis = true;		
		$arr_data["#stok#"] = $item_stok[ $item ][ $order["gudang"] ] < 0 ? 0 : $item_stok[ $item ][ $order["gudang"] ]; 
		$arr_data["#kuantitas#"] = $kuantitas;
		$arr_data["#gudang#"] = $order["gudang"]; 
		$arr_data["#display-gudang-non-lokal#"] = trim(strtoupper($order["gudang"])) != trim(strtoupper($_SESSION["cabang"])) ? "block" : "none" ; 
		$arr_data["#subtotal#"] = main::number_format_dec( $harga * $kuantitas ) . ( $item_stok[ $item ][ $order["gudang"] ] < 0 ? "<br /><sup class=\"peringatan\">Mohon ubah kuantitas sesuai ketersediaan stok</sup>" : "" );
		$arr_data["#saranpaket#"] = @implode("<br />", $arr_paket); 
		$arr_data["#btn_display#"] = isset( $arr_paket ) ? "block" : "none"; 
		
		$item_non_paket .= str_replace( array_keys( $arr_data ), array_values( $arr_data ),  $template);
		$counter++;
		
	}
	$data_order .= $item_non_paket  . "<br /><h3>Total Order Non Campaign :: Rp" . main::number_format_dec($subtotal_noncampaign) . "</h3>" ;
	
}else $data_order .= "Tidak ada item non campaign!";


$data_order .= "<br /> <h3 class=\"sub-judul\">:: Daftar Item Campaign :: </h3>";

Start_Simulasi:

$total_diskon = 0;
$template = file_get_contents("template/item-paket.html");
$item_paket = "";
$counter = 1;

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
	$data_order .= "<div style=\"margin-bottom:10px; padding-bottom:3px; border-bottom:3px solid #999\">Lihat item berdasarkan campaign : <br />
		<a href=\"javascript:tunjukkan_semua_item_paket()\">Semua Item</a>#daftar-paket-item-order#
		</div>";	
	
	while( $order = sqlsrv_fetch_array( $rs_order ) ){
		
		// keterangan paket parameter
		$urutan_parameter = $order["urutan_parameter"] >= 1 ? $order["urutan_parameter"] : 1;
		unset( $parameter, $arr_keterangan_paket_parameter );
		$parameter["e.paketid"] = array("=", "'". main::formatting_query_string($order["paketid"]) ."'");
		$tambahan_parameter = "( e.urutan_parameter = '". main::formatting_query_string($order["urutan_parameter"]) ."' 
			or e.grup_parameter = ( select grup_parameter from paket_parameter where paketid = '". main::formatting_query_string($order["paketid"]) ."' and urutan_parameter = '". main::formatting_query_string($order["urutan_parameter"]) ."' ) )";
		$parameter["/*e.urutan_parameter*/"] = array("", $tambahan_parameter);
		$rs_keterangan_paket_parameter = sql_dm::browse_paket_parameter( $parameter );
		while( $keterangan_paket_parameter = sqlsrv_fetch_array( $rs_keterangan_paket_parameter ) )
			$arr_keterangan_paket_parameter[] = $keterangan_paket_parameter["keterangan_paket_parameter"];
		
		$arr_list = array( $order["item_seq"], $order["item_id"], $order["harga"], $order["kuantitas"], $order["paketid"] );
		list($itemseq, $item, $harga, $kuantitas, $paketid) = $arr_list;
		
		$arr_daftar_paket[ "'" . $paketid . "'" ] = $paketid;
		
		$total_diskon += @$order["diskon"];	
		$subtotal_diskon = ( $harga * $kuantitas ) - @$order["diskon"];
		@$subtotal_campaign += $subtotal_diskon;
		
		$shortcut[$paketid]["item"][]=$item;
		$shortcut[$paketid]["kuantitas"][]=$kuantitas;
		
		$arr_data["#kelas#"] = $counter % 2 ==0 ? 1 : 2; 
		$arr_data["#item#"] = $item; 
		$arr_data["#item_seq#"] = $itemseq; 
		$arr_data["#order_id#"] = $_REQUEST["order_id"]; 
		$arr_data["#paketid#"] = $paketid; 
		$arr_data["#itemdesc#"] = $order["item_nama"]; 
		$arr_data["#harga#"] = main::number_format_dec( $harga ); 
		
		 if( $item_stok[ $item ][ $order["gudang"] ] < 0 )	$item_stok_habis = true;		
		$arr_data["#stok#"] = $item_stok[ $item ][ $order["gudang"] ] < 0 ? 0 : $item_stok[ $item ][ $order["gudang"] ]; 
		$arr_data["#kuantitas#"] = $kuantitas; 
		$arr_data["#gudang#"] = $order["gudang"]; 
		$arr_data["#display-gudang-non-lokal#"] = trim(strtoupper($order["gudang"])) != trim(strtoupper($_SESSION["cabang"])) ? "block" : "none" ; 
		$arr_data["#diskon#"] = main::number_format_dec( $order["diskon"] ); 
		$arr_data["#subtotal#"] = main::number_format_dec( $harga * $kuantitas ) . ( $item_stok[ $item ][ $order["gudang"] ] < 0 ? "<br /><sup class=\"peringatan\">Mohon ubah kuantitas sesuai ketersediaan stok</sup>" : "" );
		$arr_data["#subtotal_diskon#"] = main::number_format_dec( $subtotal_diskon ); 
		$arr_data["#display-reward-non-diskon#"] = @$dm->arr_item_reward_non_diskon[ $itemseq ][ $paketid ] != "" ? "line-height:27px" : "display:none"; 
		$arr_data["#reward-non-diskon#"] = strtoupper( @$dm->arr_item_reward_non_diskon[ $itemseq ][ $paketid ] ); 
		$arr_data["#paket#"] = "<strong><a href=\"paket-detail.php?paketid=". $paketid ."\" style=\"color:blue;\">" . $paketid . "</a></strong> - " . 
				( is_array( @$arr_keterangan_paket_parameter ) && count( $arr_keterangan_paket_parameter ) > 0 ? 
					implode(",", $arr_keterangan_paket_parameter) : 
					$order["keterangan_paket"] ); 
		
		$item_paket .= str_replace( array_keys( $arr_data ), array_values( $arr_data ),  $template);
		$counter++;
		
	}
	
	foreach( $arr_daftar_paket as $paketid ){
		$ringkasan_item_paket = "(". count( $shortcut[$paketid]["item"] ) ." item - ". array_sum( $shortcut[$paketid]["kuantitas"] ) ." unit)";
		@$s_daftar_paket .= " | <a href=\"javascript:filter_item_paket('". $paketid ."')\">". $paketid ." ". $ringkasan_item_paket ."</a>";
	}
	
	$data_order = str_replace("#daftar-paket-item-order#", $s_daftar_paket, $data_order);
	
	@$data_order .=  $item_paket  . "<br /><h3> Total Order Campaign :: Rp" . main::number_format_dec($subtotal_campaign) . "</h3>";

}else  $data_order .= "Tidak ada item campaign!";

$item_free = "";
$counter = 1;

unset($parameter);
$parameter["a.dealer_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["dealer_id"] ) ."'");
$parameter["a.order_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'");
$parameter["/*b.paketid*/"] = array("", "( b.paketid <> '' and b.paketid is not null)");
$parameter["isnull(b.harga, 0)"] = array("<=", "0");
$rs_order = sql_dm::browse_cart( $parameter );

if( sqlsrv_num_rows($rs_order) > 0 ){
	
	$data_order .=  "<br /><h3 class=\"sub-judul\">:: Daftar Free Item ::</h3>";
	$template = file_get_contents("template/item-free.html");
	
	while( $order = sqlsrv_fetch_array( $rs_order ) ){						
			
			$arr_list = array( $order["item_seq"], $order["item_id"], $order["harga"], $order["kuantitas"], $order["paketid"] );
			list($itemseq, $item, $harga, $kuantitas, $paketid) = $arr_list;						
			
			$arr_data["#kelas#"] = $counter % 2 ==0 ? 1 : 2; 
			$arr_data["#item#"] = $item; 
			$arr_data["#itemdesc#"] = $order["item_nama"]; 
			
			 if( $item_stok[ $item ][ $order["gudang"] ] < 0 )	$item_stok_habis = true;		
			$arr_data["#stok#"] = $item_stok[ $item ][ $order["gudang"] ] < 0 ? 0 : $item_stok[ $item ][ $order["gudang"] ]; 
			$arr_data["#kuantitas#"] = $kuantitas; 
			$arr_data["#gudang#"] = $order["gudang"]; 
			$arr_data["#display-gudang-non-lokal#"] = trim(strtoupper($order["gudang"])) != trim(strtoupper($_SESSION["cabang"])) ? "block" : "none" ; 
			$arr_data["#keterangan_kuantitas_tidak_tersedia#"] = $item_stok[ $item ][ $order["gudang"] ] < 0 ? "<sup class=\"peringatan\">Kuantitas free item akan disesuaikan sesuai ketersediaan stok</sup><br />" : "" ; 
			$arr_data["#paket#"] = "<strong><a href=\"paket-detail.php?paketid=". $paketid ."\" style=\"color:blue;\">" . $paketid . "</a></strong>"; 
			$item_free .= str_replace( array_keys( $arr_data ), array_values( $arr_data ),  $template);
			$counter++;

	}
	
}

$data_order .=  $item_free  . " <div class=\"total-harga\" style=\"line-height:21px\"><h4>Total Order<br />Rp" .  main::number_format_dec($subtotal_noncampaign + $subtotal_campaign + $total_diskon);
$data_order .=  "<br /><br />Total Diskon Campaign<br />Rp" . main::number_format_dec(  $total_diskon );
$data_order .=  "<br /> <br />Total Order Net<br />Rp" . main::number_format_dec($subtotal_noncampaign + $subtotal_campaign) . "</h4></div>";

// cek overlimit kredit
$s_script_overlimit = "";
$overlimit = order::check_overlimit( $_SESSION["kode_dealer"], ( $subtotal_noncampaign + $subtotal_campaign ) ) ;
if( $overlimit["is_overlimit"] ) 
	$s_script_overlimit = "
			document.getElementById('overlimit-note').style.display = 'block';
			document.getElementById('limit-kredit').innerHTML = '". main::number_format_dec( $overlimit["limit_kredit"] ) ."';
			document.getElementById('piutang+order').innerHTML = '". main::number_format_dec( $overlimit["piutang_plus_order_baru"] ) ."';
		";

$script = "
	try{
		document.getElementById('total-order').innerHTML = '". main::number_format_dec($subtotal_noncampaign + $subtotal_campaign + $total_diskon) ."';
		document.getElementById('total-diskon-campaign').innerHTML = '". main::number_format_dec($total_diskon) ."';
		document.getElementById('total-order-net').innerHTML = '". main::number_format_dec($subtotal_noncampaign + $subtotal_campaign) ."';
		". $s_script_overlimit ."
	}catch(e){}
";

// keterangan paket
if( is_array( @$arr_daftar_paket ) && count( $arr_daftar_paket ) > 0 ){
	
	$data_order .=  "Keterangan Campaign :: </strong><br />";
	unset( $parameter );
	$parameter["/*b.paketid*/"] = array("", " b.paketid in (". implode(",", array_keys($arr_daftar_paket) ) .") ");
	$rs_keterangan_paket = sql_dm::cari_paket( $parameter ) ;
	while( $keterangan_paket = sqlsrv_fetch_array( $rs_keterangan_paket ) )
		$data_order .=  "<strong><a href=\"paket-detail.php?paketid=". $keterangan_paket["paketid"] ."\" style=\"color:blue;\">" . $keterangan_paket["paketid"] . "</a></strong> - " . $keterangan_paket["keterangan_paket"] . "<br />";
	
}
SkipDM:

?>