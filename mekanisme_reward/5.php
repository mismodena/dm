<?

class reward_free_item_harga_terendah{
	
	function __construct( $obyek_dm, $arr_parameter ){
				
		foreach( $obyek_dm->arr_paket[ $arr_parameter["paketid"] ] as $item )
			$arr_harga_item_dalam_paket[ $item ] = $obyek_dm->arr_item[ $item ]["harga"];
		
		asort( $arr_harga_item_dalam_paket, SORT_NUMERIC );
		
		$arr_item_harga_terendah = array_keys($arr_harga_item_dalam_paket);

		//$obyek_dm->arr_free_item_paket[ $arr_parameter["paketid"] ] = array( $arr_item_harga_terendah[0] =>  $arr_parameter["nilai_reward"] );
		@$obyek_dm->arr_free_item_paket[ $arr_parameter["paketid"] ][ $arr_item_harga_terendah[0] ] = $arr_parameter["nilai_reward"] ;
	}
	
}

?>