<?
if( @$_REQUEST["dealer"] =="" &&  @$_SESSION["kode_dealer"] == ""){
	echo "<script>location.href='transaksi.php'</script>";
	exit;
}

if( @$_REQUEST["c"] == "" ) goto SkipCommand;

if( @$_REQUEST["c"] == "tambah_item" ){
	unset($_SESSION['pilih_cn']);
	// cek untuk dealer modern berdasarkan PO
	if( @$_SESSION["t_po"] != "" && @$_REQUEST["sc"] != "pass" ){
		
		$_REQUEST["t_po"] = $_SESSION["t_po"];
		include_once "transaksi-2.php.po-cek.php";
		
		$data_po = cek_po("", $_REQUEST["item"], true);
		if ($data_po != "") {
			$data_po = "
				<style>.tombol-tutup-detail-order-po-referensi{display:none}</style>
				<h3>Item yang anda cari sudah ada di daftar PO ". $_SESSION["t_po"] ."</h3>
			" . $data_po . "
			<div style=\"margin:17px 0px 27px 0px; float:left; width:100%\">
				<span class=\"tanda-seru\">!</span>Klik tombol \"Lanjut\" untuk melanjutkan order item, atau \"Batal\" untuk membatalkan order item.<br  />
				<input type=\"button\" name=\"b_batal\" id=\"b_batal\" value=\"Batal\" style=\"background-color:red; color:white; float:left; width:49%\" onclick=\"location.href='". $page ."'\" />
				<input type=\"button\" name=\"b_lanjut_proses\" id=\"b_lanjut_proses\" value=\"Lanjut\" style=\"background-color:green; color:white; float:right; width:49%\" onclick=\"location.href='". $page ."?sc=pass&". http_build_query( $_REQUEST )  ."'\" />
			</div>
			";
			goto SkipCommand;
		}

	}
	
	$arr_data["order_id"] = "'" . main::formatting_query_string( $_SESSION["order_id"]) . "'";
	$arr_data["user_id"] = "'" . main::formatting_query_string( $_SESSION["sales_id"]) . "'";
	$arr_data["item_seq"] = "case when (select isnull(max(item_seq)+1, 0) from order_item where 
			order_id = '". main::formatting_query_string( $_SESSION["order_id"] ) ."' and 
			user_id = '". main::formatting_query_string( $_SESSION["sales_id"] ) ."') = 0 then 1
			else
			(select max(item_seq)+1 from order_item where 
			order_id = '". main::formatting_query_string( $_SESSION["order_id"] ) ."' and 
			user_id = '". main::formatting_query_string( $_SESSION["sales_id"] ) ."') end";
	$arr_data["item_id"] = "'". main::formatting_query_string( $_REQUEST["item"] ) ."'";
	$arr_data["harga"] = "'". main::formatting_query_string( $_REQUEST["harga"] ) ."'";
	$arr_data["kuantitas"] = "'". main::formatting_query_string( $_REQUEST["qty"] ) ."'";
	$arr_data["paketid"] = "'". main::formatting_query_string( $_REQUEST["paket"] ) ."'";
	$arr_data["gudang"] = "'". main::formatting_query_string( $_REQUEST["gudang"] ) ."'";
	
	sql_dm::insert_order_item( $arr_data );
	
	if( $_REQUEST["paket"] != "" ) 
		$dm = new perhitungan_otomatis($_SESSION["order_id"], $_SESSION["sales_id"], $_REQUEST["paket"]);

	echo "<script>location.href='transaksi-2.php'</script>";
}
exit;

SkipCommand:
// load dealer
$_POST["sc"] = "cl";
$_POST["kode_sales"] = $_SESSION["sales_kode"];
if (@$_REQUEST["dealer"] != "") 
	$_SESSION["kode_dealer"] = @$_REQUEST["dealer"];
$_POST["kode_dealer"] = $_SESSION["kode_dealer"];
include_once "dealer.php";
$rs_dealer = sql::execute( $sql );
$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("<script>location.href='transaksi.php'</script>");

if( $data_dealer["order_id"] == "" ||  $data_dealer["kode_sales"] != $_SESSION["sales_kode"] )
	$data_dealer["order_id"] = order::orderid( $data_dealer["idcust"], 0 /*$data_dealer["disc"]*/ );

// cek order
if( $data_dealer["order_id"] == "" ) die("<script>location.href='transaksi.php'</script>");

// cek po, untuk dealer modern
if( in_array( trim( $data_dealer["idgrp"] ), explode(",", str_replace("'", "", $arr_dealer_modern) ) )  ) {
	if( @$_SESSION["t_po"] == "" && @$_REQUEST["po"] == "" ){
		$string_entri_po = "<div style=\"margin:0px 0px 17px 0px; float:left; width:100%\"><span class=\"tanda-seru\">!</span>Khusus dealer modern, masukkan nomor PO <input type=\"text\" name=\"t_po\" id=\"t_po\" style=\"width:100%\" onblur=\"simpan_session(this)\" /></div>";
	}else{
		if( @$_REQUEST["po"] != "" ) $_SESSION["t_po"] = $_REQUEST["po"];
	}
}

// cek data gudang spesifik per user
$arr_gudang_user = order::daftar_item_gudang_spesifik_utk_user();

// mekanisme terkait dengan pengecualian net item yang disetting di tabel pengurangan_net_item_dealer_modern
include_once "lib/cls_prosedur_khusus_tambahan_diskon.php";
$arr_net_price_baru = prosedur_khusus_tambahan_diskon::pengurangan_net_item_dealer_modern( array($data_dealer["idcust"], $data_dealer["idgrp"]), $data_dealer["disc"] );
$arr_net_price_baru_bertingkat = prosedur_khusus_tambahan_diskon::pengurangan_net_item_dealer_modern_bertingkat($data_dealer["idcust"], $data_dealer["disc"]);

// untuk link ubah net dealer oleh sales
$display_ubah_net_dealer = "none";
if( in_array( $data_dealer["idcust"], explode(",", str_replace("'", "", $arr_dealer_wajib_professional . "," . $arr_dealer_wajib_project) ) ) )  $display_ubah_net_dealer = "inline";

// cari item
$data_item = "<div>Mohon cari item terlebih dahulu!</div>";
if( @$_REQUEST["item"] != ""){
	$rs_item = order::daftar_item( @$_SESSION["disc_dealer"] != "" ? $_SESSION["disc_dealer"] : $data_dealer["disc"], @$_REQUEST["cbx"] );
	$item_template = file_get_contents("template/item.html");
	$counter = 1;
	$data_item = "";
	while( $item = sqlsrv_fetch_array( $rs_item ) ){
		//if( $item["qty_lokal"] <= 0 ) continue;
		
		if( is_array($arr_net_price_baru) && in_array( trim($item["itemno"]), array_keys( $arr_net_price_baru ) ) )	$item["unitprice"] = $arr_net_price_baru[ trim($item["itemno"]) ];

		if( is_array( $arr_net_price_baru_bertingkat ) )
			$item["unitprice"] = in_array( trim($item["itemno"]), array_keys( $arr_net_price_baru_bertingkat ) ) ? $arr_net_price_baru_bertingkat[ trim($item["itemno"]) ] : $item["unitprice"];
		else
			$item["unitprice"] = (100 - $arr_net_price_baru_bertingkat) * $item["unitprice"] / 100;
		
		$arr["#kelas#"] = $counter % 2 == 0 ? 1 : 2;
		$arr["#order_id#"] = $data_dealer["order_id"];
		$arr["#item#"] = $item["itemno"];
		$arr["#harga#"] = main::number_format_dec($item["unitprice"]);
		$arr["class=\"sembunyikan\""] = "";
		$arr["#harga_unformatted#"] = $item["unitprice"];
		$arr["#itemdesc#"] = $item["desc"];
		$arr["#stok_lokal#"] = $item["qty_lokal"];
		$arr["#stok_pusat#"] = $item["qty_pst"];		
		$arr["#gudang_lokal#"] = $_SESSION["cabang"];
		$arr["#qty#"] = $item["qty_lokal"] > 0 || $item["qty_pst"] > 0 ? 1 : 0;
		$arr["#disabled#"] = $item["qty_lokal"] <= 0 && $item["qty_pst"] <= 0 ? "disabled" : "";
		$arr["#disabled_lokal#"] = $item["qty_lokal"] > 0 ? "" : "disabled";
		$arr["#disabled_pusat#"] = $item["qty_pst"] > 0 ? ( ( count($arr_gudang_user) > 0 && !in_array("GDGPST", $arr_gudang_user) ) ? "disabled" : "" ) : "disabled";
		
		$data_paket = array();
		$rs_paket_tersedia = order::browse_paket_per_item( $item["itemno"] );		
		if( sqlsrv_num_rows($rs_paket_tersedia) > 0 ){
			while( $paket_tersedia = sqlsrv_fetch_array( $rs_paket_tersedia ) )
				$data_paket[ $paket_tersedia["paketid"] ] = "<input type=\"radio\" name=\"r_". $item["itemno"] ."\" id=\"r_". $item["itemno"] ."_". $paket_tersedia["paketid"] ."\"value=\"". $paket_tersedia["paketid"] ."\" />&nbsp;&nbsp;&nbsp;
				<label for=\"r_". $item["itemno"] ."_". $paket_tersedia["paketid"] ."\"><strong><a href=\"paket-detail.php?paketid=" . $paket_tersedia["paketid"] . "\" style=\"color:blue\">" . $paket_tersedia["paketid"] . "</a>
				</strong> - " . $paket_tersedia["keterangan_paket"] . "</label>";
		}
		$arr["#saranpaket#"] = implode(  "<br />", $data_paket );
	
		$data_item .= str_replace( array_keys( $arr ), array_values( $arr ), $item_template );
		$counter++;
	}
	
	$data_item = "<div>Sebanyak ". ($counter-1)  ." data item ditemukan.<br /><strong>Note :</strong> harga tertera adalah harga net dealer (belum termasuk diskon campaign apabila ada).</div>" . $data_item;
	
}

?>