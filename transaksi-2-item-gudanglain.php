<?
include "includes/top.php";
?>
<a href="javascript:history.back()">Kembali ke halaman sebelumnya</a>
<div style="float:left; width:100%">
	<h3>
		<div style="float:left">Order item <?=$nama_item?></div> 
		<div style="float:right">NET Rp<?=main::number_format_dec($harga_item)?></div>
	</h3>
</div>
<div style="float:left">
	<h3 style="display:block;">Net Dealer : <?=@$_SESSION["disc_dealer"] != "" ? $_SESSION["disc_dealer"] : $data_dealer["disc"]?>% 
	<a href="javascript:void(0)" onclick="TINY.box.show({iframe:'form_ubah_net.php',boxid:'frameless',width:255,height:247,fixed:true,maskid:'greymask',maskopacity:40,close:false})" style="display:<?=$display_ubah_net_dealer?>; text-decoration:none; color:blue; line-height:37px"><img src="images/expand-arrow.png" style="height:17px" /></a></h3>
	<div id="saran_paket">
		<h3>Saran Paket :</h3>
		<?=$saran_paket?>
	</div>
<div style="margin-top:13px"><strong>NOTE</strong> Hanya bisa mengambil dari gudang dimana terdapat kuantitas order dengan nilai lebih besar daripada nol (0).</div>	
<h3>Pilih order item dari gudang :</h3>
<?=$s_data_item?>
<input type="hidden" id="order_id" value="<?= $data_dealer["order_id"]?>" />
<input type="hidden" id="item" value="<?=$kode_item?>" />
<input type="hidden" id="harga" value="<?=$harga_item?>" />
<input type="hidden" id="dealer_id" value="<?=@$_REQUEST["dealer_id"]?>" />
<input type="hidden" id="diskonid" value="<?=@$_REQUEST["diskonid"]?>" />
</div>
<?
echo "<style>" . $style . "</style>";
include "includes/bottom.php";
?>