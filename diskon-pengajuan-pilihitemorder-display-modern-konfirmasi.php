<?
include "includes/template_top.php";
?>
<a href="diskon-pengajuan.php?dealer_id=<?=$_REQUEST["dealer_id"]?>&order_id=<?=$_REQUEST["order_id"]?>">Kembali ke halaman pengajuan tambahan diskon</a><br /><br />
<input type="hidden" name="dealer_id" value="<?=$_REQUEST["dealer_id"]?>" />
<input type="hidden" name="order_id" value="<?=$_REQUEST["order_id"]?>" />
<input type="hidden" name="diskonid" value="<?=$_REQUEST["diskonid"]?>" />
<span class="tanda-seru">!</span>Anda sedang pemilihan item ORDER untuk pengajuan tambahan diskon (di luar campaign) dengan info sebagai berikut :
<div style="width:100%; background-color:#EEE; border:solid #CCC 1px; margin-top:7px; margin-bottom:7px">
	<div style="padding:17px">
		Sales : <?=$data_dealer["nama_lengkap"]?><br />
		No. Order : <?=$_REQUEST["order_id"]?><br />
		Kode Dealer : <?=$data_dealer["idcust"]?><br />
		Nama Dealer : <?=$data_dealer["namecust"]?><br />
		Tambahan Diskon : <?=strtoupper( $data_diskon["diskon"] )?><br />
		Nilai Diskon : <?= main::number_format_dec( $data_diskon["nilai_diskon"] ) .  " - SATUAN " . 
			($data_diskon["nilai_diskon"] <= 100 ? " % (PERSEN) " : "RP" ) ?>
	</div>	
</div>
<div style="display:none">
<input type="button" name="b_selesai" id="b_selesai" />	
</div>
<div style="padding:3px; margin:7px 0px 7px 0px; background-color:yellow; border:solid 1px #CCC">
	<h4>Item berikut sudah pernah mendapatkan diskon display sebelumnya dan tidak diperbolehkan lagi untuk mendapatkan diskon display (apabila tanpa pengajuan) :</h4>
</div>
<input type="checkbox" name="cb_cek_semua"  id="cb_cek_semua" value="0" onclick="cek(this)" /><label for="cb_cek_semua">Pilih semua item (Campaign + Non-campaign)</label>
<input type="button" name="b_set_qty" id="b_set_qty" value="Set Semua QTY Diskon = QTY Order" onclick="copy_qty()" style="display:none" />
<?=$data_order?>
<div style="padding:3px; margin:17px 0px 17px 0px; background-color:yellow; border:solid 1px #CCC; line-height:23px; float:left; width:100%">
	<h4>Item-item tersebut diatas dapat dihapus dari daftar order, ataupun dilanjutkan untuk pengajuan diskon display dengan persetujuan pejabat terkait. <br />
	Klik tombol untuk melanjutkan ke proses berikutnya :
	<div style="float:left; width:100%; margin:11px 0px 11px 0px">
		<input type="button" name="b_hapus_item" id="b_hapus_item" value="Hapus Item Terpilih" style="float:left; width:48%; background-color:red; color:#FFF; font-weight:900" onclick="execute(0)" />
		<input type="button" name="b_hapus_item" id="b_hapus_item" value="Lanjutkan untuk pengajuan diskon display dengan persetujuan" style="float:right; width:48%; background-color:green; color:#FFF; font-weight:900" onclick="execute(1)" />
	</div>
	</h4>
</div>
<script>
document.getElementById('total-order-bawah').setAttribute("style", "display:none");
</script>
<?
include_once "includes/bottom.php";
?>