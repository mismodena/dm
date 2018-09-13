<?

class reward_diskon_rupiah_tanpa_kelipatan{	
	
	function __construct( $obyek_dm, $arr_parameter ){
		$kuantitas_total_item_paket = 0;

		foreach( $obyek_dm->arr_item_dengan_paket as $arr_item_paket ){
			if( $arr_item_paket["paketid"] == $arr_parameter["paketid"] )
				$kuantitas_total_item_paket += $arr_item_paket["kuantitas"];
		}
		
		$obyek_dm->arr_item_diskon[  $arr_parameter[ "item_seq" ] ][ $arr_parameter["paketid"] ] += $arr_parameter[ "nilai_reward" ] *  $arr_parameter[ "kuantitas" ] / $kuantitas_total_item_paket;
		
	}
	
}

?>