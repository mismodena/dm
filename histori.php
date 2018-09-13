<?
include "includes/top.php";
?>
<style>
#histori-table td{
	height:27px;
}
</style>
<div>
	Berikut ini adalah data penjualan Anda. Mohon isikan tanggal awal / akhir untuk melakukan pencarian: <br />
	<table width="100%" border="0" style="margin-top:17px; margin-bottom:17px;" id="histori-table">
		<tr>
			<td width="50%">Tanggal Awal</td>
			<td><input type="text" name="awal" id="awal" value="<?=@$awal_formatted?>" style="width:100%" readonly /></td>
		</tr>
		<tr>
			<td width="50%">Tanggal Akhir </td>
			<td><input type="text" name="akhir" id="akhir" value="<?=@$akhir_formatted?>" style="width:100%" readonly /></td>
		</tr>
		<tr>
			<td>
				<input type="hidden" name="hd_awal" id="hd_awal" value="<?=@$_REQUEST["awal"]?>" />
				<input type="hidden" name="hd_akhir" id="hd_akhir" value="<?=@$_REQUEST["akhir"]?>" />
			</td>
			<td><input type="button" name="b_cari" id="b_cari" value="Cari" onclick="cari_order()" /></td>
		</tr>
	</table> 
	<div style="padding-top:17px">
		<?=@$data_order?>
	</div>
</div>
<?
include "includes/bottom.php";
?>