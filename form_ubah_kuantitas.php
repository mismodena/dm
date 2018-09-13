<?
include "includes/top_blank.php";

unset($parameter);
$parameter["a.[order_id]"] = array("=", "'". main::formatting_query_string( $_SESSION["order_id"] ) ."'");
$parameter["b.[item_seq]"] = array("=", "'". main::formatting_query_string( $_REQUEST["item_seq"] ) ."'");
$rs_item_order = sql_dm::browse_cart( $parameter );

if( sqlsrv_num_rows( $rs_item_order ) <= 0 ) die("<script>alert('Gagal mendapatkan data item!');parent.TINY.box.hide();</script>");

$item_order = sqlsrv_fetch_array( $rs_item_order );

?>
<script>
function proses(){
	var  qty = parseFloat( document.getElementById('t_kuantitas_baru').value );		
	location.href='transaksi-2.php?c=ubah_kuantitas&item_seq=<?=$_REQUEST["item_seq"]?>&qty='+qty;
}
</script>
<style>
input[type=button]{
	width:57px;
}
</style>
<h3>Perubahan Kuantitas!</h3>
<div style="margin-top:-11px">Isikan kuantitas baru!</div>
<input type="number" name="t_kuantitas_baru" id="t_kuantitas_baru" value="<?=$item_order["kuantitas"]?>" onfocus="javascript:select()" style="width:100%" />
<div style="margin:7px 0px 7px 0px; font-weight:bold">
<?=$item_order["item_nama"]?><br />
<?=$item_order["kuantitas"]?> Unit x Rp<?=main::number_format_dec( $item_order["harga"] )?>
</div>
<div style="text-align:center; margin:13px 0px 0px 0px">
	<input type="button" name="b_batal" id="b_batal" value="Batal" onclick="parent.TINY.box.hide()" /> | 
	<input type="button" name="b_ubah" id="b_ubah" value="Ubah" onclick="proses()" />
</div>
