<?
include "includes/top.php";

if( @$_REQUEST["order_id"] == "" ){
	include "transaksi-kontrol.cari-orderid.php";
	exit;
}
?>
<a href="bm-diskon-pengajuan.php" style="color:blue">Kembali ke daftar dealer</a><br /><br />
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
		<!--<input type="button" name="b_lihat_detail_order" id="b_lihat_detail_order" style="margin:17px 0px 7px 0px" value="Detail Order" onclick="location.href='detail-pengajuan-detailorder.php?kode_dealer=<?=$data_dealer["idcust"]?>&order_id=<?=$data_dealer["order_id"]?>&diskonid='" /><br />-->
		<div style="display:none">
		<input type="button" name="b_delete_order" id="b_delete_order" style="margin:17px 0px 7px 0px" value="Hapus Order" onclick="if( confirm('Hapus order ini?') )location.href='diskon-pengajuan.php?c=hapus_order&dealer_id=<?=$data_dealer["idcust"]?>&order_id=<?=$data_dealer["order_id"]?>'" /> | 
		<input type="button" name="b_kirim_order" id="b_kirim_order" style="margin:17px 0px 7px 0px"  <?= !$aktifkan_tombol_kirim_accpac ? "disabled" : "" ?> value="Kirim Order ke ACCPAC" onclick="if( confirm('Kirimkan order ini?\nApabila order dikirimkan, maka diskon tambahan yang sedang dalam proses persetujuan secara otomatis akan dibatalkan!') )location.href='transaksi-3.php?c=kirim_order&order_id=<?=$data_dealer["order_id"]?>'" />
		</div>
		<span style="display:<?=@$nominal_order["nominal_order"] > 0 ? "block" : "none"?>">
			<br />
			<strong>Nilai Order Sebelum Tambahan Diskon (belum dikirimkan)<br />Rp<?=main::number_format_dec(@$nominal_order["nominal_order"])?></strong>
		</span>
		<span style="display:<?=@$nominal_order["nominal_order"] > 0 ? "block" : "none"?>">
			<br />
			<strong>Draft Nilai Order Setelah Tambahan Diskon (belum dikirimkan)<br /><span id="order-setelah-diskon">Rp<?=main::number_format_dec(@$nominal_order["nominal_order_net"])?>*</span></strong>			
			<br /><br />*). Total order net yang termasuk semua tambahan diskon yang masih dalam proses pengajuan persetujuan.
			<br /><input type="button" name="b_lihat_draft" id="b_lihat_draft" value="Review Detail Order" onclick="location.href='transaksi-kontrol.review-order.php?order_id=<?=$data_dealer["order_id"]?>'" style="margin-top:13px; width:100%;" />
		</span>
		<div style="display:none">
		<?=$string_peringatan_stok_kosong?>
		</div>
	</div>	
</div>
<div style="display:none">
<input type="button" name="b_pilih_diskon" id="b_pilih_diskon" value="Pilih dan Atur Tambahan Diskon" onclick="pilih_diskon()" style="width:100%; margin:17px 0px 17px 0px; font-weight:900"/>	
<hr style="width:100%" />
<input type="button" name="b_hitung" id="b_hitung" value="Simpan & Hitung Nilai Order Setelah Diskon" onclick="__submit('diskon-pengajuan.php', 'c=hitung_order')" class="tombol-hijau"/>	
</div>
<div style="float:left;width:100%; margin-top:13px">
	<?=$list_diskon?>
</div>
<input type="button" name="b_kirim" id="b_kirim" style="display:none" value="Kirim Pengajuan Tambahan Diskon" <?= !$aktifkan_tombol_kirim || $item_stok_habis ? "disabled" : "" ?> onclick="kirim_pengajuan()" class="tombol-hijau"/>	
<?
include "includes/bottom.php";
?>