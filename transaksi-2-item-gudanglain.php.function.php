<?

// load dealer
$_POST["sc"] = "cl";
include_once "dealer.php";
$rs_dealer = sql::execute( $sql );
$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("<script>location.href='transaksi.php'</script>");

// cek order
if( $data_dealer["order_id"] == "" ) die("<script>location.href='transaksi.php'</script>");

// cek data gudang spesifik per user
$arr_gudang_user = order::daftar_item_gudang_spesifik_utk_user();

// cek data order per gudang
$arr_order_gudang = array();
$rs_data_order_gudang = order::daftar_order_item( $data_dealer["order_id"] );
while( $data_order_gudang = sqlsrv_fetch_array( $rs_data_order_gudang ) )
	@$arr_order_gudang[ $data_order_gudang["gudang"] ] += $data_order_gudang["sub_total"];

if( count( $arr_gudang_user ) > 0 ){
	$style = ".div-gudang-display{display:none}";
}else{
	$style = ".div-display, .button-display{display:none}";
}

$rs_item = order::daftar_item_semua_gudang( $_REQUEST["item"], @$_SESSION["disc_dealer"] != "" ? $_SESSION["disc_dealer"] : $data_dealer["disc"], $arr_gudang_user );

$s_data_item = "";
$template_data_item = file_get_contents( "template/item-gudanglain.html" );
while( $daftar_item = sqlsrv_fetch_array( $rs_item ) ){
	
	if( count( $arr_gudang_user ) > 0 && !in_array( $daftar_item["gudang"], $arr_gudang_user ) ) continue;
		
	$arr["#gudang#"] = $daftar_item["gudang"];
	$arr["#stok#"] = $daftar_item["kuantitas"];
	$arr["#nilai_order#"] = $daftar_item["kuantitas"] < 1 ? 0 : 1;
	$arr["#disabled#"] = $daftar_item["kuantitas"] < 1 || ( isset($_REQUEST["sp"]) && @$arr_order_gudang[ $daftar_item["gudang"] ] <= 0 ) ? "disabled" : "";
	$arr["#bgcolor#"] = $daftar_item["kuantitas"] < 1 ? "#EEE" : "transparent";
	$arr["#display_gudang#"] = $daftar_item["kuantitas"] < 1 ? "none" : "block";
	
	if( isset($_REQUEST["sp"]) ){
		$arr["#onclick#"] = "beli_itemfree('". $daftar_item["gudang"] ."', 'qty_". $daftar_item["gudang"] ."')";
		if( !in_array( $daftar_item["gudang"], array( $_SESSION["cabang"], "GDGPST" ) ) )
			$arr["#onclick#"] = "konfirmasi_beli_itemfree('". $daftar_item["gudang"] ."', 'qty_". $daftar_item["gudang"] ."')";
	}else{
		$arr["#onclick#"] = "beli('". $daftar_item["gudang"] ."', 'qty_". $daftar_item["gudang"] ."')";
		if( !in_array( $daftar_item["gudang"], array( $_SESSION["cabang"], "GDGPST" ) ) )
			$arr["#onclick#"] = "konfirmasi_beli('". $daftar_item["gudang"] ."', 'qty_". $daftar_item["gudang"] ."')";
	}
	
	$s_data_item .= str_replace( array_keys( $arr ), array_values( $arr ), $template_data_item );
}
		
$data_item = sqlsrv_fetch_array( $rs_item, 2, SQLSRV_SCROLL_FIRST );
$kode_item = $data_item["itemno"];
$nama_item = $data_item["model"];

$data_paket = array();
$rs_paket_tersedia = order::browse_paket_per_item( $data_item["itemno"] );		
if( sqlsrv_num_rows($rs_paket_tersedia) > 0 ){
	while( $paket_tersedia = sqlsrv_fetch_array( $rs_paket_tersedia ) )
		$data_paket[ $paket_tersedia["paketid"] ] = "<input type=\"radio\" name=\"r_". $data_item["itemno"] ."\" id=\"r_". $data_item["itemno"] ."_". $paket_tersedia["paketid"] ."\"value=\"". $paket_tersedia["paketid"] ."\" />&nbsp;&nbsp;&nbsp;
		<label for=\"r_". $data_item["itemno"] ."_". $paket_tersedia["paketid"] ."\"><strong><a href=\"paket-detail.php?paketid=" . $paket_tersedia["paketid"] . "\" style=\"color:blue\">" . $paket_tersedia["paketid"] . "</a>
		</strong> - " . $paket_tersedia["keterangan_paket"] . "</label>";
}
$saran_paket = implode(  "<br />", $data_paket );

// mekanisme terkait dengan pengecualian net item yang disetting di tabel pengurangan_net_item_dealer_modern
include_once "lib/cls_prosedur_khusus_tambahan_diskon.php";
$arr_net_price_baru = prosedur_khusus_tambahan_diskon::pengurangan_net_item_dealer_modern( array($data_dealer["idcust"], $data_dealer["idgrp"]), $data_dealer["disc"] );
$arr_net_price_baru_bertingkat = prosedur_khusus_tambahan_diskon::pengurangan_net_item_dealer_modern_bertingkat($data_dealer["idcust"], $data_dealer["disc"]);

// untuk link ubah net dealer oleh sales
$display_ubah_net_dealer = "none";
if( in_array( $data_dealer["idcust"], explode(",", str_replace("'", "",$arr_dealer_wajib_professional . "," . $arr_dealer_wajib_project) ) ) )  $display_ubah_net_dealer = "inline";

if( is_array($arr_net_price_baru) && in_array( trim($data_item["itemno"]), array_keys( $arr_net_price_baru ) ) )
	$harga_item = $arr_net_price_baru[ trim($data_item["itemno"]) ];	
else
	$harga_item = $data_item["unitprice"];

if( is_array( $arr_net_price_baru_bertingkat ) )
	$harga_item = in_array( trim($data_item["itemno"]), array_keys( $arr_net_price_baru_bertingkat ) ) ? $arr_net_price_baru_bertingkat[ trim($data_item["itemno"]) ] : $harga_item;
else
	$harga_item = (100 - $arr_net_price_baru_bertingkat) * $harga_item / 100;

$script = "";
if( isset($_REQUEST["sp"]) )
	$script = "try{document.getElementById('saran_paket').setAttribute('style', 'display:none')}catch(e){}";

?>