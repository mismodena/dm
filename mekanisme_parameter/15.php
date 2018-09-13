<?

// cek parameter item dengan kuantitas terkecil dalam sub kategori untuk dicocokkan dengan parameter paket
include_once "6.php";

class kuantitas_item_dalam_sub_kategori extends kuantitas_sub_kategori{
	
	public $kuantitas_item_dalam_sub_kategori;
	
	function __construct( $obyek_dm, $arr_parameter ){
		
		$kuantitas_sub_kategori = new kuantitas_sub_kategori( $obyek_dm, $arr_parameter );		
		
		if( is_array($kuantitas_sub_kategori->arr_sub_kategori_order) ){
			
			asort( $kuantitas_sub_kategori->arr_sub_kategori_order );
			$arr_kuantitas_item_dalam_sub_kategori = array_values( $kuantitas_sub_kategori->arr_sub_kategori_order );
			$this->kuantitas_item_dalam_sub_kategori = $arr_kuantitas_item_dalam_sub_kategori[0];

		}
	}

}

?>