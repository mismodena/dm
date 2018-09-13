<?

class order_split extends order{

	public 	$arr_item_nama /* [itemid] = nama lengkap produk */
			//,$arr_item /* [itemid] = array(harga net / item, kuantitas) */
			//,$arr_item_paket /* [itemid] = paketid */
			,$arr_item_dengan_paket
			,$arr_item_non_paket /* [n] = itemid */
			//,$arr_paket /* [paketid] = itemid */
			//,$arr_paket_parameter /* [paketid] = urutan parameter terpakai / terakhir */
			//,$arr_grup_paket_parameter /* [paketid] = grup dari urutan parameter terpakai / terakhir */
			,$arr_keterangan_paket_parameter /* [paketid] = keterangan dari urutan parameter terpakai / terakhir */
			//,$arr_paket_reward /* [paketid] = reward (Rp / %) / item */
			,$arr_item_diskon /* [itemid] = array( [paketid] => diskon (Rp) ) -- diskon untuk sub total item */
			,$arr_keterangan_paket /* [paketid] =  keterangan paket */
			,$arr_free_item_paket /* [paketid] = array( itemid => kuantitas )*/
			,$belum_ada_order
			,$gudang;
	
	function __construct($order_id){
		
		$this->belum_ada_order = true;
		
		$arr_parameter["dbo.sambung_order_id(a.order_id, a.order_id_split, '-')"] = array("=", "'". main::formatting_query_string( $order_id ) ."'");
		$rs_item = self::browse_cart_split( $arr_parameter );
		
		while( $item = sqlsrv_fetch_array( $rs_item ) ){
			
			$this->gudang = $item["gudang"];
			
			$this->arr_item_nama[ $item["item_id"] ] = $item["item_nama"];
			
			if( $item["paketid"] == "")
				$this->arr_item_non_paket[] = array( "item_seq" => $item["item_seq"], "item" => $item["item_id"], "harga" => $item["harga"], "kuantitas" => $item["kuantitas"], "saran_paket" => array(), "gudang" => $item["gudang"], "gudang_asal" => $item["gudang_asal"] );				
			else{
				if( $item["harga"] <= 0 || ($item["harga"] * $item["kuantitas"]) == $item["diskon"] )
					$this->arr_free_item_paket[ $item["paketid"] ][ $item["item_id"] ] = $item["kuantitas"];
				else
					$this->arr_item_dengan_paket[] = array( "item_seq" => $item["item_seq"], "item" => $item["item_id"], "harga" => $item["harga"], "kuantitas" => $item["kuantitas"], "paketid" => $item["paketid"], "gudang" => $item["gudang"], "gudang_asal" => $item["gudang_asal"] );
			}
			
			unset( $arr_parameter );
			$arr_parameter["a.paketid"] = array("=", "'". main::formatting_query_string( $item["paketid"] ) ."'");
			$arr_parameter["/*urutan_parameter + grup_parameter*/"] = array("", "
									(a.urutan_parameter = '". main::formatting_query_string( $item["urutan_parameter"] ) ."' or 
									a.grup_parameter = (select grup_parameter from paket_parameter where 
										paketid = '" . main::formatting_query_string( $item["paketid"] ) ."'
										and urutan_parameter = '". main::formatting_query_string( $item["urutan_parameter"] ) ."')
									)
									" 
								);
			$rs_paket = self::paket_parameter_reward( $arr_parameter );
			
			$arr_keterangan_paket_parameter="";
			while( $paket = sqlsrv_fetch_array( $rs_paket ) ){
				$arr_keterangan_paket_parameter[] = trim($paket["keterangan_paket_parameter"]);
				$this->arr_keterangan_paket[ $item["paketid"] ] = $paket["keterangan_paket"];
			}
			
			if( is_array($arr_keterangan_paket_parameter) && count($arr_keterangan_paket_parameter) > 0 )
				$this->arr_keterangan_paket_parameter[ $item["paketid"] ] = implode(", ", $arr_keterangan_paket_parameter);
			
			$this->arr_item_diskon[ $item["item_seq"] ][ $item["paketid"] ] = $item["diskon"];						
			
			$this->belum_ada_order = false;
			
		}
		
	}
	
}

?>