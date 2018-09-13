<?

class reward_cashback_item_ttn_1000 extends sql_dm{
	
	function __construct( $obyek_dm, $arr_parameter ){ 
		
		$item_khusus = array('CF2130S0914F08',"CF3180S0914F08","CG3180S0415F08","CG2130S0415F08","CU3300S1115F10","UF2070S0914F08","UF4130S0914F08","SF1040W0217I05","SF2080W0217I05");
				

		foreach( $obyek_dm->arr_item_dengan_paket as $arr_detail_item_paket ){	
			list($item_seq, $itemid, $harga, $kuantitas, $paketid) = array_values( $arr_detail_item_paket );

			if( $arr_detail_item_paket["paketid"] !=  $arr_parameter["paketid"] ) continue;

			if( in_array( $arr_detail_item_paket["item"], $item_khusus ) ) {
				if( $item_seq == $arr_parameter[ "item_seq" ] ){
					$obyek_dm->arr_item_diskon[$arr_detail_item_paket["item_seq"]][ $arr_parameter["paketid"] ] += $arr_parameter["nilai_reward"] * $arr_parameter["kuantitas"];;
					break;
				}

			}
		}
	}
	
}

?>