<?
include "lib/mainclass.php";
?>

<html>
<head>
<title>PT. INDOMO MULIA - Mobile Sales</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<script src="script/jquery.min.js"></script>
<script src="script/jquery-1.10.2.min.js"></script>
<!--<script src="script/jquery.easy-autocomplete.min.js"></script>-->
<script src="script/jquery-ui.js"></script>
<script src="script/ui.datepicker-id.js"></script>
<script src="script/tinybox.js"></script>
<script src="script/main.js"></script>
<script>
<?
if( file_exists( "script/srv/" . $page . ".js" ) )
	include_once "script/srv/" . $page . ".js";
?>
try{
	$(document).ready(function(){
		// set judul
		try{$('#judul-halaman').html(title);}catch(e){}	
		$('.slideout-menu-toggle').on('click', function(event){
			event.preventDefault();
			var slideoutMenu = $('.slideout-menu');
			var slideoutMenuWidth = $('.slideout-menu').width();
			
			slideoutMenu.toggleClass("open");
			
			if (slideoutMenu.hasClass("open")) {
				slideoutMenu.animate({
					left: "0px"
				});	
			} else {
				slideoutMenu.animate({
					left: -slideoutMenuWidth
				}, 250);	
			}
		    });
		<?=@$script?>
	});	
}catch(e){}
	
</script>
<style>
<?
if( file_exists( "css/srv/" . $page . ".css" ) )
	include_once "css/srv/" . $page . ".css";
echo @$style;
?>
</style>
<link href="css/main.css" rel="stylesheet" type="text/css" />
<link href="css/jquery-ui.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="frm" method="post">
<div style="padding-top:32px; margin:0px; width:100%; ">
<div id="menu-bar" style="position:absolute; top:0px; border-bottom:1px solid #CCC; background-color:#EEE; width:100%; padding:7px 0px 7px 0px;">
	<!--<a href="sales.php" style="float:left; margin-left:3px">-->
		<a href="#" class="slideout-menu-toggle"><img src="images/home.png" style="height:27px; border:none; padding:0px 9px 0px 3px" /></a>
	<span id="judul-halaman" style="float:right; margin-right:3px; line-height:27px"><img src="images/loading.gif" /></span>
</div>
<div class="slideout-menu">
	<h3>
	<a href="#" class="slideout-menu-toggle"><span style="padding:13px">&times;</span></a><br /></h3>
	<ul>
		<?=$string_daftar_menu_user?>
		<!--<li><a href="transaksi.php">Transaksi Penjualan</a></li>
		<li><a href="diskon.php">Pengajuan Tambahan Diskon <br />(di luar campaign)</a></li>	
		<li><a href="stok.php">Cek Data Stok</a></li>	
		<li><a href="histori.php">Data Histori Penjualan</a></li>	
		<li><a href="campaign.php">Campaign Berlaku</a></li>	-->
	</ul>
</div>
<div style="margin-left:3px; margin-right:0px; padding-top:17px; width:97%; position:absolute; top:41px">