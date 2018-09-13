<?
include "includes/top_blank.php";

// load dealer
$_POST["sc"] = "cl";
$_POST["kode_sales"] = $_SESSION["sales_kode"];
if (@$_REQUEST["dealer"] != "") 
	$_SESSION["kode_dealer"] = @$_REQUEST["dealer"];
$_POST["kode_dealer"] = $_SESSION["kode_dealer"];
include_once "dealer.php";
$rs_dealer = sql::execute( $sql );
$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("<script>location.href='transaksi.php'</script>");

if( @$_REQUEST["t_net_baru"] != "" ){
	$_SESSION["disc_dealer"] = $_REQUEST["t_net_baru"];
	die("<script>parent.location.reload()</script>");
}

?>
<script>
function proses(){
	var  net_baru = parseFloat( document.getElementById('t_net_baru').value );		
	if( isNaN(net_baru) || net_baru < 0 || net_baru > <?=$data_dealer["disc"] ?> ) net_baru = <?=$data_dealer["disc"]?>;
	location.href='form_ubah_net.php?t_net_baru='+ net_baru;
}
</script>
<style>
input[type=button]{
	width:57px;
}
</style>
<h3>Perubahan NET Diskon!</h3>
<div style="margin-top:-11px">Isikan persentase net baru!</div>
<input type="number" name="t_net_baru" id="t_net_baru" value="<?=@$_SESSION["disc_dealer"] != "" ? $_SESSION["disc_dealer"] : $data_dealer["disc"]?>" onfocus="javascript:select()" style="width:100%" />
<div style="margin:7px 0px 7px 0px; font-weight:normal">
<ul style="margin-left:-20px">
	<li>Isikan <strong>tanpa</strong> tambahan persen (%)</li>
	<li>Gunakan tanda titik (.) untuk pemisah nilai desimal</li>
	<li>Maksimal persentase net diskon adalah <strong><?=$data_dealer["disc"]?></strong>%</li>
</ul>
</div>
<div style="text-align:center; margin:13px 0px 0px 0px">
	<input type="button" name="b_batal" id="b_batal" value="Batal" onclick="parent.TINY.box.hide()" /> | 
	<input type="button" name="b_reset" id="b_reset" value="Reset" onclick="document.getElementById('t_net_baru').value=<?=$data_dealer["disc"]?>" /> | 
	<input type="button" name="b_ubah" id="b_ubah" value="Ubah" onclick="proses()" />
</div>
