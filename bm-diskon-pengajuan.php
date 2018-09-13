<?
include "includes/top.php";
?>

<style>
.display-nama-sales{display:block !important}
</style>

Berikut adalah daftar order yang diajukan diskon tambahan oleh sales Anda:
<div style="padding:17px 0px 17px 0px;">
	<div style="margin-bottom:17px">Sebanyak <?=$jumlah_data?> data ditampilkan.</div>
	<?=@$data_dealer?>
</div>

<?
include "includes/bottom.php";
?>