<?

/*	
mekanisme transaksi :
1. pilih dealer - otomatis bikin data di dm.order - generate $order_id - dapatkan diskon net dealer
2. sales masukin item ke dalam shopping cart
3. sistem deteksi paket parameter dengan metode self::browse_paket_per_item( $item ). 
4. sales memilih paket sesuai dengan saran yang didapatkan dari poin 3. 
	Cek paket.paket_berulang = 1 atau (paket.paket_berulang = 0 & paket belum digunakan oleh dealer)
		Update data order_detail.paketid sesuai dengan data paket yg dipilih untuk item tsb.
	
5. sistem menghitung paket parameter - reward dengan metode new perhitungan_otomatis($order_id, $user_id). 
	Apabila sesuai parameter, order_detail.urutan_parameter dan order_detail.diskon akan terupdate sesuai ketentuan paket.
*/

class perhitungan_otomatis extends sql_dm{

	public 	$arr_item_nama /* [itemid] = nama lengkap produk */
			,$arr_item /* [itemid] = array(harga net / item, kuantitas) */
			,$arr_item_paket /* [itemid] = paketid */
			,$arr_item_dengan_paket
			,$arr_item_non_paket /* [n] = itemid */
			,$arr_paket /* [paketid] = itemid */
			,$arr_paket_parameter /* [paketid] = urutan parameter terpakai / terakhir */
			,$arr_grup_paket_parameter /* [paketid] = grup dari urutan parameter terpakai / terakhir */
			,$arr_keterangan_paket_parameter /* [paketid] = keterangan dari urutan parameter terpakai / terakhir */
			,$arr_paket_reward /* [paketid] = reward (Rp / %) / item */
			,$arr_item_diskon /* [itemid] = array( [paketid] => diskon (Rp) ) -- diskon untuk sub total item */
			,$arr_keterangan_paket /* [paketid] =  keterangan paket */
			,$arr_free_item_paket /* [paketid] = array( itemid => kuantitas )*/
			,$arr_item_reward_non_diskon /*[itemid] = array( [paketid] => reward non diskon ) -- */
			,$belum_ada_order
			,$arr_item_cn
	;

	private function set_paket_parameter( $arr_parameter ){		
		list($paketid, $urutan_paket, $grup_paket) = $arr_parameter;
		$this->arr_paket_parameter[ $paketid ] = $urutan_paket;
		$this->arr_grup_paket_parameter[ $paketid ] = $grup_paket;
	}		
	
	protected function cek_parameter_vs_kondisi( $kondisi_transaksi, $rs_parameter_filter ){
		
		while( $parameter_filter = sqlsrv_fetch_array( $rs_parameter_filter ) ){

			if ( $parameter_filter["operator"] ==  "between" && self::operator_between( $kondisi_transaksi, $parameter_filter["nilai_parameter"] ) )	
					$this->set_paket_parameter ( array( $parameter_filter["paketid"], $parameter_filter["urutan_parameter"], $parameter_filter["grup_parameter"]) );

			elseif ( $parameter_filter["operator"] ==  "<=" && self::operator_kurang_dari_sama_dengan( $kondisi_transaksi, $parameter_filter["nilai_parameter"] ) )
				$this->set_paket_parameter ( array( $parameter_filter["paketid"], $parameter_filter["urutan_parameter"], $parameter_filter["grup_parameter"]) );
			
			elseif ( $parameter_filter["operator"] ==  "<" && self::operator_kurang_dari( $kondisi_transaksi, $parameter_filter["nilai_parameter"] ) )
				$this->set_paket_parameter ( array( $parameter_filter["paketid"], $parameter_filter["urutan_parameter"], $parameter_filter["grup_parameter"]) );
			
			elseif ( $parameter_filter["operator"] ==  ">=" && self::operator_lebih_dari_sama_dengan( $kondisi_transaksi, $parameter_filter["nilai_parameter"] ) )
				$this->set_paket_parameter ( array( $parameter_filter["paketid"], $parameter_filter["urutan_parameter"], $parameter_filter["grup_parameter"]) );

			elseif ( $parameter_filter["operator"] ==  ">" && self::operator_lebih_dari( $kondisi_transaksi, $parameter_filter["nilai_parameter"] ) )
				$this->set_paket_parameter ( array( $parameter_filter["paketid"], $parameter_filter["urutan_parameter"], $parameter_filter["grup_parameter"]) );
			
			elseif ( $parameter_filter["operator"] ==  "=" && self::operator_sama_dengan( $kondisi_transaksi, $parameter_filter["nilai_parameter"] ) )
				$this->set_paket_parameter ( array( $parameter_filter["paketid"], $parameter_filter["urutan_parameter"], $parameter_filter["grup_parameter"]) );
			
			elseif ( $parameter_filter["operator"] ==  "<>" && self::operator_tidak_sama_dengan( $kondisi_transaksi, $parameter_filter["nilai_parameter"] ) )
				$this->set_paket_parameter ( array( $parameter_filter["paketid"], $parameter_filter["urutan_parameter"], $parameter_filter["grup_parameter"]) );
			
			elseif ( $parameter_filter["operator"] ==  "in" && self::operator_in( $kondisi_transaksi, $parameter_filter["nilai_parameter"] ) )
				$this->set_paket_parameter ( array( $parameter_filter["paketid"], $parameter_filter["urutan_parameter"], $parameter_filter["grup_parameter"]) );
			
			elseif ( $parameter_filter["operator"] ==  "not in" && self::operator_not_in( $kondisi_transaksi, $parameter_filter["nilai_parameter"] ) )
				$this->set_paket_parameter ( array( $parameter_filter["paketid"], $parameter_filter["urutan_parameter"], $parameter_filter["grup_parameter"]) );
			
			else	
				return true;	
				
		}			
		
	}
	
	protected  function baca_sql_parameter( $arr_parameter ){

		$arr_rpl = explode("|", "#". implode("#|#", array_keys( $arr_parameter )) . "#");
		$arr_src = array_values( $arr_parameter );
	
		$sql = file_get_contents( MEKANISME_PARAMETER . $arr_parameter["parameterid"] . ".php" );
				
		$sql = str_replace( $arr_rpl, $arr_src, $sql );
		$kondisi_transaksi = sqlsrv_fetch_array( sql::execute($sql) );

		$return = $this->cek_parameter_vs_kondisi( 
					@$kondisi_transaksi[0] == "" ? 0 : $kondisi_transaksi[0], 
					self::paket_parameter_reward( 
						array(
							"a.paketid"=> array( "=", "'" . main::formatting_query_string($arr_parameter["paketid"]) . "'" ),
							"a.urutan_parameter"=> array( "=", "'" . main::formatting_query_string($arr_parameter["urutan_parameter"]) . "'" )
							)
					) 
				);
		
		return $return;
	}
	
	protected function persiapan_fungsi_parameter( $arr_parameter ){		

		if( !file_exists( MEKANISME_PARAMETER . $arr_parameter["parameterid"] . ".php") ) return false;
					
		include_once MEKANISME_PARAMETER . $arr_parameter["parameterid"] . ".php";

		$obyek_transaksi = new $arr_parameter["mekanisme_parameter"]( $this, $arr_parameter );

		$return  = $this->cek_parameter_vs_kondisi( 
					@$obyek_transaksi->$arr_parameter["mekanisme_parameter"], 
					self::paket_parameter_reward( 
						array(
							"a.paketid"=> array( "=", "'" . main::formatting_query_string($arr_parameter["paketid"]) . "'" ),
							"a.urutan_parameter"=> array( "=", "'" . main::formatting_query_string($arr_parameter["urutan_parameter"]) . "'" )
							) 
					) 
				);
				
		return $return;
	}
	
	protected function persiapan_fungsi_reward( $arr_parameter ){					

		foreach(  $arr_parameter["arr_itemid"] as $itemid ){

			$arr_item_dengan_paket = self::cari_index_array( $this->arr_item_dengan_paket, $itemid );
			
			foreach($arr_item_dengan_paket as $index_item_seq){
			
				$rs_reward = self::paket_parameter_reward( 
						array(
							"a.paketid"			=> array( "=", "'" . main::formatting_query_string($arr_parameter["paketid"]) . "'" ),
							"a.urutan_parameter"	=> array( "=", "'" . main::formatting_query_string($arr_parameter["urutan_parameter"]) . "'" ),
							)
					);		
				
				if( sqlsrv_num_rows( $rs_reward ) > 0 ){

					$this->keterangan_paket_parameter( $arr_parameter["paketid"] );
					
					// mekanisme untuk update order_detail.urutan_parameter = $this->arr_paket_parameter[ $parameter_filter["paketid"] ] where order_id = $arr_parameter["order_id"] and user_id = $arr_parameter["user_id"] and paketid = $parameter_filter["paketid"]
					$arr_set["urutan_parameter"] = array("=", "'" . main::formatting_query_string( $arr_parameter["urutan_parameter"] ) . "'");
					$arr_parameter_update["order_id"] = array("=", "'" . main::formatting_query_string( $arr_parameter["order_id"] ) . "'");
					$arr_parameter_update["user_id"] = array("=", "'" . main::formatting_query_string( $arr_parameter["user_id"] ) . "'");
					$arr_parameter_update["paketid"] = array("=", "'" . main::formatting_query_string( $arr_parameter["paketid"] ) . "'");
					self::update_order_item( $arr_set, $arr_parameter_update );	
				}
					
				$basis_harga = $this->arr_item_dengan_paket[ $index_item_seq ]["harga"] * $this->arr_item_dengan_paket[ $index_item_seq ]["kuantitas"];
				
				$this->arr_item_diskon[ $this->arr_item_dengan_paket[ $index_item_seq ]["item_seq"] ][ $arr_parameter["paketid"] ] = 0;
				$this->arr_item_cn[ $this->arr_item_dengan_paket[ $index_item_seq ]["item_seq"] ][ $arr_parameter["paketid"] ] = 0;
				$this->arr_item_reward_non_diskon[ $this->arr_item_dengan_paket[ $index_item_seq ]["item_seq"] ][ $arr_parameter["paketid"] ] = "";
				
				while( $reward = sqlsrv_fetch_array( $rs_reward ) ){		

					$basis_harga -= $this->arr_item_diskon[ $this->arr_item_dengan_paket[ $index_item_seq ]["item_seq"] ][ $arr_parameter["paketid"] ];
					
					if( file_exists( MEKANISME_REWARD . $reward["rewardid"] . ".php") ){
						
						include_once MEKANISME_REWARD . $reward["rewardid"] . ".php";
						
						$arr_parameter_fungsi = $arr_parameter + array(
											"item_seq"=> $this->arr_item_dengan_paket[ $index_item_seq ]["item_seq"], 
											"harga_per_unit" => $this->arr_item_dengan_paket[ $index_item_seq ]["harga"],
											"kuantitas" => $this->arr_item_dengan_paket[ $index_item_seq ]["kuantitas"],
											"basis_harga" => $basis_harga, 
											"nilai_reward" =>$reward["nilai_reward"] 
										);
						
						new $reward["mekanisme_reward"]( $this, $arr_parameter_fungsi );
						
					} else {
						$this->arr_item_diskon[ $this->arr_item_dengan_paket[ $index_item_seq ]["item_seq"] ][ $arr_parameter["paketid"] ] = 0;
						$this->arr_item_reward_non_diskon[ $this->arr_item_dengan_paket[ $index_item_seq ]["item_seq"] ][ $arr_parameter["paketid"] ] = "";
						$this->arr_item_cn[ $this->arr_item_dengan_paket[ $index_item_seq ]["item_seq"] ][ $arr_parameter["paketid"] ] = 0;
						
					}
					
				}			
				
				$diskon_rounded = main::number_format_dec( main::formatting_query_string( $this->arr_item_diskon[ $this->arr_item_dengan_paket[ $index_item_seq ]["item_seq"] ][ $arr_parameter["paketid"] ] ) );
				$diskon_rounded = str_replace( ",", "", $diskon_rounded );

				$diskon_rounded_cn = main::number_format_dec( main::formatting_query_string( $this->arr_item_cn[ $this->arr_item_dengan_paket[ $index_item_seq ]["item_seq"] ][ $arr_parameter["paketid"] ] ) );
				$diskon_rounded_cn = str_replace( ",", "", $diskon_rounded_cn );
				
				$arr_set["diskon"] = array("=", "'" . $diskon_rounded . "'");
				$arr_set["credit_note"] = array("=", "'" . $diskon_rounded_cn . "'");
				$arr_set["diskon_default"] = array("=", "'" . $diskon_rounded . "'");
				//$arr_set["keterangan_order_item"] = array("=", "'" . main::formatting_query_string( $this->arr_item_reward_non_diskon[ $this->arr_item_dengan_paket[ $index_item_seq ]["item_seq"] ][ $arr_parameter["paketid"] ] ) . "'");
				$arr_set["keterangan_order_item"] = array("=", "'" . ( $diskon_rounded <= 0 ? "" : main::formatting_query_string( $arr_parameter["paketid"] ) ) . "'");
				$arr_parameter_update["order_id"] = array("=", "'" . main::formatting_query_string( $arr_parameter["order_id"] ) . "'");
				$arr_parameter_update["user_id"] = array("=", "'" . main::formatting_query_string( $arr_parameter["user_id"] ) . "'");
				$arr_parameter_update["item_seq"] = array("=", "'" . main::formatting_query_string( $this->arr_item_dengan_paket[ $index_item_seq ]["item_seq"] ) . "'");
				$arr_parameter_update["item_id"] = array("=", "'" . main::formatting_query_string( $itemid ) . "'");
				$arr_parameter_update["paketid"] = array("=", "'" . main::formatting_query_string( $arr_parameter["paketid"] ) . "'");							
				self::update_order_item( $arr_set, $arr_parameter_update );
				
			}
			
		}
			
	}
	
	
	/* keterangan paket parameter yang digunakan */
	private function keterangan_paket_parameter( $paketid ){
		
		$this->arr_keterangan_paket_parameter[ $paketid ] = array();
		if( @$this->arr_grup_paket_parameter[ $paketid ] != "" )
			$arr_parameter_keterangan_paket_parameter = array(
				"a.paketid"=> array( "=", "'" . main::formatting_query_string( $paketid ) ."'" ),
				"a.grup_parameter" => array( "=", "'". main::formatting_query_string($this->arr_grup_paket_parameter[ $paketid ]) ."'" )
				);
		else
			$arr_parameter_keterangan_paket_parameter = array(
				"a.paketid"=> array( "=", "'" . main::formatting_query_string( $paketid ) ."'" ),
				"/*urutan_parameter + grup_parameter*/" => array("", "
						(a.urutan_parameter = '". main::formatting_query_string( @$this->arr_paket_parameter[ $paketid ] < 1 ? 1 : $this->arr_paket_parameter[ $paketid ] ) ."' or 
						a.grup_parameter = (select grup_parameter from paket_parameter where 
							paketid = '" . main::formatting_query_string($paketid) ."'
							and urutan_parameter = '". main::formatting_query_string( @$this->arr_paket_parameter[ $paketid ] < 1 ? 1 : $this->arr_paket_parameter[ $paketid ] ) ."')
						)
						" 
					)
				);

		$rs_keterangan_paket_parameter = self::paket_parameter_reward( $arr_parameter_keterangan_paket_parameter );
		$arr_keterangan_paket_parameter = "";
		while( $keterangan_paket_parameter = sqlsrv_fetch_array( $rs_keterangan_paket_parameter ) )
			if( trim($keterangan_paket_parameter["keterangan_paket_parameter"]) != "" )
				$arr_keterangan_paket_parameter[ $keterangan_paket_parameter["urutan_parameter"] ] = trim($keterangan_paket_parameter["keterangan_paket_parameter"]);

		if( is_array($arr_keterangan_paket_parameter) && count($arr_keterangan_paket_parameter) > 0 )
			$this->arr_keterangan_paket_parameter[ $paketid ] = implode(" <i style=\"text-decoration:underline\">dan/atau</i> ", $arr_keterangan_paket_parameter);
	}
	
	
	/* perhitungan reward per paket dengan syarat paketid per item sudah diketahui */
	private function scanning_parameter_reward($order_id, $user_id){
		
		if( !is_array( $this->arr_paket ) || count( $this->arr_paket ) <= 0 ){
			// reset semua order_item.diskon dan order_item.urutan_paket, klo paket & campaign tiba-tiba di-inaktifkan dari PM
			$arr_set["diskon"] = array("=", "''");
			$arr_set["diskon_default"] = array("=", "''");
			$arr_set["urutan_parameter"] = array("=", "''");
			$arr_parameter_update["order_id"] = array("=", "'" . main::formatting_query_string( $order_id ) . "'");
			$arr_parameter_update["user_id"] = array("=", "'" . main::formatting_query_string( $user_id ) . "'");
			self::update_order_item( $arr_set, $arr_parameter_update );
			return false;
		}

		foreach( $this->arr_paket as $paketid => $arr_itemid ){

			$rs_parameter = self::paket_parameter_reward( array("a.paketid"=> array( "=", "'" . main::formatting_query_string($paketid) ."'" ))  );
			$stop_loop = false;
			
			while( $parameter = sqlsrv_fetch_array( $rs_parameter ) ){

				// MEKANISME PENCOCOKAN KONDISI VS PARAMETER
				if( $parameter["metode_parameter"] == 1 ){ // perhitungan otomatis										
		
					// cek perbandingan jumlah parameter dibandingkan dengan rewardnya
					$parameter_reward_sama_jumlahnya = true;
					$cek_jumlah_parameter_reward = sqlsrv_fetch_array( self::jumlah_parameter_reward_paket( array(
							"a.paketid"=> array( "=", "'" . main::formatting_query_string( $paketid ) ."'" ), 
							"a.grup_parameter"=> array( "=", "'" . main::formatting_query_string( $parameter["grup_parameter"] ) ."'" ), 
							) 
						) );
					if( $cek_jumlah_parameter_reward["jumlah_reward"] != $cek_jumlah_parameter_reward["jumlah_parameter"] )	$parameter_reward_sama_jumlahnya = false;
							
					if( $stop_loop && !$parameter_reward_sama_jumlahnya ) continue;	
					
					$arr_parameter_fungsi = array( 
								"order_id" => $order_id, 
								"user_id" => $user_id,								
								"paketid" => $paketid, 								
								"parameterid"=>$parameter["parameterid"] , 
								"urutan_parameter"=>$parameter["urutan_parameter"] , 
								"mekanisme_parameter" => $parameter["mekanisme_parameter"]
								);										
					
					if( $parameter["otomasi_parameter"] == 1 ){ // sql logic					
						if( $this->$parameter["mekanisme_parameter"]($arr_parameter_fungsi ) ) $stop_loop = true;
						else	$stop_loop = $stop_loop && !$parameter_reward_sama_jumlahnya ? $stop_loop :  false;

					}elseif( $parameter["otomasi_parameter"] == 2 ){ // php logic
						if( $this->persiapan_fungsi_parameter( $arr_parameter_fungsi ) ) $stop_loop = true;
						else	$stop_loop = $stop_loop && !$parameter_reward_sama_jumlahnya ? $stop_loop :  false;
					}
					
					if( @$grup_parameter["grup_parameter"] !=  $parameter["grup_parameter"]){
						$grup_parameter["grup_parameter"] = $parameter["grup_parameter"];
						$grup_parameter["stop_loop"] = $stop_loop;
					}else{
						if( $grup_parameter["stop_loop"] )	$stop_loop = true;
					}

					// khusus urutan parameter no. 1 .. pada bagian operator ( >= ) dan nilai parameter ( 0 ) VS  ( <= ) dan nilai parameter ( 10.000.000 )  = biar hasilnya sama
					if( $parameter["rewardid"] != "" ){
						if( $parameter["urutan_parameter"] == 1 && $stop_loop = false )	$stop_loop = true;
					}

					if( $stop_loop ) continue;										
					
				}
				
				// MEKANISME REWARDING
				if( $parameter["urutan_parameter"] != @$this->arr_paket_parameter[ $paketid ] && @$this->arr_paket_parameter[ $paketid ] != "") continue;
					
				if( $parameter["metode_reward"] == 1 ){ // perhitungan otomatis														
					if( $parameter["otomasi_reward"] == 2 ){ // php logic
						$arr_parameter_fungsi = array( 
								"paketid" => $paketid, 								
								"paket_berulang" => $parameter["paket_berulang"], 																
								"urutan_parameter" => @$this->arr_paket_parameter[ $paketid ],
								"arr_itemid" => $arr_itemid,
								"order_id" => $order_id, 
								"user_id" => $user_id
								);
						$this->persiapan_fungsi_reward( $arr_parameter_fungsi );
					}
				}
				
			}

		}		
		
	}
	
	// untuk penentuan paket utk => item yg ada di dalam satu paket (penentuan otomatis) dan item yg ada di dalam lebih dari satu paket (pemilihan paket oleh sales)
	function __construct($order_id, $user_id, $paketid = "", $untuk_simulasi = false){
			
		$arr_parameter = array( 
			"a.order_id" => array("=", "'". main::formatting_query_string( $order_id ) ."'"), 
			"a.user_id" => array("=", "'". main::formatting_query_string( $user_id ) ."'"),
			"b.harga" => array(">", 0)
			);			
		if( $paketid != "" ) $arr_parameter["b.paketid"] = array("=", "'". main::formatting_query_string( $paketid ) ."'") ;
		$rs = self::browse_cart( $arr_parameter );
		
		if( sqlsrv_num_rows($rs) <= 0 ) {$this->belum_ada_order=true;return false;}
		$this->belum_ada_order=false;
		
		while( $data = sqlsrv_fetch_array( $rs ) ){						
			
			$this->arr_item_nama[ $data["item_id"] ] = $data["item_nama"];
			
			if( $data["paketid"] == "" ){				
				
				// saran paket
				$arr_saran_paket = array();
				$rs_saran_paket = self::browse_paket_per_item( $data["item_id"], $untuk_simulasi );
				while( $saran_paket = sqlsrv_fetch_array( $rs_saran_paket ) ){
					$arr_saran_paket[ $saran_paket["paketid"] ] = $saran_paket["keterangan_paket"];
				}
				
				$this->arr_item_non_paket[] = array( "item_seq" => $data["item_seq"], "item" => $data["item_id"], "harga" => $data["harga"], "kuantitas" => $data["kuantitas"], "saran_paket" => $arr_saran_paket );
				
				continue;
			}
			
			$this->arr_item_dengan_paket[] = array( "item_seq" => $data["item_seq"], "item" => $data["item_id"], "harga" => $data["harga"], "kuantitas" => $data["kuantitas"], "paketid" => $data["paketid"]);
			
			$arr_parameter = array(
					"b.paketid" => array("=", "'". main::formatting_query_string( trim($data["paketid"]) ) ."'")
				);				
			$rs_paket = self::cari_paket( $arr_parameter , $untuk_simulasi);
			
			if( is_array( $this->arr_item ) && count( $this->arr_item ) > 0 && array_key_exists( $data["item_id"], $this->arr_item ) )
				$this->arr_item[ $data["item_id"] ]["kuantitas"] += $data["kuantitas"];
			else
				$this->arr_item[ $data["item_id"] ] = array("harga" => $data["harga"], "kuantitas" => $data["kuantitas"]);
			
			if( sqlsrv_num_rows( $rs_paket ) > 0 ){

				while( $paket = sqlsrv_fetch_array( $rs_paket ) ){					
					
					$this->arr_item_paket[ $data["item_id"] ][] = $paket["paketid"];
					$this->arr_paket[ $paket["paketid"] ][] = $data["item_id"];
					$this->arr_keterangan_paket[ $paket["paketid"] ]= $paket["keterangan_paket"];
					
				}
				
			}

		}
		
		// reset diskon dan keterangan
		$arr_set["diskon"] = array("=", "'0'");
		$arr_set["diskon_default"] = array("=", "'0'");
		$arr_set["keterangan_order_item"] = array("=", "NULL");
		$arr_parameter_update["order_id"] = array("=", "'" . main::formatting_query_string( $order_id ) . "'");
		$arr_parameter_update["user_id"] = array("=", "'" . main::formatting_query_string( $user_id ) . "'");
		if( $paketid != "" ) $arr_parameter_update["paketid"] = array("=", "'". main::formatting_query_string( $paketid ) ."'") ;
		self::update_order_item( $arr_set, $arr_parameter_update );
		
		// reset free item dan keterangan
		unset( $arr_parameter_update );
		$arr_parameter_update["order_id"] = array("=", "'" . main::formatting_query_string( $order_id ) . "'");
		$arr_parameter_update["user_id"] = array("=", "'" . main::formatting_query_string( $user_id ) . "'");
		$arr_parameter_update["harga"] = array("=", "'0'");
		if( $paketid != "" ) $arr_parameter_update["paketid"] = array("=", "'". main::formatting_query_string( $paketid ) ."'") ;
		self::hapus_order_item( $arr_parameter_update );
		
		// scan ulang nilai diskonnya
		$this->scanning_parameter_reward($order_id, $user_id, $untuk_simulasi);
		
		// cek keterangan paket ulang
		if( @$this->arr_keterangan_paket_parameter[ $paketid ] == "" )		$this->keterangan_paket_parameter( $paketid );
		
		// entri khusus free item
		if( is_array( $this->arr_free_item_paket ) && count( array_keys($this->arr_free_item_paket) > 0 ) ){						
			
			foreach( $this->arr_free_item_paket as $paketid => $arr_item_kuantitas ){
				foreach( $arr_item_kuantitas as $item => $kuantitas ){
					
					unset( $arr_data );
					$arr_data["order_id"] = "'" . main::formatting_query_string( $_SESSION["order_id"]) . "'";
					$arr_data["user_id"] = "'" . main::formatting_query_string( $_SESSION["sales_id"]) . "'";
					$arr_data["item_seq"] = "case when (select isnull(max(item_seq)+1, 0) from order_item where 
							order_id = '". main::formatting_query_string( $_SESSION["order_id"] ) ."' and 
							user_id = '". main::formatting_query_string( $_SESSION["sales_id"] ) ."') = 0 then 1
							else
							(select max(item_seq)+1 from order_item where 
							order_id = '". main::formatting_query_string( $_SESSION["order_id"] ) ."' and 
							user_id = '". main::formatting_query_string( $_SESSION["sales_id"] ) ."') end";
					$arr_data["item_id"] = "'". main::formatting_query_string( $item ) ."'";
					$arr_data["harga"] = "'0'";
					$arr_data["kuantitas"] = "'". main::formatting_query_string( $kuantitas ) ."'";
					$arr_data["keterangan_order_item"] = "'". main::formatting_query_string( $paketid ) ."'";
					$arr_data["paketid"] = "'". main::formatting_query_string( $paketid ) ."'";
					//$arr_data["gudang"] = "'". main::formatting_query_string( $_SESSION["cabang"] ) ."'";
					$arr_data["gudang"] = " (select top 1 gudang from order_item a,
									(select MAX(kuantitas) kuantitas, order_id, user_id, item_seq from [order_item] where order_id='" . main::formatting_query_string( $_SESSION["order_id"]) . "' 
										and paketid = '". main::formatting_query_string( $paketid ) ."' and harga * kuantitas <> diskon group by order_id, user_id, item_seq) b
									where a.order_id = b.order_id and a.user_id = b.user_id and a.item_seq = b.item_seq) ";
					
					self::insert_order_item( $arr_data );
					
				}
			}
			
			// tambahkan daftar nama item untuk item free
			$arr_parameter = array( 
				"a.order_id" => array("=", "'". main::formatting_query_string( $order_id ) ."'"), 
				"a.user_id" => array("=", "'". main::formatting_query_string( $user_id ) ."'"),
				"b.harga" => array("=", 0)
				);			
			$rs = self::browse_cart( $arr_parameter );
			while( $data = sqlsrv_fetch_array( $rs ) )
				$this->arr_item_nama[ $data["item_id"] ] = $data["item_nama"];
			
		}
		
	}
}

?>