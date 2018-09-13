<?
include "lib/mainclass.php";
?>

<html>
<head>
<title>PT. INDOMO MULIA - Mobile Sales</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<script src="script/jquery.min.js"></script>
<script src="script/jquery-1.10.2.min.js"></script>
<script src="script/jquery.easy-autocomplete.min.js"></script>
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
		
		<?=@$script?>
	});
}catch(e){}
</script>
<style>
<?
if( file_exists( "css/srv/" . $page . ".css" ) )
	include_once "css/srv/" . $page . ".css";
?>
</style>
<link href="css/main.css" rel="stylesheet" type="text/css" />
<link href="css/jquery-ui.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="frm" method="post">
<div style="margin:0px; width:100%; ">
<div style="margin-left:3px; margin-right:3px; width:97%;">