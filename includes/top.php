<?
$arr_included_file = get_included_files();

if( !in_array( __DIR__ . "/includes/top.php", $arr_included_file ) )
	include "lib/mainclass.php";

include "includes/template_top.php";
?>

