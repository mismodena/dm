<?

class reward_non_diskon_kelipatan  extends sql_dm{	
	
	function __construct( $obyek_dm, $arr_parameter ){
		
		// untuk diskon ato free item dlm bentuk keterangan
		//$obyek_dm->arr_item_reward_non_diskon[  $arr_parameter[ "item_seq" ] ][ $arr_parameter["paketid"] ] = $arr_parameter[ "nilai_reward" ];
		
		$kuantitas = 0;
		$arr_item = array();

/*		foreach( $obyek_dm->arr_paket[ $arr_parameter["paketid"] ] as $item ){
			
			$arr_harga_item_dalam_paket[ $item ] = $obyek_dm->arr_item[ $item ]["harga"];
			if( in_array( $item, $arr_item ) ) continue;
			$kuantitas += $obyek_dm->arr_item[ $item ]["kuantitas"];
			$arr_item[] = $item;
		}
*/		
		foreach( $obyek_dm->arr_item_dengan_paket as $arr_paket_item ){
			if( $arr_paket_item["paketid"] != $arr_parameter["paketid"] ) continue;
			$kuantitas += $arr_paket_item["kuantitas"];
		}

		// cari kelipatan - akan mengambil minimal kuantitas dari paket_parameter.nilai_parameter sbg faktor pembagi
		$rs_minimal_kuantitas = self::paket_parameter_reward( 
					array(
						"a.paketid"			=> array( "=", "'" . main::formatting_query_string($arr_parameter["paketid"]) . "'" ),
						"a.urutan_parameter"	=> array( "=", "'" . main::formatting_query_string($obyek_dm->arr_paket_parameter[ $arr_parameter["paketid"] ]) . "'" ),
						)
				);
		$minimal_kuantitas = sqlsrv_fetch_array($rs_minimal_kuantitas); 
		$kelipatan = floor( $kuantitas / $minimal_kuantitas["nilai_parameter"] );
		
		// untuk free item (merchandise) yang didaftarkan di ACCPAC
		$arr_sql = array(
			"f.paketid" => array("=", "'". main::formatting_query_string( $arr_parameter["paketid"] ) ."'"),
			"f.urutan_parameter" => array("=", "'". main::formatting_query_string( $arr_parameter["urutan_parameter"] ) ."'"),			
		);
		$rs_paket_reward_item = self::browse_paket_reward_item( $arr_sql );
		
		$arr_paket_reward_item = array();
		
		while( $paket_reward_item = sqlsrv_fetch_array( $rs_paket_reward_item ) )	{	
			//$arr_paket_reward_item[ $paket_reward_item["item"] ] = $arr_parameter["nilai_reward"]  * $kuantitas;
			@$obyek_dm->arr_free_item_paket[ $arr_parameter["paketid"] ][ $paket_reward_item["item"] ] = ( $arr_parameter["nilai_reward"] * $kelipatan );
		}
		//$obyek_dm->arr_free_item_paket[ $arr_parameter["paketid"] ] = $arr_paket_reward_item;
		
	}
	
}

?>