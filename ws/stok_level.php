<?

$arr_data = array();

foreach( $_REQUEST["item"] as $item )
	$arr_item[] = "'". main::formatting_query_string( $item ) ."'";

if( count($arr_item)  <= 0 ) goto Skip;

$_SESSION["order_id"] = "temporary";
	
$ap=array(
			"/*ITEMNO*/"	=>	(@$_REQUEST["item"]		!=""?	
				" and (b.itemno in (". implode(",", $arr_item) .") )"	:""), 
			"/*LOCATION*/"	=>	(@$_REQUEST["cabang"]	!=""?	main::formatting_query_string( trim(strtoupper(@$_REQUEST["cabang"])) )					:""),
			"k.gudang"	=>	(@$_REQUEST["cabang"]	!=""? "'".	main::formatting_query_string( trim(strtoupper(@$_REQUEST["cabang"])) )	. "'"				:"")
		);

$rs = sql::execute( str_replace(array_keys($ap), array_values($ap), order::sql_item_info_khusus_untuk_cek_stok( 0 ) ) );
while( $data = sqlsrv_fetch_array( $rs ) )
	$arr_data[] = array( "itemno" => $data["itemno"], "kuantitas" => $data["qty_lokal"] );

Skip:
echo json_encode( $arr_data );

?>