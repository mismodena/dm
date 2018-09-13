<?
include "includes/top.php";
?>
<div style="float:right"><img src="images/dealer.png" border="none" /></div>
<span class="tanda-seru">!</span>Anda akan melakukan <strong>pengajuan tambahan diskon (di luar campaign)</strong> untuk dealer. <br /><br />
Cari dealer berdasarkan kode ACCPAC atau sebagian nama dealer. 
Kosongkan isian dan klik tombol "Cari" untuk memunculkan semua dealer.<br />
Klik di tombol "Pilih" pada masing-masing baris data dealer untuk melanjutkan pengajuan tambahan diskon (di luar campaign) untuk dealer.
<br /><br />
<input type="text" name="t_dealer" id="t_dealer" value="<?=@$_REQUEST["t_dealer"]?>" /><input type="button" id="b_cari" value="Cari" onclick="cari_dealer(document.getElementById('t_dealer'))" />
<div style="padding:17px 0px 17px 0px;">
	<div style="margin-bottom:17px">Sebanyak <?=$jumlah_data?> data ditampilkan.</div>
	<?=@$data_dealer?>
</div>
<?
include "includes/bottom.php";
?>