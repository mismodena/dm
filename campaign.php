<?
include "includes/top.php";
include "lib/cls_campaign.php";

$s_campaign = "";

echo "Berikut adalah campaign yang sedang berlaku.<br /><br />
Cari item dalam campaign <input type=\"text\" name=\"item\" id=\"item\" value=\"". @$_REQUEST["item"] ."\" /><input type=\"button\" onclick=\"cari_item()\" value=\"Cari\" id=\"b_cari\" />
<ul style=\"margin-left:-20px;\">";

// daftar campaign
$item = "";
if( @$_REQUEST["item"] != "" ) $item = " and campaignid in (select campaignid from paket_item a, ". $database_accpac ."..icitem b where a.item = b.itemno and b.[desc] like '%". $_REQUEST["item"] ."%')";
$arr_parameter["campaignid"] = array("", " in (select campaignid from paket where aktif_paket=1 ". $item .") ") ;
$arr_parameter["periodeid"] = array("", " in (select periodeid from periode where getdate()>= awal and getdate()<=akhir ) ") ;
$arr_parameter["aktif_campaign"] = array( "=", 1 );
$rs_campaign = campaign::daftar_campaign( $arr_parameter );
while( $campaign = sqlsrv_fetch_array( $rs_campaign ) ){
	$s_campaign .= "<li><strong>" . $campaign["campaign"] . "</strong> : " . $campaign["keterangan_campaign"] . "<ul style=\"margin-left:-20px;\">";
	
	// daftar paket
	$item = "";
	if( @$_REQUEST["item"] != "" ) $item = " and paketid in (select paketid from paket_item a, ". $database_accpac ."..icitem b where a.item = b.itemno and b.[desc] like '%". $_REQUEST["item"] ."%')";
	$arr_parameter = array("campaignid" => array("=", "'". main::formatting_query_string($campaign["campaignid"]) ."' ". $item ." ") );	
	$rs_paket = campaign::daftar_paket( $arr_parameter );
	while( $paket = sqlsrv_fetch_array( $rs_paket ) )
		$s_campaign .= "<li><a href=\"paket-detail.php?paketid=". $paket["paketid"] ."\" style=\"color:blue\">" . $paket["paketid"] . "</a> : " . $paket["keterangan_paket"] . "</li>";
	
	$s_campaign .= "</ul></li>";
}

echo $s_campaign . "</ul>";

include "includes/bottom.php";
?>