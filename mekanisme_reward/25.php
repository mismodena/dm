<?

include_once "../mekanisme_parameter/28.php";

class reward_diskon_persen_per_item_akumulasi_item_sebulan{	
	
	function __construct( $obyek_dm, $arr_parameter ){	

		$akumulasi_kuantitas_order_sebulan = new akumulasi_kuantitas_order_sebulan( $obyek_dm, $arr_parameter );
		
		list( $diskon_1, $arr_disc_support ) = explode( ";", $arr_parameter["nilai_reward"] );

		$arr_disc_support = explode("_", $arr_disc_support );

		$n_disc = 0;
		foreach($arr_disc_support as $arr_support){
			list( $operator_support, $qty_support, $disc_support ) = explode(":", substr($arr_support, 1, -1) );

			switch ($operator_support) {
			    case ">":
			        	if($arr_parameter["kuantitas"] > $qty_support ) $n_disc = $disc_support;
			        break;
			    case ">=":
			        	if($arr_parameter["kuantitas"] >= $qty_support ) $n_disc = $disc_support;
			        break;	
			    case "<":
			        	if($arr_parameter["kuantitas"] < $qty_support ) $n_disc = $disc_support;
			        break;
			    case "<=":
			        	if($arr_parameter["kuantitas"] <= $qty_support ) $n_disc = $disc_support;
			        break;			    			    
			}			
		}

		$obyek_dm->arr_item_diskon[ $arr_parameter[ "item_seq" ] ][ $arr_parameter["paketid"] ] = $arr_parameter[ "basis_harga" ] * $n_disc / 100;

		$nilai_total_diskon = ($akumulasi_kuantitas_order_sebulan->akumulasi_nominal_order - $obyek_dm->arr_item_diskon[ $arr_parameter[ "item_seq" ] ][ $arr_parameter["paketid"] ]) * ($diskon_1 / 100);
	
		$sql = "select sum(kuantitas) kuantitas  from order_item where order_id = '". main::formatting_query_string( $arr_parameter["order_id"] ) ."' and paketid = '". main::formatting_query_string( $arr_parameter["paketid"] ) ."';";
		$kuantitas_order_item_dengan_paket_saat_ini = sqlsrv_fetch_array( sql::execute( $sql ) );

		foreach($obyek_dm->arr_item_dengan_paket as $arr_item_dengan_paket){
			list($item_seq, $itemid, $harga, $kuantitas, $paketid) = array_values( $arr_item_dengan_paket );
			if( $paketid != $arr_parameter["paketid"] ) continue;

			$obyek_dm->arr_item_cn[  $item_seq ][ $paketid ] = $nilai_total_diskon * $kuantitas / $kuantitas_order_item_dengan_paket_saat_ini["kuantitas"];
			$_SESSION['pilih_cn'] = 2;

		}				

	}
	
}

?>