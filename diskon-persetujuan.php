<?
include "includes/top.php";
?>
<a href="daftar-persetujuan-diskon.php" style="color:blue">Kembali ke daftar dealer</a><br /><br />
<input type="hidden" name="dealer_id" value="<?=@$_REQUEST["dealer_id"]?>" />
<input type="hidden" name="order_id" value="<?=$data_dealer["order_id"]?>" />
<input type="hidden" name="nominal_order" value="<?=@$nominal_order["nominal_order"]?>" />
<span class="tanda-seru">!</span>Anda sedang melakukan pengajuan tambahan diskon (di luar campaign) untuk dealer sebagai berikut :
<div style="width:100%; background-color:#EEE; border:solid #CCC 1px; margin-top:7px; margin-bottom:7px">
	<div style="padding:17px">
		Sales : <?=$data_dealer["nama_lengkap"]?><br />
		No. Order : <?=$data_dealer["order_id"]?><br />
		Kode Dealer : <?=$data_dealer["idcust"]?><br />
		Nama Dealer : <?=$data_dealer["namecust"]?><br />
		<input type="button" name="b_lihat_detail_order" id="b_lihat_detail_order" style="margin:17px 0px 7px 0px" value="Lihat Detail Order" onclick="location.href='diskon-pengajuan-detailorder.php?kode_dealer=<?=$data_dealer["idcust"]?>&order_id=<?=$data_dealer["order_id"]?>&diskonid='" />
		<span style="display:<?=@$nominal_order["nominal_order"] > 0 ? "block" : "none"?>">
			<br />
			<strong>Nilai Order Sebelum Tambahan Diskon (belum dikirimkan)<br />Rp<?=main::number_format_dec(@$nominal_order["nominal_order"])?></strong>
		</span>
		<span style="display:<?=@$nominal_order["nominal_order"] > 0 ? "block" : "none"?>">
			<br />
			<strong>Draft Nilai Order Setelah Tambahan Diskon (belum dikirimkan)<br /><span id="order-setelah-diskon">Rp<?=main::number_format_dec(@$nominal_order_setelah_diskon)?>*</span></strong>
			<br /><br />*). Total order net yang termasuk semua tambahan diskon yang masih dalam proses pengajuan persetujuan.
		</span>
	</div>	
</div>
<div style="float:left;width:100%; margin:13px 0px 23px 0px; ">
	<?=$data_persetujuan_diskon?>
</div>
<?
include "includes/bottom.php";
?>