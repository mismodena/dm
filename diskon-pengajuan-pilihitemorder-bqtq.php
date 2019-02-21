<?
include "includes/top.php";
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
		<!--Nilai Diskon : <?= main::number_format_dec( $data_diskon["nilai_diskon"] ) .  " - SATUAN " . 
			($data_diskon["nilai_diskon"] <= 100 ? " % (PERSEN) " : "RP" ) ?>
		<br />-->
		<?
			if($_REQUEST["diskonid"]==2)
				echo "";			
			else {
		?>
			<strong>Budget saldo tersedia : Rp <?=$saldo_tersedia_formatted[ $_REQUEST["diskonid"] ] ?></strong>
		<?
			}
		?>
	</div>	
</div>
<div style="display:block; border:solid 1px #CCC; background-color:#EEE; padding:7px">
	<strong>Detail Budgeting</strong><br />
	<div style="border:solid 1px #CCC; background-color:#FFF; padding:7px; margin:3px 0px 7px 0px">
	<table cellpadding="3" cellspacing="0" border="1" width="100%" style="border:solid #CCC 1px">
		<?
			if($_REQUEST["diskonid"]==2)
				echo "";			
			else {
		?>
		<tr>
			<td><?=strtoupper( $data_diskon["diskon"] )?></td>
			<td>Rp<?= $saldo_awal_formatted[ $_REQUEST["diskonid"] ]?></td>
		</tr>
		<?}?>
		<tr>
			<td>Diskon diajukan</td>
			<td><input type="text" name="nilai_diskon" id="nilai_diskon" value="<?= main::number_format_dec( $data_diskon["nilai_diskon"] )?>" onblur="hitung_diskon(this)" /></td>
		</tr>
		<tr>
			<td>Budget diskon dibutuhkan</td>
			<td>Rp.<?=@$item_order_nominal_formatted?></td>
		</tr>
		<?
			if($_REQUEST["diskonid"]==2)
				echo "";			
			else {
		?>
		<tr>
			<td  class="kuning"><strong>Sisa Budget Saldo</strong></td>
			<td  class="kuning"><strong>Rp<?= main::number_format_dec($saldo_tersedia[ $_REQUEST["diskonid"] ] - @$item_order_nominal )?></strong></td>
		</tr>
			<?}?>
	</table>
	<div style="display:<?= $warning ? "block" : "none"?>; color:red; font-weight:900; padding:7px; width:100%; text-align:center">Saldo budget diskon tidak mencukupi!</div>
	 </div>
</div>
<input type="button" name="b_selesai" id="b_selesai" value="Hitung Budget Diskon" onclick="if(__validasi(this))__submit('diskon-pengajuan-pilihitemorder-bqtq.php', 'c=pilih_itemorder_bqtq')" class="tombol-hijau"/>	
<input type="checkbox" name="cb_cek_semua"  id="cb_cek_semua" value="0" onclick="cek(this)" /><label for="cb_cek_semua">Pilih semua item (Campaign + Non-campaign)</label>
<input type="button" name="b_set_qty" id="b_set_qty" value="Set Semua QTY Diskon = QTY Order" onclick="copy_qty()" style="display:none" />
<?=$data_order?>
<?
include "includes/bottom.php";
?>