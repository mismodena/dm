<?

class reward_diskon_item_bertingkat_2_kedua  extends sql_dm{	
	
	function __construct( $obyek_dm, $arr_parameter ){

		foreach( $obyek_dm->arr_item_dengan_paket as $index=>$arr_data_item ){
			if( $arr_data_item["paketid"] == $arr_parameter["paketid"] ){
				$arr_item_index[ $arr_data_item["item_seq"] ] = $index;
				$arr_urutan_item_harga_tinggi_rendah[ $arr_data_item["item_seq"] ] = $arr_data_item["harga"];
			}
		}
		
		arsort ( $arr_urutan_item_harga_tinggi_rendah );
		
		list( $diskon_1, $diskon_2 ) = explode( ";", $arr_parameter["nilai_reward"] );

		$maksimal_kuantitas_diskon_level_pertama = 2;
		$counter = 0;		
		foreach( $arr_urutan_item_harga_tinggi_rendah as $item_seq=>$harga_item ){
			
			$kuantitas = $obyek_dm->arr_item_dengan_paket[ $arr_item_index[ $item_seq ] ]["kuantitas"];
			$harga = $obyek_dm->arr_item_dengan_paket[ $arr_item_index[ $item_seq ] ]["harga"];
			$paketid = $arr_parameter["paketid"];

			if( $counter <= $maksimal_kuantitas_diskon_level_pertama ){ // diskon utk item pertama dan kedua
				$maksimal_kuantitas_diskon_level_pertama -= $counter;
				$kuantitas_item_pertama = $kuantitas <= $maksimal_kuantitas_diskon_level_pertama ? $kuantitas : $maksimal_kuantitas_diskon_level_pertama;
				$obyek_dm->arr_item_diskon[  $item_seq ][ $paketid ] = ($diskon_1 / 100 ) * $harga * $kuantitas_item_pertama;
				$obyek_dm->arr_item_diskon[  $item_seq ][ $paketid ] += ($diskon_2 / 100 ) * $harga * ( $kuantitas - $kuantitas_item_pertama <= 0 ? 0 : $kuantitas - $kuantitas_item_pertama );
				$counter += $kuantitas_item_pertama;
				
			}else // diskon utk item untuk item berikutnya
				$obyek_dm->arr_item_diskon[  $item_seq ][ $paketid ] = ($diskon_2 / 100 ) * $harga * $kuantitas;				
			
		}		

	}
	
}

?>