<?

if( @$_REQUEST["sub_kategoriid"] == "" )
	die("Sub kategori ID tidak boleh kosong ya!");
	
include "includes/top_blank.php";
include "lib/cls_simulasi_campaign.php";

$rs_item = simulasi_campaign::item_info_dari_subkategori( $_REQUEST["sub_kategoriid"] );
$template = file_get_contents("template/simulasi-item.html");
$item_campaign = "";

while( $item = sqlsrv_fetch_array( $rs_item ) ){
	$rs_nama_harga = simulasi_campaign::item_info( $item["itemno"] );
	$nama_harga = sqlsrv_fetch_array( $rs_nama_harga );

	$arr_item["#item#"] = $item["itemno"];
	$arr_item["#item_nama#"] = $nama_harga["desc"];
	$arr_item["#item_harga#"] = main::number_format_dec($nama_harga["unitprice"]);
	
	$item_campaign .= str_replace( array_keys( $arr_item ), array_values( $arr_item ), $template );
}

echo $item_campaign . "<style>body,html,div{font-size:12px !important}</style>";

include "includes/bottom.php";
?>