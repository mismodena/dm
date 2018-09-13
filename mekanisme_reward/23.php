<?

class reward_cashback_item_ttn_300 extends sql_dm{
	
	function __construct( $obyek_dm, $arr_parameter ){
				
		$item_khusus = array("CP4200S0715A10",'CT7640S0914A10','CT7960S0914A10','FT6930S0914A10','SL2200S0715A10','SL3000S0715A10','TC1800S0715A10');
		
		foreach( $obyek_dm->arr_item_dengan_paket as $arr_detail_item_paket ){	
			list($item_seq, $itemid, $harga, $kuantitas, $paketid) = array_values( $arr_detail_item_paket );

			if( $arr_detail_item_paket["paketid"] !=  $arr_parameter["paketid"] ) continue;

			if( in_array( $arr_detail_item_paket["item"], $item_khusus ) ) {
				if( $item_seq == $arr_parameter[ "item_seq" ] ){
					$obyek_dm->arr_item_diskon[$arr_detail_item_paket["item_seq"]][ $arr_parameter["paketid"] ] += $arr_parameter["nilai_reward"] * $arr_parameter["kuantitas"];
					break;
				}

			}
		}
	}
	
}

?>