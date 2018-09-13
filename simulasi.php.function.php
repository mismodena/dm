<?
$GLOBALS["database_accpac"] = "sgtdat";
define("__MODE_SIMULASI__", true);

if( @$_REQUEST["paketid"] == "" ) die("<script>alert('Paket campaign tidak dikenal!');window.close()</script>");

$sales_id = $_REQUEST["userid"];
$cabang = "GDGPST";
$dealer = "XXXXXXXXXX";

include "lib/cls_simulasi_campaign.php";
$_SESSION["order_id"] = $sales_id;
$_SESSION["sales_id"] = $sales_id;
$_SESSION["kode_dealer"] = $dealer;


if( @$_REQUEST["c"] == "" ) goto SkipCommand;

if( $_REQUEST["c"] == "tambah_item" ){
	$arr_parameter["order_id"] = "'" . main::formatting_query_string( $sales_id) . "'";
	$arr_parameter["user_id"] = "'" . main::formatting_query_string( $sales_id) . "'";
	$arr_parameter["item_seq"] = "case when (select isnull(max(item_seq)+1, 0) from order_item where 
			order_id = '". main::formatting_query_string( $sales_id ) ."' and 
			user_id = '". main::formatting_query_string( $sales_id ) ."') = 0 then 1
			else
			(select max(item_seq)+1 from order_item where 
			order_id = '". main::formatting_query_string( $sales_id ) ."' and 
			user_id = '". main::formatting_query_string( $sales_id ) ."') end";
	$arr_parameter["item_id"] = "'". main::formatting_query_string( $_REQUEST["item"] ) ."'";
	$arr_parameter["harga"] = "'". main::formatting_query_string( $_REQUEST["harga"] ) ."'";
	$arr_parameter["kuantitas"] = "'". ( $_REQUEST["qty"] == "" ? 1 : main::formatting_query_string( $_REQUEST["qty"] ) ) ."'";
	$arr_parameter["paketid"] = @$_REQUEST["sc"] == "non_paket" ? "NULL" : "'". main::formatting_query_string( $_REQUEST["paketid"] ) ."'";

	sql_dm::insert_order_item( $arr_parameter );

}elseif( $_REQUEST["c"] == "hapus_item" ){
	sql_dm::hapus_item( $_REQUEST["item_seq"] );

}elseif( $_REQUEST["c"] == "hapus_campaign" ){
	sql_dm::hapus_campaign( $_REQUEST["item_seq"] );

}elseif( $_REQUEST["c"] == "ubah_kuantitas" ){
	sql_dm::ubah_kuantitas( $_REQUEST["item_seq"], $_REQUEST["qty"] );

}elseif( $_REQUEST["c"] == "reset_simulasi" ){
	// reset simulasi
	sql_dm::hapus_order( $sales_id );

	// entri simulasi baru
	unset( $arr_parameter );
	$arr_parameter["order_id"] = "'" . main::formatting_query_string( $sales_id ) . "'";
	$arr_parameter["user_id"] = "'" . main::formatting_query_string( $sales_id ) . "'";
	$arr_parameter["dealer_id"] = "'" . main::formatting_query_string( $dealer ) . "'";
	$arr_parameter["gudang"] = "'" . main::formatting_query_string( $cabang ) . "'";
	$arr_parameter["diskon"] = 0;
	simulasi_campaign::insert_order_simulasi( $arr_parameter );	
	
}elseif( $_REQUEST["c"] == "item_non_campaign" ){
	$data_dealer["disc"] = 0;
	$counter = 1;
	$item_non_campaign = "";
	
	$rs_item = order::daftar_item( $data_dealer["disc"] );
	while( $item = sqlsrv_fetch_array( $rs_item ) ){
		unset($arr_item);
		$arr_item["#kelas#"] = $counter % 2 == 0 ? 1 : 2;
		
		$template = file_get_contents("template/simulasi-item.html");
		
		$rs_nama_harga = simulasi_campaign::item_info( $item["itemno"] );
		$nama_harga = sqlsrv_fetch_array( $rs_nama_harga );

		$arr_item["#item#"] = $item["itemno"];
		$arr_item["#item_nama#"] = $nama_harga["desc"];
		$arr_item["#item_harga#"] = main::number_format_dec($nama_harga["unitprice"]);
		$arr_item["simulasi("] = "simulasi_non_paket(";
		
		$item_non_campaign .= str_replace( array_keys( $arr_item ), array_values( $arr_item ), $template );
		$counter++;
	}
	echo "<script>
		parent.document.getElementById('list-daftar-item-non-campaign').innerHTML = '". str_replace(array("'", "\r\n"), array("\'", ""), $item_non_campaign) ."';
	</script>";
	exit;
}
unset( $_SESSION["order_id"] );
echo "<script>location.href='simulasi.php?paketid=". $_REQUEST["paketid"] ."&userid=". $sales_id ."'</script>";
exit;

SkipCommand:

// load keterangan simulasi_paket
unset($arr_parameter);
$arr_parameter["b.paketid"] = array("=", "'". main::formatting_query_string( $_REQUEST["paketid"] ) ."'");
$rs_simulasi_paket = sql_dm::cari_paket( $arr_parameter, true ); 
if( sqlsrv_num_rows( $rs_simulasi_paket ) <= 0 ) die("<script>alert('Paket campaign sudah tidak aktif ataupun sudah tidak berlaku lagi!');window.close()</script>");
$simulasi_paket = sqlsrv_fetch_array( $rs_simulasi_paket );

// load daftar item campaign
$rs_item = sql_dm::browse_paket_per_item( "%", $simulasi_paket["paketid"], true );
$item_campaign = "";
$counter = 1;
while( $item = sqlsrv_fetch_array( $rs_item ) ){
	
	unset($arr_item);
	$arr_item["#kelas#"] = $counter % 2 == 0 ? 1 : 2;
		
	if( $item["mode"] == "1" ){
		$template = file_get_contents("template/simulasi-item.html");
		$rs_nama_harga = simulasi_campaign::item_info( $item["item"] );
		$nama_harga = sqlsrv_fetch_array( $rs_nama_harga );

		$arr_item["#item#"] = $item["item"];
		$arr_item["#item_nama#"] = $nama_harga["desc"];
		$arr_item["#item_harga#"] = main::number_format_dec($nama_harga["unitprice"]);
		
	}else{
		$template = file_get_contents("template/simulasi-subkategori.html");
		$rs_nama = simulasi_campaign::sub_kategori_info( $item["item"] );
		$nama = sqlsrv_fetch_array( $rs_nama );

		$arr_item["#item#"] = $item["item"];
		$arr_item["#item_nama#"] = $nama["brand"] . " - " . strtoupper($nama["sub_kategori"]);
		$arr_item["#item_harga#"] = "";
		
	}
	
	$item_campaign .= str_replace( array_keys( $arr_item ), array_values( $arr_item ), $template );
	$counter++;
}

$dm = new perhitungan_otomatis($sales_id, $sales_id);
include_once "transaksi-2-order.php";

$data_order = $data_order == "" ? "Pilih item di samping dan klik tombol \"Beli\" untuk melakukan simulasi!" : $data_order;

?>