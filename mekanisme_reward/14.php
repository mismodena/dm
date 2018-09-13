<?

class reward_non_diskon  extends sql_dm{	
	
	function __construct( $obyek_dm, $arr_parameter ){
		
		// untuk diskon ato free item dlm bentuk keterangan
		//$obyek_dm->arr_item_reward_non_diskon[  $arr_parameter[ "item_seq" ] ][ $arr_parameter["paketid"] ] = $arr_parameter[ "nilai_reward" ];
		
		// untuk free item (merchandise) yang didaftarkan di ACCPAC
		$arr_sql = array(
			"f.paketid" => array("=", "'". main::formatting_query_string( $arr_parameter["paketid"] ) ."'"),
			"f.urutan_parameter" => array("=", "'". main::formatting_query_string( $arr_parameter["urutan_parameter"] ) ."'"),			
		);
		$rs_paket_reward_item = self::browse_paket_reward_item( $arr_sql );
		
		$arr_paket_reward_item = array();
		
		while( $paket_reward_item = sqlsrv_fetch_array( $rs_paket_reward_item ) )	{	
			//$arr_paket_reward_item[ $paket_reward_item["item"] ] = $arr_parameter["nilai_reward"];
			@$obyek_dm->arr_free_item_paket[ $arr_parameter["paketid"] ][ $paket_reward_item["item"] ] = $arr_parameter["nilai_reward"];
		}
		//$obyek_dm->arr_free_item_paket[ $arr_parameter["paketid"] ] = $arr_paket_reward_item;

	}
	
}

?>