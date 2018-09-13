<?

class reward_diskon_persen_2_item_perdana{	
	
	public $maksimal_pengali = 2;
	
	function __construct( $obyek_dm, $arr_parameter ){	
		
		$sisa_pengali = $this->maksimal_pengali;
		
		foreach($obyek_dm->arr_item_dengan_paket as $arr_item_dengan_paket){
			list($item_seq, $itemid, $harga, $kuantitas, $paketid) = array_values( $arr_item_dengan_paket );
			
			if( $paketid != $arr_parameter["paketid"] ) continue;
			
			$pengali = $kuantitas >= $sisa_pengali ? $sisa_pengali : $kuantitas;
			if( $pengali <= 0 ) $pengali = 0;
			
			if( $item_seq == $arr_parameter[ "item_seq" ] ){
				$obyek_dm->arr_item_diskon[ $arr_parameter[ "item_seq" ] ][ $arr_parameter["paketid"] ] = $pengali * $arr_parameter[ "harga_per_unit" ] * $arr_parameter[ "nilai_reward" ] / 100;
				break;
			}
			
			$sisa_pengali -= $pengali;
			
		}
		
	}
	
}

?>