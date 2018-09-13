<?
include "includes/top.php";

?>
<a href="transaksi-2.php" style="color:blue">Kembali ke daftar order</a><br /><br />
<!--Anda sedang melakukan order untuk dealer sebagai berikut :
<div style="width:100%; background-color:#EEE; border:solid #CCC 1px; margin-top:7px; margin-bottom:7px">
	<div style="padding:17px">
		Kode : <?=$data_dealer["idcust"]?><br />
		Nama Dealer : <?=$data_dealer["namecust"]?><br />
		<!--Alamat : <?=$data_dealer["addr"]?> <?=$data_dealer["namecity"]?><br />
		Diskon (%) : <?=$data_dealer["disc"]?>
	</div>
</div>-->
<div style="float:left; width:100%">
	<?=@$string_entri_po?>
	<?=@$data_po?>
	<div style="float:left; width:100%; position:relative">
	<img src="images/item.png" border="none" style="float:right; text-align:right; position:absolute; right:0px; top:0px;" />
	Cari item 
	<input type="text" name="item" id="item" value="<?=@$_REQUEST["item"]?>" style="width:107px" /><input type="button" name="b_cari" id="b_cari" value="Cari" onclick="cari_item()" />
	<h3 style="display:block">Net Dealer : <?=@$_SESSION["disc_dealer"] != "" ? $_SESSION["disc_dealer"] : $data_dealer["disc"]?>% 
	<a href="javascript:void(0)" onclick="TINY.box.show({iframe:'form_ubah_net.php',boxid:'frameless',width:255,height:247,fixed:true,maskid:'greymask',maskopacity:40,close:false})" style="display:<?=$display_ubah_net_dealer?>; text-decoration:none; color:blue; line-height:37px"><img src="images/expand-arrow.png" style="height:17px" /></a></h3><br />
	<input type="checkbox" name="cbx" id="cbx" value="1" <?=@$_REQUEST["cbx"] == "1" || !isset($_REQUEST["cbx"]) ? "checked" : "" ?> /><label for="cbx">Hanya item dengan stok lokal lebih dari 0 (nol) unit.<br />Ketersediaan stok lokal sudah disesuaikan dengan jumlah unit yang sedang dalam proses order ini.</label>
		<div style="padding-top:17px">
			<?=@$data_item?>
		</div>
	</div>
</div>
<?
include "includes/bottom.php";
?>