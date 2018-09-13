<?
include "includes/top_blank.php";

if( @$_REQUEST["paketid"] == "" ) goto SkipPaket;

echo "<a href=\"javascript:history.back()\" style=\"color:blue\">Kembali ke halaman semula</a>";

$rs_paket_detail = sql_dm::cari_paket( array( "b.paketid" => array("=", "'". main::formatting_query_string($_REQUEST["paketid"]) ."'") ), true );
$paket_detail = sqlsrv_fetch_array( $rs_paket_detail );
$arr_data[] = "<h3>Campaign " . $paket_detail["paketid"] . "</h3>";
$arr_data[] = $paket_detail["keterangan_paket"];
echo implode("", $arr_data);
unset($arr_data);

echo "<h3>Area Campaign</h3>";
$rs_paket_area = sql_dm::browse_paket_area( array( "a.paketid" => array("=", "'". main::formatting_query_string($_REQUEST["paketid"]) ."'") ) );
while( $paket_area = sqlsrv_fetch_array( $rs_paket_area ) )
	$arr_data[] = "<li>" . $paket_area["paket_area"] . "</li>";
echo "<ul>" . ( is_array( @$arr_data ) ? implode("", $arr_data) : "" ) . "</ul>";
unset($arr_data);

echo "<h3>Detail Campaign</h3>";
$rs_paket_parameter = sql_dm::browse_paket_parameter( array( "b.paketid" => array("=", "'". main::formatting_query_string($_REQUEST["paketid"]) ."'") ), true );
while( $paket_parameter = sqlsrv_fetch_array( $rs_paket_parameter ) )
	if( $paket_parameter["keterangan_paket_parameter"] != "" )
		$arr_data[] = "<li>" . $paket_parameter["keterangan_paket_parameter"] . "</li>";
echo "<ul>" . implode("", $arr_data) . "</ul>";
unset($arr_data);

echo "<h3>Item Campaign</h3>";
$rs_paket_item = sql_dm::browse_paket_item( array( "b.paketid" => array("=", "'". main::formatting_query_string($_REQUEST["paketid"]) ."'") ), true );
while( $paket_item = sqlsrv_fetch_array( $rs_paket_item ) )
	if( $paket_item["item"] != "" )
		$arr_data[] = "<li>" . $paket_item["item"] . "</li>";
echo "<ul>" . implode("", $arr_data) . "</ul>";
	
goto Akhir;

SkipPaket:
	echo "Paket tidak diketahui!<br /><a href=\"javascript:history.back()\" style=\"color:blue\">Kembali ke halaman berikutnya.</a>";
Akhir:

include "includes/bottom.php";
?>