<?

include_once "../mekanisme_parameter/20.php";

class reward_diskon_persen_per_item_akumulasi_item{	
	
	function __construct( $obyek_dm, $arr_parameter ){	
	
		$akumulasi_kuantitas_order = new akumulasi_kuantitas_order( $obyek_dm, $arr_parameter );
		
		$nilai_total_diskon = $akumulasi_kuantitas_order->akumulasi_nominal_order * $arr_parameter[ "nilai_reward" ] / 100;

		$sql = "select sum(kuantitas) kuantitas  from order_item where order_id = '". main::formatting_query_string( $arr_parameter["order_id"] ) ."' and paketid = '". main::formatting_query_string( $arr_parameter["paketid"] ) ."';";
		$kuantitas_order_item_dengan_paket_saat_ini = sqlsrv_fetch_array( sql::execute( $sql ) );
		
		foreach($obyek_dm->arr_item_dengan_paket as $arr_item_dengan_paket){
			list($item_seq, $itemid, $harga, $kuantitas, $paketid) = array_values( $arr_item_dengan_paket );
			if( $paketid != $arr_parameter["paketid"] ) continue;

			$obyek_dm->arr_item_diskon[  $item_seq ][ $paketid ] = $nilai_total_diskon * $kuantitas / $kuantitas_order_item_dengan_paket_saat_ini["kuantitas"];
		}

	}
	
}

?>