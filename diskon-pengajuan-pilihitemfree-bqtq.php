<?
include "includes/top.php";
?>
<link rel="stylesheet" href="css/srv/diskon-pengajuan.php.css"></link>
<a href="diskon-pengajuan.php?dealer_id=<?=$_REQUEST["dealer_id"]?>&order_id=<?=$_REQUEST["order_id"]?>">Kembali ke halaman pengajuan tambahan diskon</a><br /><br />
<input type="hidden" name="dealer_id" id="dealer_id" value="<?=$_REQUEST["dealer_id"]?>" />
<input type="hidden" name="order_id" id="order_id" value="<?=$_REQUEST["order_id"]?>" />
<input type="hidden" name="diskonid" id="diskonid" value="<?=$_REQUEST["diskonid"]?>" />
<span class="tanda-seru">!</span>Anda sedang pemilihan item FREE untuk pengajuan tambahan diskon (di luar campaign) dengan info sebagai berikut :
<div style="width:100%; background-color:#EEE; border:solid #CCC 1px; margin-top:7px; margin-bottom:7px">
	<div style="padding:17px">
		Sales : <?=$data_dealer["nama_lengkap"]?><br />
		No. Order : <?=$_REQUEST["order_id"]?><br />
		Kode Dealer : <?=$data_dealer["idcust"]?><br />
		Nama Dealer : <?=$data_dealer["namecust"]?><br />
		Tambahan Diskon : <?=strtoupper( $data_diskon["diskon"] )?><br />
		<strong>Budget saldo tersedia : Rp<?= $saldo_tersedia_formatted[ $_REQUEST["diskonid"] ]?></strong>
	</div>	
</div>
<div style="display:<?=@$display_konfirmasi_share != "" ? "block" : "none"?>; border:solid 1px #CCC; background-color:#EEE; padding:7px">
	<strong>Detail Budgeting</strong><br />
	<div style="border:solid 1px #CCC; background-color:#FFF; padding:7px; margin:3px 0px 7px 0px">
	<table cellpadding="3" cellspacing="0" border="1" width="100%" style="border:solid #CCC 1px">
		<tr>
			<td><?=strtoupper( $data_diskon["diskon"] )?></td>
			<td>Rp<?= $saldo_tersedia_formatted[ $_REQUEST["diskonid"] ]?></td>
		</tr>
		<tr>
			<td><?=@$item_free?> x <?=@$item_free_kuantitas?> unit </td>
			<td>Rp.<?=@$item_free_nominal_formattted?> (-)</td>
		</tr>
		<tr>
			<td  class="kuning"><strong>Kekurangan Budget Saldo</strong></td>
			<td  class="kuning"><strong>Rp<?= main::number_format_dec(abs($saldo_tersedia[ $_REQUEST["diskonid"] ] - @$item_free_nominal))?></strong></td>
		</tr>
	</table>
	 </div>
	 <span style="color:red; font-weight:700">Mohon klik tombol "Kombinasikan Budget" di bawah (apabila ada) untuk mengambil budget diskon dari alokasi lain, karena budget diskon yang tidak mencukupi.</span>
</div>
<div style="margin:13px 0px 13px 0px; display:<?=$display_diskon_share?>">
<strong>Kombinasi dengan budget diskon berikut :</strong>
<?=$alternatif_kombinasi_budget_diskon?>
</div>
<!--<input type="button" name="b_selesai" id="b_selesai" value="Pilih Item Order" onclick="__submit('diskon-pengajuan.php', 'c=pilih_itemfree')" class="tombol-hijau"/>-->
<div style="float:left;">
	Cari item 
	<input type="text" name="item" id="item" value="<?=@$_REQUEST["item"]?>" style="width:107px" /><input type="button" name="b_cari" id="b_cari" value="Cari" <?=$saldo_tersedia > 0 ? "" : "disabled" ?> onclick="cari_item()" /><br />
	<input type="checkbox" name="cbx" id="cbx" value="<?=@$_REQUEST["cbx"] != "" ? $_REQUEST["cbx"] : 1 ?>" <?=@$_REQUEST["cbx"] == 1 || !isset($_REQUEST["cbx"]) ? "checked" : "" ?> /><label for="cbx">Hanya item dengan stok lebih dari 0 (nol) unit.</label>
	<div style="margin-top:7px"><strong>NOTE</strong> Hanya bisa mengambil dari gudang dimana terdapat kuantitas order dengan nilai lebih besar daripada nol (0).</div>
	<div style="padding-top:17px">
		<?=@$data_item?>
	</div>
</div>
<?
include "includes/bottom.php";
?>