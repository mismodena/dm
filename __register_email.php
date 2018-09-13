<?

include_once "lib/mainclass.php";
if( @$_REQUEST["order_id"] == "" ) die("1");

$sql = "insert into order_email (order_id, user_id, internet_protocol) 
				select order_id, [user_id],  '". main::formatting_query_string( $_SERVER["REMOTE_ADDR"] ) ."'
				from [order] where order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."' 
					and not exists (select 1 from order_email where order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."' );";

//set order.kirim = 1 					
$sql .= " update [order] set kirim = 1 where order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."' ;";
					
sql::execute( $sql );					
					
?>