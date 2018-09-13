<?
include "includes/top_blank.php";
?>
<br /><br />
Berikut adalah data order penjualan untuk dealer sebagai berikut :
<div style="width:100%; background-color:#EEE; border:solid #CCC 1px; margin-top:7px; margin-bottom:7px">
	<div style="padding:17px">
		Sales : <?=$data_dealer["nama_lengkap"]?><br />
		No. Order : <?=(@$_REQUEST["order_id_split"] != "" ? $_REQUEST["order_id_split"] : $data_dealer["order_id"]) . ( $data_dealer["po_referensi"] != "" ? " (PO : ". $data_dealer["po_referensi"] .") " : "" )?><br />
		Kode : <?=$data_dealer["idcust"]?><br />
		Nama Dealer : <?=$data_dealer["namecust"]?><br />
		Alamat : <?=$data_dealer["addr"]?><br />
		Keterangan : <?=$data_dealer["keterangan_order"]?>
		<!--Diskon (%) : <?=$data_dealer["disc"]?><br /><br />-->
	</div>	
</div>
<div style="float:left;width:100%; ">
	<?=$daftar_order?>
</div>
<?
include "includes/bottom.php";
?>