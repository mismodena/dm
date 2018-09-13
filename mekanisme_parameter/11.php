<?

// SATU CAMPAIGN HANYA UNTUK 1 JENIS ITEM PERDANA

class pembelian_item_perdana extends sql_dm{
	
	public $pembelian_item_perdana = true;
	
	function __construct( $obyek_dm, $arr_parameter ){
		
		// kumpulkan semua item id yg disetting untuk campaign item perdana
		$arr_item_perdana = array();
		foreach($obyek_dm->arr_item_dengan_paket as $data_paket){
			if( $data_paket["paketid"] != $arr_parameter["paketid"] ) continue;
			$arr_item_perdana[ $data_paket["item_seq"] ] =  "'" . $data_paket["item"] . "'";
		}
		
		// cari dealer ybs
		$data_dealer = sqlsrv_fetch_array( sql::execute("select dealer_id from [order] where order_id = '". main::formatting_query_string( $arr_parameter["order_id"] ) ."';") );
		//$data_dealer["dealer_id"] = "DA10112050B0";
		
		if( $GLOBALS["page"] == "simulasi.php" ) goto SKIP;
		
		// cek data item di dalam order masa lalu (tidak memperhatikan campaign)	
		$sql = "select b.item_id, sum(b.kuantitas) kuantitas from [order] a, order_item b 
			where a.order_id = b.order_id and a.user_id = b.user_id and b.item_id in (". implode(",", array_values( $arr_item_perdana ) ) .") 
			and a.dealer_id = '". $data_dealer["dealer_id"] ."' and a.order_id <> '". main::formatting_query_string( $arr_parameter["order_id"] ) .
			"' group by b.item_id;";
		$rs_cek_data_order_masa_lalu = sql::execute( $sql );		
		while( $cek_data_order_masa_lalu = sqlsrv_fetch_array( $rs_cek_data_order_masa_lalu ) )
			if( $cek_data_order_masa_lalu["kuantitas"] > 0 ) {$this->pembelian_item_perdana = false; goto SKIP; }

		// cek data item di dalam order saat ini, hanya untuk yang menggunakan campaign perdana ini (dengan mengabaikan item dengan campaign lain) --> tidak perlu cek item di order saat ini
		/*$sql = "select b.item_id, sum(b.kuantitas) kuantitas from [order] a, order_item b 
			where a.order_id = b.order_id and a.user_id = b.user_id and b.item_id in (". implode(",", array_values( $arr_item_perdana ) ) .") 
			and a.dealer_id = '". $data_dealer["dealer_id"] ."' and a.order_id = '". main::formatting_query_string( $arr_parameter["order_id"] ) ."' and b.paketid = '". main::formatting_query_string( $arr_parameter["paketid"] ) .
			"' group by b.item_id;";
		$rs_cek_data_order_saat_ini = sql::execute( $sql );
		while( $cek_data_order_saat_ini = sqlsrv_fetch_array( $rs_cek_data_order_saat_ini ) )
			if( $cek_data_order_saat_ini["kuantitas"] > 0 ) {	$this->pembelian_item_perdana = false; return; }				
		*/
		
		SKIP:
		
		// cek nilai nilai parameter		
		$arr_parameter__["a.paketid"] = array("=", "'". main::formatting_query_string( $arr_parameter["paketid"] ) ."'");
		$arr_parameter__["a.urutan_parameter"] = array("=", "'". main::formatting_query_string( $arr_parameter["urutan_parameter"] ) ."'");
		$rs_nilai_parameter = sqlsrv_fetch_array( self::paket_parameter_reward( $arr_parameter__ ) );
		$nilai_parameter = $rs_nilai_parameter["nilai_parameter"];
		$operator_parameter = $rs_nilai_parameter["operator"];

		if( $this->pembelian_item_perdana ) {
			
			$this->pembelian_item_perdana = $nilai_parameter != 0 ? $nilai_parameter : 1;
			
			if		( $operator_parameter == "<" ) 	$this->pembelian_item_perdana -= $this->pembelian_item_perdana;
			elseif	( $operator_parameter == "<=" ) 	$this->pembelian_item_perdana -= $this->pembelian_item_perdana;
			elseif	( $operator_parameter == ">" ) 	$this->pembelian_item_perdana += $this->pembelian_item_perdana;
			elseif	( $operator_parameter == ">=" ) 	$this->pembelian_item_perdana += $this->pembelian_item_perdana;
			elseif	( $operator_parameter == "=" ) 	$this->pembelian_item_perdana = $this->pembelian_item_perdana;
			elseif	( $operator_parameter == "<>" ) 	$this->pembelian_item_perdana += $this->pembelian_item_perdana;
			elseif	( $operator_parameter == "between" ) 	{
				list($bawah, $atas) = explode("-", $this->pembelian_item_perdana);		
				$this->pembelian_item_perdana = rand( $bawah, $atas );
			}
			
		}else {

			$this->pembelian_item_perdana = $nilai_parameter != 0 ? $nilai_parameter : 1;
			
			if		( $operator_parameter == "<" ) 	$this->pembelian_item_perdana += $this->pembelian_item_perdana;
			elseif	( $operator_parameter == "<=" ) 	$this->pembelian_item_perdana += $this->pembelian_item_perdana;
			elseif	( $operator_parameter == ">" ) 	$this->pembelian_item_perdana -= $this->pembelian_item_perdana;
			elseif	( $operator_parameter == ">=" ) 	$this->pembelian_item_perdana -= $this->pembelian_item_perdana;
			elseif	( $operator_parameter == "=" ) 	$this->pembelian_item_perdana += $this->pembelian_item_perdana;
			elseif	( $operator_parameter == "<>" ) 	$this->pembelian_item_perdana = $this->pembelian_item_perdana;
			elseif	( $operator_parameter == "between" ) 	{
				list($bawah, $atas) = explode("-", $this->pembelian_item_perdana);		
				$this->pembelian_item_perdana = $bawah + $atas;
			}
			
		}

	}
	
}

?>