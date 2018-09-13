<?

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include "../lib/mainclass.php";

if( file_exists( $_REQUEST["ws"] . ".php" ) )
	include $_REQUEST["ws"] . ".php";

?>