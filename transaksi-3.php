<?
include "includes/top.php";
set_time_limit(60);
?>
<a href="transaksi-2.php" style="color:blue">Kembali ke detail item order</a><br /><br />
<span class="tanda-seru">!</span>Anda sedang melakukan order pembelian item untuk dealer sebagai berikut :
<div style="width:100%; background-color:#EEE; border:solid #CCC 1px; margin-top:7px; margin-bottom:7px">
	<div style="padding:17px">
		Kode : <?=$data_dealer["idcust"]?><br />
		Nama Dealer : <?=$data_dealer["namecust"]?><br />
		<!--Diskon (%) : <?=$data_dealer["disc"]?><br />-->
	</div>	
</div>
<div id="overlimit-note" style="width:100%; background-color:#EEE; border:solid #CCC 1px; margin-top:7px; margin-bottom:7px; display:none">
	<div style="padding:17px">
		<div style="font-weight:900"><span class="tanda-seru">!</span>Dealer ini telah melebihi limit kredit (Overlimit)</div>
		<div>Limit kredit dealer : Rp<span id="limit-kredit"></span></div>
		<div>Piutang + Order sekarang : Rp<span id="piutang+order"></span></div>
	</div>	
</div>
<div style="float:left;width:100%; ">
	<div style="text-align:center; margin-top:17px; border-top:3px double #CCC; border-bottom:3px double #CCC; background-color:#EEE;"><h3>Ringkasan Order</h3></div>
	<div style="font-weight:bold; line-height:27px;">
		Total Order : Rp<span id="total-order"></span><br />
		Total Diskon Campaign : Rp<span id="total-diskon-campaign"></span><br />
		Tambahan Diskon Disetujui : Rp<span id="tambahan-diskon"></span><br />
		Total Order Net : Rp<span id="total-order-net"></span><br />
	</div>
</div>
<div style="float:left;width:100%; margin-bottom:17px;">
	<div style="text-align:center; margin-top:17px; border-top:3px double #CCC; border-bottom:3px double #CCC; background-color:#EEE;"><h3>Tambahan Diskon</h3></div>
	<?=$list_diskon?>
</div>
<input type="button" name="b_kirim" id="b_kirim" value="Kirimkan Order" onclick="if(confirm('Kirimkan order?'))location.href='transaksi-3.php?c=kirim_order'" class="tombol-hijau"/>	
<?
include "includes/bottom.php";
?>