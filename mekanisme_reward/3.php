<?

class reward_diskon_rupiah_per_item{	
	
	function __construct( $obyek_dm, $arr_parameter ){
		
		$obyek_dm->arr_item_diskon[  $arr_parameter[ "item_seq" ] ][ $arr_parameter["paketid"] ] += $arr_parameter[ "nilai_reward" ] * $arr_parameter[ "kuantitas" ];
		
	}
	
}

?>