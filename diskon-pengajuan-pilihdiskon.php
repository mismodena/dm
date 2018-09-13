<?
include "includes/top.php";
?>
<a href="diskon-pengajuan.php?dealer_id=<?=$_REQUEST["dealer_id"]?>&order_id=<?=$_REQUEST["order_id"]?>">Kembali ke daftar tambahan diskon dealer</a><br /><br />
<input type="hidden" name="dealer_id" value="<?=@$_REQUEST["dealer_id"]?>" />
<input type="hidden" name="order_id" value="<?=$data_dealer["order_id"]?>" />
<input type="hidden" name="nominal_order" value="<?=@$nominal_order["nominal_order"]?>" />
<span class="tanda-seru">!</span>Anda sedang melakukan pemilihan tambahan diskon (di luar campaign) dengan info dealer sebagai berikut :
<div style="width:100%; background-color:#EEE; border:solid #CCC 1px; margin-top:7px; margin-bottom:7px">
	<div style="padding:17px">
		Sales : <?=$data_dealer["nama_lengkap"]?><br />
		No. Order : <?=$data_dealer["order_id"]?><br />
		Kode Dealer : <?=$data_dealer["idcust"]?><br />
		Nama Dealer : <?=$data_dealer["namecust"]?><br />
	</div>	
</div>
<input type="button" name="b_selesai" id="b_selesai" value="Terapkan Pilihan Tambahan Diskon" onclick="__submit('diskon-pengajuan.php', 'c=tambah_diskon')" class="tombol-hijau"/>	
<div style="margin:7px; 0px">
Total sebanyak <strong><?=$counter_belum_diterapkan?></strong> tambahan diskon belum diterapkan di order dealer ini.<br /><br />
<?=$list_diskon?>
</div>
<?
include "includes/bottom.php";
?>