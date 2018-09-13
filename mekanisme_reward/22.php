<?

class reward_free_item_harga_terendah_kelipatan_2_faktur extends sql_dm{
	
	function __construct( $obyek_dm, $arr_parameter ){
		
		$kuantitas = 0;
		
		/*foreach( $obyek_dm->arr_item_dengan_paket as $arr_item_dengan_paket ){
			if( $arr_item_dengan_paket["paketid"] == $arr_parameter["paketid"] ){
				$arr_harga_item_dalam_paket[ $arr_item_dengan_paket["item"] ] = $arr_item_dengan_paket["harga"];
				$kuantitas += $arr_item_dengan_paket["kuantitas"];
			}
		}*/
		if( !class_exists("akumulasi_kuantitas_order_2_invoice") )
			include_once "mekanisme_parameter/26.php";
		
		$kelas_parameter = new akumulasi_kuantitas_order_2_invoice( $obyek_dm, $arr_parameter );
		$arr_harga_item_dalam_paket = $kelas_parameter->array_daftar_produk;
		$kuantitas = $kelas_parameter->akumulasi_kuantitas_order_2_invoice;

		// cari item harga terendah
		asort( $arr_harga_item_dalam_paket, SORT_NUMERIC );		
		$arr_item_harga_terendah = array_keys($arr_harga_item_dalam_paket);
				
		// cari kelipatan - akan mengambil minimal kuantitas dari paket_parameter.nilai_parameter sbg faktor pembagi
		$rs_minimal_kuantitas = self::paket_parameter_reward( 
					array(
						"a.paketid"			=> array( "=", "'" . main::formatting_query_string($arr_parameter["paketid"]) . "'" ),
						"a.urutan_parameter"	=> array( "=", "'" . main::formatting_query_string($obyek_dm->arr_paket_parameter[ $arr_parameter["paketid"] ]) . "'" ),
						)
				);
		$minimal_kuantitas = sqlsrv_fetch_array($rs_minimal_kuantitas); 
		$kelipatan = floor( $kuantitas / $minimal_kuantitas["nilai_parameter"] );

		//$obyek_dm->arr_free_item_paket[ $arr_parameter["paketid"] ] = array( $arr_item_harga_terendah[0] =>  $arr_parameter["nilai_reward"] * $kelipatan );
		@$obyek_dm->arr_free_item_paket[ $arr_parameter["paketid"] ][ $arr_item_harga_terendah[0] ] = ( $arr_parameter["nilai_reward"] * $kelipatan );

	}
	
}

?>