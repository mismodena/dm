<?

class reward_diskon_item_bertingkat_2  extends sql_dm{	
	
	function __construct( $obyek_dm, $arr_parameter ){
		
		foreach( $obyek_dm->arr_item_dengan_paket as $index=>$arr_data_item ){
			if( $arr_data_item["paketid"] == $arr_parameter["paketid"] ){
				$arr_item_index[ $arr_data_item["item_seq"] ] = $index;
				$arr_urutan_item_harga_tinggi_rendah[ $arr_data_item["item_seq"] ] = $arr_data_item["harga"];
			}
		}
		
		arsort ( $arr_urutan_item_harga_tinggi_rendah );
		
		list( $diskon_1, $diskon_2 ) = explode( ";", $arr_parameter["nilai_reward"] );

		$counter = 1;		
		foreach( $arr_urutan_item_harga_tinggi_rendah as $item_seq=>$harga_item ){
			
			$kuantitas = $obyek_dm->arr_item_dengan_paket[ $arr_item_index[ $item_seq ] ]["kuantitas"];
			$harga = $obyek_dm->arr_item_dengan_paket[ $arr_item_index[ $item_seq ] ]["harga"];
			$paketid = $arr_parameter["paketid"];
			
			if( $counter <= 1 ){ // diskon utk item pertama
				$kuantitas_item_pertama = $kuantitas;
				$obyek_dm->arr_item_diskon[  $item_seq ][ $paketid ] = ($diskon_1 / 100 ) * $harga * 1;
				$obyek_dm->arr_item_diskon[  $item_seq ][ $paketid ] += ($diskon_2 / 100 ) * $harga * ( $kuantitas_item_pertama  - 1 );
				
			}else // diskon utk item untuk item berikutnya
				$obyek_dm->arr_item_diskon[  $item_seq ][ $paketid ] = ($diskon_2 / 100 ) * $harga * $kuantitas;
				
			$counter++;
			
		}		
		
	}
	
}

?>