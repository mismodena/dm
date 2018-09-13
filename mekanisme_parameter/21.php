<?

class porsi_nilai_order_pembelian extends sql_dm{
	
	public $porsi_nilai_order_pembelian = false;
	
	function __construct( $obyek_dm, $arr_parameter ){

		// cari semua paket item
		$sql = "select * from paket_item where paketid = '". main::formatting_query_string( $arr_parameter["paketid"] ) ."' and mode = 1 ";
		$rs_semua_paket_item = sql::execute( $sql );
		while( $semua_paket_item = sqlsrv_fetch_array( $rs_semua_paket_item ) )
			$arr_semua_paket_item[] = $semua_paket_item["item"];
		
		// cari nilai item dan kategori item di dalam order_item yang mempunyai paketid = $arr_parameter["paketid"], dan harus berada di list array $arr_semua_paket_item
		$arr_set["b.order_id"] = array("=", "'". main::formatting_query_string( $arr_parameter["order_id"] ) ."'");
		$arr_set["b.paketid"] = array("=", "'". main::formatting_query_string( $arr_parameter["paketid"] ) ."'");
		$rs_item_paket = self::browse_cart( $arr_set );
		$data_kategori_item = sqlsrv_fetch_array( $rs_item_paket );
		
		$sql = "select * from sub_kategori where kode_prefiks like '%". substr($data_kategori_item["item_id"], 0, 2) ."%'";
		$rs_kategori = sql::execute( $sql );
		$rs_kategori_item = sqlsrv_fetch_array( $rs_kategori );		
		$kategori_item = $rs_kategori_item["kode_prefiks"];
		
		$arr_item_paket = $arr_sql_item_paket = array();
		$rs_item_paket = self::browse_cart( $arr_set );
		while( $item_paket = sqlsrv_fetch_array( $rs_item_paket ) )
			if( in_array( $item_paket["item_id"], $arr_semua_paket_item ) ){
				@$arr_item_paket[ $item_paket["item_id"] ] += ( $item_paket["kuantitas"] * $item_paket["harga"] );
				@$arr_item_paket_kuantitas[ $item_paket["item_id"] ] += $item_paket["kuantitas"];
				$arr_sql_item_paket[] = "'". $item_paket["item_id"] ."'";
			}

		// cari nilai item di dalam order_item yang mempunyai paketid is null (tidak ambil paket), ataupun mempunyai paketid = $arr_parameter[paketid] namun tidak berada di list array $arr_semua_paket_item
		$arr_item_non_paket = array();	
		unset( $arr_set );
		$arr_set["b.order_id"] = array("=", "'". main::formatting_query_string( $arr_parameter["order_id"] ) ."'");
		$arr_set["substring( b.item_id, 1, 2 )"] = array(" in ", " ( '". str_replace( ",", "','", $kategori_item ) ."' ) ");
		$arr_set["/*b.paketid*/"] = array("", "( b.paketid is NULL or b.paketid = '' or 
			( b.paketid = '". main::formatting_query_string( $arr_parameter["paketid"] ) ."' ". ( count( $arr_sql_item_paket ) > 0 ? "and b.item_id not in (". implode(",", $arr_sql_item_paket) .")" : "" ) ." ) )");
		$rs_item_non_paket = self::browse_cart( $arr_set );
		while( $item_non_paket = sqlsrv_fetch_array( $rs_item_non_paket ) ){
			if( !in_array( $item_non_paket["item_id"], $arr_semua_paket_item ) ){
				@$arr_item_non_paket[ $item_non_paket["item_id"] ] += ( $item_non_paket["kuantitas"] * $item_non_paket["harga"] );
				@$arr_item_non_paket_kuantitas[ $item_non_paket["item_id"] ] += $item_non_paket["kuantitas"];
				$arr_item_seq_non_paket[] = $item_non_paket["item_seq"];
			}else{
				@$arr_item_paket[ $item_non_paket["item_id"] ] += ( $item_non_paket["kuantitas"] * $item_non_paket["harga"] );
				@$arr_item_paket_kuantitas[ $item_non_paket["item_id"] ] += $item_non_paket["kuantitas"];
			}
		}
		
		// reset database item yang non paket
		if( !is_array( $arr_item_seq_non_paket ) || $arr_parameter["urutan_parameter"] > 1 ) goto Skip_Reset;
		
		unset( $arr_set, $arr_parameter_update );
		$arr_set["diskon"] = array("=", "0");
		$arr_set["diskon_default"] = array("=", "0");
		$arr_set["paketid"] = array("=", "NULL");
		$arr_parameter_update["order_id"] = array("=", "'" . main::formatting_query_string( $arr_parameter["order_id"] ) . "'");
		$arr_parameter_update["user_id"] = array("=", "'" . main::formatting_query_string( $arr_parameter["user_id"] ) . "'");
		$arr_parameter_update["item_seq"] = array(" in ", "( ". implode( ",", $arr_item_seq_non_paket ) ." )");
		self::update_order_item( $arr_set, $arr_parameter_update );
		
		// reset object item yang non paket
		foreach( $obyek_dm->arr_item_dengan_paket as $index=>$arr_data_item ){
			if( $arr_data_item["paketid"] == $arr_parameter["paketid"] ){

				if( !in_array( $arr_data_item["item"], $arr_semua_paket_item ) ) {
					
					unset( $obyek_dm->arr_item_dengan_paket[ $index ]["paketid"] );
					// saran paket
					$arr_saran_paket = array();
					$rs_saran_paket = self::browse_paket_per_item( $data["item_id"], $untuk_simulasi );
					while( $saran_paket = sqlsrv_fetch_array( $rs_saran_paket ) ){
						$arr_saran_paket[ $saran_paket["paketid"] ] = $saran_paket["keterangan_paket"];
					}
					$obyek_dm->arr_item_non_paket[] = $obyek_dm->arr_item_dengan_paket[ $index ] + array("saran_paket" => $arr_saran_paket);
					
					unset( $obyek_dm->arr_item_dengan_paket[ $index ] );

				}
			}
		}
		
		Skip_Reset:
		
		$total_item_paket = array_sum( array_values( $arr_item_paket ) );
		$total_item_non_paket = array_sum( array_values( $arr_item_non_paket ) );

		$total_kuantitas_item_paket_non_paket = array_sum( array_values($arr_item_non_paket_kuantitas) ) + array_sum( array_values($arr_item_paket_kuantitas) );
		$this->porsi_nilai_order_pembelian = $total_item_paket / ( $total_item_paket + $total_item_non_paket ) . ";" . ( $total_kuantitas_item_paket_non_paket ) ;

	}
	
}

?>