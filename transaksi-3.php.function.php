<?

// load dealer
$_POST["sc"] = "cl";
//$_POST["kode_sales"] = $_SESSION["sales_kode"];
//$_POST["kode_dealer"] = @$_SESSION["kode_dealer"];

include_once "dealer.php";
$rs_dealer = sql::execute( $sql . " and c.order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."' " );
$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("<script>alert('Gagal mendapatkan data dealer!');location.href='transaksi.php';</script>");

//if( $data_dealer["order_id"] == "" ||  $data_dealer["kode_sales"] != $_SESSION["sales_kode"] )
//	$data_dealer["order_id"] = order::orderid( $data_dealer["idcust"], 0 /*$data_dealer["disc"]*/ );

//if( @$_REQUEST["dealer"] =="" &&  @$_SESSION["kode_dealer"] == "")
//	die("<script>alert('Gagal mendapatkan data dealer!');location.href='transaksi.php';</script>");

if( @$_REQUEST["c"] == "" ) goto SkipCommand;
include "transaksi-3.php.command.php";

SkipCommand:

// cek order dealer yg blm dikirimkan
$nominal_order = array("nominal_order" => 0);
if( $data_dealer["order_id"] != "" )
	$nominal_order = order::nominal_order( $data_dealer["idcust"], array( "b.order_id" => array("=",  "'". $data_dealer["order_id"] ."'" ), "b.kirim" => array("=",  "'0'" ) ) );
else die("<script>alert('Mohon lakukan drafting order produk untuk dealer tersebut terlebih dahulu!');location.href='transaksi.php'</script>");

$nominal_order_setelah_diskon = $nominal_order["nominal_order"];

// daftar diskon
unset( $parameter );
$parameter["b.order_id"] = array( "=", "'". $data_dealer["order_id"] ."'" );
$parameter["b.disetujui"] = array( "=", "'1'" );
$rs_daftar_diskon = tambahan_diskon::daftar_tambahan_diskon( $parameter, $data_dealer["order_id"] );

$item_stok = $arr_budget_diskon_tersedia_terkait = array();
$arr_stok = order::cek_cek_stok_item_order( $data_dealer["order_id"] );
while( $stok = sqlsrv_fetch_array( $arr_stok ) )
	$item_stok[ $stok["item_id"] ][ $stok["gudang"] ] = $stok["kuantitas"];

$list_diskon = "";
$counter = 1;

if( sqlsrv_num_rows( $rs_daftar_diskon ) > 0 )
	while( $diskon = sqlsrv_fetch_array( $rs_daftar_diskon ) ){
		
		$readonly = "-readonly";			
			
		$arr_content = tambahan_diskon_persetujuan::detail_order_diskon_single($counter, $diskon,  $data_dealer["idcust"], $data_dealer["order_id"], $nominal_order, $rs_daftar_diskon, "", $readonly, $item_stok , $arr_budget_diskon_tersedia_terkait, $nominal_order_setelah_diskon);
		
		$list_diskon .= $arr_content["content"];
		$item_stok_habis = $arr_content["item_stok_habis"] ? $arr_content["item_stok_habis"] : $item_stok_habis ;
		$arr_budget_diskon_tersedia_terkait = $arr_content["arr_budget_diskon_tersedia_terkait"];
		$nominal_order_setelah_diskon = $arr_content["nominal_order_setelah_diskon"];
		
		if( !$diskon_belum_dialokasikan ) $diskon_belum_dialokasikan = $arr_content["diskon_ada_yg_blm_dialokasikan"];
		
		$counter++;
	}
else
	$list_diskon = "<div style=\"margin:17px 0px 17px 0px\">Tidak ada tambahan diskon!</div>
		<input type=\"button\" name=\"b_tambahan_diskon\" id=\"b_tambahan_diskon\" value=\"Ajukan Tambahan Diskon\" 
			onclick=\"if(confirm('Ajukan tambahan diskon untuk order ini?'))location.href='diskon-pengajuan.php?dealer_id=". $data_dealer["idcust"] ."'\" />";

// cek overlimit kredit
$s_script_overlimit = "";
$overlimit = order::check_overlimit( $_SESSION["kode_dealer"], $nominal_order_setelah_diskon ) ;
if( $overlimit["is_overlimit"] ) 
	$s_script_overlimit = "
			document.getElementById('overlimit-note').style.display = 'block';
			document.getElementById('limit-kredit').innerHTML = '". main::number_format_dec( $overlimit["limit_kredit"] ) ."';
			document.getElementById('piutang+order').innerHTML = '". main::number_format_dec( $overlimit["piutang_plus_order_baru"] ) ."';
		";

$script = "
	try{
		document.getElementById('total-order').innerHTML = '". main::number_format_dec($nominal_order["nominal_order_gross"]) ."';
		document.getElementById('total-diskon-campaign').innerHTML = '". main::number_format_dec($nominal_order["diskon_campaign"]) ."';
		document.getElementById('tambahan-diskon').innerHTML = '". main::number_format_dec($nominal_order["nominal_order"] - $nominal_order_setelah_diskon) ."';
		document.getElementById('total-order-net').innerHTML = '". main::number_format_dec($nominal_order_setelah_diskon) ."';
		". $s_script_overlimit ."
	}catch(e){}
";

?>