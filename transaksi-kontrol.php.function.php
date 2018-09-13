<?

//if( @$_REQUEST["dealer_id"] == "" ) die("<script>location.href='diskon.php'</script>");

// load dealer
$_POST["sc"] = "cl";
//$_POST["kode_sales"] = $_SESSION["sales_kode"];
//$_POST["kode_dealer"] = $_REQUEST["dealer_id"];
$_POST["pengajuan_diskon"] = 1;

include_once "dealer.php";
$rs_dealer = sql::execute( $sql . " and c.order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."' " );
$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("<script>location.href='diskon.php'</script>");

//if( $data_dealer["order_id"] == "" ||  $data_dealer["kode_sales"] != $_SESSION["sales_kode"] )
//	$data_dealer["order_id"] = order::orderid( $data_dealer["idcust"], 0 /*$data_dealer["disc"]*/ );
$data_dealer["order_id"] = $_REQUEST["order_id"];

// COMMAND
if( @$_REQUEST["c"] == "" ) goto SkipCommand;

include "diskon-pengajuan.php.command.php";
	
SkipCommand:

// cek order dealer yg blm dikirimkan
$nominal_order = array("nominal_order" => 0);
if( $data_dealer["order_id"] != "" )
	$nominal_order = order::nominal_order( $data_dealer["idcust"], array( "b.order_id" => array("=",  "'". $data_dealer["order_id"] ."'" ), "b.kirim" => array("=",  "'0'" ) ) );
else die("<script>alert('Mohon lakukan drafting order produk untuk dealer tersebut terlebih dahulu!');location.href='transaksi.php'</script>");

$nominal_order_setelah_diskon = $nominal_order["nominal_order"];

// daftar diskon
$rs_daftar_diskon = tambahan_diskon::daftar_tambahan_diskon( array( "b.order_id" => array( "=", "'". $data_dealer["order_id"] ."'" ) ), $data_dealer["order_id"] );

$list_diskon = "";
$counter = 1;
$aktifkan_tombol_kirim_accpac = $item_sudah_dipilih_utk_diskon_wajib_pilih_item = true;
$aktifkan_tombol_kirim = $diskon_ada_yg_belum_dikirimkan = $item_stok_habis = $diskon_belum_dialokasikan = false;

$item_stok = $arr_budget_diskon_tersedia_terkait = array();
$arr_stok = order::cek_cek_stok_item_order( $data_dealer["order_id"] );
while( $stok = sqlsrv_fetch_array( $arr_stok ) )
	$item_stok[ $stok["item_id"] ][ $stok["gudang"] ] = $stok["kuantitas"];

if( sqlsrv_num_rows( $rs_daftar_diskon ) > 0 )
	while( $diskon = sqlsrv_fetch_array( $rs_daftar_diskon ) ){		
		
		$arr_tambahan_diskon[] = $diskon["diskon_id"];
		
		$readonly = "";
		if( in_array( $diskon["status_persetujuan"], array(1, 2, 4) ) || $readonly )
			$readonly = "-readonly";			
		else
			$diskon_ada_yg_belum_dikirimkan = true;

		$arr_content = tambahan_diskon_persetujuan::detail_order_diskon_single($counter, $diskon,  $data_dealer["idcust"], $data_dealer["order_id"], $nominal_order, $rs_daftar_diskon, "", $readonly, $item_stok , $arr_budget_diskon_tersedia_terkait, $nominal_order_setelah_diskon);
		$list_diskon .= $arr_content["content"] . ( $diskon["status_persetujuan"] == 1 ? "<input type=\"button\" name=\"b_kirim_ulang_". $diskon["diskon_id"] ."\" id=\"b_kirim_ulang_". $diskon["diskon_id"] ."\"  value=\"Kirim email persetujuan ulang\" style=\"width:100%\" />" : "" );
		$item_stok_habis = $arr_content["item_stok_habis"] ? $arr_content["item_stok_habis"] : $item_stok_habis ;
		$arr_budget_diskon_tersedia_terkait = $arr_content["arr_budget_diskon_tersedia_terkait"];
		$nominal_order_setelah_diskon = $arr_content["nominal_order_setelah_diskon"];
		
		if( $readonly == "" && !$diskon_belum_dialokasikan ) $diskon_belum_dialokasikan = $arr_content["diskon_ada_yg_blm_dialokasikan"];
		
		if( $readonly == "" && !$arr_content["item_sudah_dipilih_utk_diskon_wajib_pilih_item"] && $diskon["wajib_pilih_item"] == 1 ) $item_sudah_dipilih_utk_diskon_wajib_pilih_item = false;
		
		@$script .= @$arr_content["script_dikembalikan"];
		
		$counter++;
	}
else
	$list_diskon = "<style>#b_hitung, #b_kirim{display:none}</style>";

if( $diskon_ada_yg_belum_dikirimkan ){
	$aktifkan_tombol_kirim = true;
	if( !$item_sudah_dipilih_utk_diskon_wajib_pilih_item )
		$aktifkan_tombol_kirim = false;
}

// cek ulang nilai stok tersedia vs unit pembelian	
unset($arr_stok_item);
$rs_cek_stok = order::cek_cek_stok_item_order($data_dealer["order_id"]);
while( $cek_stok = sqlsrv_fetch_array($rs_cek_stok) )
	@$arr_stok_item[ $cek_stok["item_id"] ] = $cek_stok["kuantitas"];

$string_peringatan_stok_kosong = order::cek_stok_kosong( $data_dealer, $arr_stok_item );

if( $string_peringatan_stok_kosong != "" ) $aktifkan_tombol_kirim_accpac = $aktifkan_tombol_kirim = false;
if( $diskon_belum_dialokasikan ) $aktifkan_tombol_kirim = false;
?>