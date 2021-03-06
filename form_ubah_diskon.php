<?
include "includes/top_blank.php";

unset($parameter);
$parameter["a.[order_id]"] = array("=", "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'");
$parameter["b.[item_seq]"] = array("=", "'". main::formatting_query_string( $_REQUEST["item_seq"] ) ."'");
$rs_item_order = sql_dm::browse_cart( $parameter );

if( sqlsrv_num_rows( $rs_item_order ) <= 0 ) die("<script>alert('Gagal mendapatkan data item!');parent.TINY.box.hide();</script>");

$item_order = sqlsrv_fetch_array( $rs_item_order );

$diskon_maksimal_rupiah = $item_order["diskon_default"];
$diskon_maksimal_persen = ( $item_order["diskon_default"] * 100 ) / ( $item_order["harga"] * $item_order["kuantitas"] );

?>
<script>
function resetting(){
	if( !confirm('Reset nilai diskon?') ) return false;
	var opsi = document.getElementById('r1').checked ? document.getElementById('r1').value : document.getElementById('r2').value;
	location.href='transaksi-2.php?c=reset_diskon&order_id=<?=$_REQUEST["order_id"]?>&item_seq=<?=$_REQUEST["item_seq"]?>&opsi='+opsi;
}
function proses(){
	var  diskon = parseFloat( document.getElementById('t_diskon_baru').value.replace(/,/gi, '') );

	if( diskon < 100 && diskon > <?=$diskon_maksimal_persen?> ) { alert('Diskon tidak boleh melebihi <?=$diskon_maksimal_persen?>%!'); return false;}
	if( diskon >= 100 && diskon > <?=$diskon_maksimal_rupiah?> ) { alert('Diskon tidak boleh melebihi Rp<?=$diskon_maksimal_rupiah?>!'); return false;}
	if( !confirm('Ubah diskon?') ) return false;	
	
	var opsi = document.getElementById('r1').checked ? document.getElementById('r1').value : document.getElementById('r2').value;
	location.href='transaksi-2.php?c=ubah_diskon&order_id=<?=$_REQUEST["order_id"]?>&item_seq=<?=$_REQUEST["item_seq"]?>&diskon='+diskon+'&opsi='+opsi;
}
</script>
<style>
input[type=button]{
	width:57px;
}
</style>
<h3>Perubahan Diskon!</h3>
<div style="margin-top:-11px">Isikan nilai diskon baru!</div>
<input type="text" name="t_diskon_baru" id="t_diskon_baru" value="<?=main::number_format_dec( $item_order["diskon"] )?>" onfocus="fokusinput(this)" onblur="unfokusinput(this)"  style="width:100%" />
<div style="margin:7px 0px 7px 0px; font-weight:bold">
<?=$item_order["item_nama"]?><br />
<?=$item_order["kuantitas"]?> Unit x Rp<?=main::number_format_dec( $item_order["harga"] )?>
</div>
<div>
	<strong>Note:</strong><br />Isikan nilai diskon dalam satuan PERSEN ataupun RUPIAH tanpa karakter % dan Rp.<br />Untuk desimal, gunakan karakter titik (.)<br />
	<strong>Diskon maksimal:<br /><?=$diskon_maksimal_persen?>% atau Rp<?=main::number_format_dec( $diskon_maksimal_rupiah )?>.</strong>
</div>
<div style="padding-top:11px">Opsi perubahan diskon:<br />	
	<input type="radio" name="radiox" id="r2" value="2" checked /><label for="r2">Untuk semua item dalam campaign</label><br />
	<input type="radio" name="radiox" id="r1" value="1" /><label for="r1">Hanya untuk item ini saja</label>
</div>
<div style="text-align:center; margin:13px 0px 0px 0px">
	<input type="button" name="b_batal" id="b_batal" value="Batal" onclick="parent.TINY.box.hide()" /> | 
	<input type="button" name="b_reset" id="b_reset" value="Reset" onclick="resetting()" /> | 
	<input type="button" name="b_ubah" id="b_ubah" value="Ubah" onclick="proses()" />
</div>
