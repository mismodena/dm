<?
include "includes/top.php";
?>
<style>
li{
	padding-bottom:17px;
}
li a{
	color:blue;
}
.slideout-menu, .slideout-menu-toggle, img[src="images/logout.png"]{
	display:none !important;
}
</style>
<h2>Selamat Datang di aplikasi Mobile Sales - PT. INDOMO MULIA</h2>
Silahkan pilih menu :
<ul>
	<?=$string_daftar_menu_user?>
	<!--<li><a href="transaksi.php">Transaksi Penjualan</a></li>
	<li><a href="diskon.php">Pengajuan Tambahan Diskon <br />(di luar campaign)</a></li>	
	<li><a href="stok.php">Cek Data Stok</a></li>	
	<li><a href="histori.php">Data Histori Penjualan</a></li>	
	<li><a href="campaign.php">Campaign Berlaku</a></li>	-->
</ul>
<?
include "includes/bottom.php";
?>
