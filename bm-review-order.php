<?
include "includes/top.php";
?>
<a href="diskon-pengajuan.php?dealer_id=<?=$data_dealer["idcust"]?>&order_id=<?=$data_dealer["order_id"]?>" style="color:blue">Kembali ke pengajuan tambahan diskon</a><br /><br />
<input type="hidden" name="order_id" value="<?=$data_dealer["order_id"]?>" />
<span class="tanda-seru">!</span>Anda sedang melakukan review pengajuan tambahan diskon (di luar campaign) untuk dealer sebagai berikut :
<div style="width:100%; background-color:#EEE; border:solid #CCC 1px; margin-top:7px; margin-bottom:7px">
	<div style="padding:17px">
		Sales : <?=$data_dealer["nama_lengkap"]?><br />
		No. Order : <?=$data_dealer["order_id"]?><br />
		Kode Dealer : <?=$data_dealer["idcust"]?><br />
		Nama Dealer : <?=$data_dealer["namecust"]?><br />
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
<div style="float:left;width:100%; margin-top:13px">
	<h4>Rasio Campaign - Non Campaign : <?=$rasio_campaign_non_campaign?></h4>
	<?=$daftar_order?>
</div>
<?
include "includes/bottom.php";
?>