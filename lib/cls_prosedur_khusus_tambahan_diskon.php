<?

class prosedur_khusus_tambahan_diskon extends tambahan_diskon{
	
	protected 
		$persentase_budget_bisa_digunakan_bq_diskon = 0.7,
		$persentase_budget_bisa_digunakan_bq_freeitem = 1,
		$persentase_budget_bisa_digunakan_tq = 1
	;
	
	static function daftar_order_diskon_bqtq( $order_id = "", $arr_parameter, $arr_kolom = "", $group_by = "", $bqtq = "" ){
		
		$tabel = "order_diskon_bqtq a  ";
		if( $order_id != "" )
			$tabel = "dbo.ufn_daftar_order_item". ($bqtq != "" ? "" : "_bqtq") ."(". $order_id .") a0 
						inner join order_diskon_bqtq a  on a.order_id = a0.order_id and a.user_id = a0.user_id and 
						((a0.item_id = a.item_bqtq and a0.jenis_diskon = 1) or (convert(varchar(50), a0.item_seq) = a.item_bqtq and a0.jenis_diskon = 0))
						and a.diskon_id in ( select * from dbo.ufn_split_string(a0.diskon_id_all, ',' ) )";
						
		$sql = "select " . ( $arr_kolom != "" ? $arr_kolom :  "*" ) ."
			from 
			". $tabel ."
			inner join order_diskon a1 on a.order_id = a1.order_id and a.user_id = a1.user_id and a.diskon_id = a1.diskon_id
			inner join diskon a2 on a.diskon_id = a2.diskon_id
			inner join [order] a3 on a1.order_id = a3.order_id and a1.user_id = a3.user_id
			left outer join order_diskon_item b on a.order_id = b.order_id and a.user_id = b.user_id and a.item_bqtq = convert(varchar(50), b.item_seq) and a.mode_bqtq = 1 
			left outer join order_diskon_freeitem c on a.order_id = c.order_id and a.user_id = c.user_id and a.item_bqtq = c.item_id and a.mode_bqtq = 2
			";

		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		$sql .= trim($group_by) != "" ? " group by " . $group_by : "";

		try{		return sql::execute( $sql  );	}
		catch(Exception $e){$e->getMessage();}
	}
	
	static function saldo_bqtq( $dealer_id, $net_kecuali_order_id = "", $persentase_saldo_bisa_digunakan = 1 ){
		
		$prosedur_bqtq = new prosedur_khusus_tambahan_diskon("");
		
		$kolom = "*";
		//$kolom = " a.idCust, ( ". $persentase_saldo_bisa_digunakan ." * ( a.bqAvail - isnull(d.pemakaian_bq_freeitem, 0) ) ) - isnull(b.pemakaian_bq_diskon, 0) bqAvail, 
		//						". $persentase_saldo_bisa_digunakan . " * ( a.tqAvail - isnull(c.pemakaian_tq, 0) ) tqAvail ";
		
		$kolom = " a.idCust, a.bqAvail - isnull(d.pemakaian_bq_freeitem, 0) - isnull(b.pemakaian_bq_diskon, 0)   bqAvail, 
								( a.tqAvail - isnull(c.pemakaian_tq, 0) ) tqAvail ";
		
		if( $net_kecuali_order_id ) 
			$sql_parameter = "and a.order_id <> ". $net_kecuali_order_id ;
		
		$sql = "select ". $kolom ." from ". __SERVER_DISKON_KHUSUS__ .".dbo.getBQTQBalance((select grup_cabang from [user] where user_id = '". main::formatting_query_string( $_SESSION["sales_id"] ) ."'), ". $dealer_id . ") a ";				
		$sql .= "left outer join (
							select sum(diskon_bqtq) / ". $prosedur_bqtq->persentase_budget_bisa_digunakan_bq_diskon ." pemakaian_bq_diskon, 'bq' jenis_budget from order_diskon_bqtq a, order_diskon b, [order] c, [user] d, [user] e 
								where 
							a.order_id = b.order_id and a.user_id = b.user_id and a.diskon_id = b.diskon_id and 
							b.order_id = c.order_id and b.user_id = c.user_id and c.kirim <> 1 and
							c.user_id = d.user_id and d.bm = e.kode_sales and e.user_id = '". main::formatting_query_string( $_SESSION["sales_id"] ) ."' and
							a.diskon_id in (1)  $sql_parameter
							) b on 1=1
				left outer join (
							select sum(diskon_bqtq) / ". $prosedur_bqtq->persentase_budget_bisa_digunakan_tq ."pemakaian_tq, 'tq' jenis_budget from order_diskon_bqtq a, order_diskon b, [order] c 
								where 
							a.order_id = b.order_id and a.user_id = b.user_id and a.diskon_id = b.diskon_id and 
							b.order_id = c.order_id and b.user_id = c.user_id and c.kirim <> 1 and c.dealer_id = ". $dealer_id ." and
							a.diskon_id in (14) $sql_parameter
							) c on 1=1
				left outer join (
							select sum(diskon_bqtq) / ". $prosedur_bqtq->persentase_budget_bisa_digunakan_bq_freeitem ." pemakaian_bq_freeitem, 'bq' jenis_budget from order_diskon_bqtq a, order_diskon b, [order] c, [user] d, [user] e
								where 
							a.order_id = b.order_id and a.user_id = b.user_id and a.diskon_id = b.diskon_id and 
							b.order_id = c.order_id and b.user_id = c.user_id and c.kirim <> 1 and
							c.user_id = d.user_id and d.bm = e.kode_sales and e.user_id = '". main::formatting_query_string( $_SESSION["sales_id"] ) ."' and
							a.diskon_id in (13)  $sql_parameter
							) d on 1=1
				";

		try{		return sqlsrv_fetch_array( sql::execute( $sql  ) );	}
		catch(Exception $e){$e->getMessage();}		
	}
	
	static function saldo( $dealer_id ){
		if( @$_SESSION["sales_id"] != "" )
			$sql = "select a.idCust, a.bqAvail, a.tqAvail from ". __SERVER_DISKON_KHUSUS__ .".dbo.getBQTQBalance((select grup_cabang from [user] where user_id = '". main::formatting_query_string( $_SESSION["sales_id"] ) ."'), ". $dealer_id . ") a ";			
		else
			$sql = "select $dealer_id, '===' bqAvail, '===' tqAvail";
		try{		return sqlsrv_fetch_array( sql::execute( $sql  ) );	}
		catch(Exception $e){$e->getMessage();}				
	}
	
	static function pemakaian_saldo( $order_id, $mode = "bq", $is_order_ini = true ){
		
		$prosedur_bqtq = new prosedur_khusus_tambahan_diskon("");
		
		$operator = " = ";
		if( !$is_order_ini ) $operator = "<>";
		
		if( $mode == "bq_diskon" )
			$sql = "select sum(diskon_bqtq) / ". $prosedur_bqtq->persentase_budget_bisa_digunakan_bq_diskon ." pemakaian, 'bq' jenis_budget from order_diskon_bqtq a, order_diskon b, [order] c, [user] d 
								where 
							a.order_id = b.order_id and a.user_id = b.user_id and a.diskon_id = b.diskon_id and 
							b.order_id = c.order_id and b.user_id = c.user_id /*and c.kirim <> 1*/ and c.user_id = d.user_id and
							a.diskon_id in (1) and a.order_id ". $operator ." ". $order_id . ( $operator == "<>" ? " and c.kirim <> 1 and d.grup_cabang in (select y.grup_cabang from [order] x, [user] y where x.user_id = y.user_id and x.order_id = ". $order_id ." )" : "" );
		
		elseif( $mode == "bq_freeitem" )
			$sql = "select sum(diskon_bqtq) / ". $prosedur_bqtq->persentase_budget_bisa_digunakan_bq_freeitem ." pemakaian, 'bq' jenis_budget from order_diskon_bqtq a, order_diskon b, [order] c, [user] d  
								where 
							a.order_id = b.order_id and a.user_id = b.user_id and a.diskon_id = b.diskon_id and 
							b.order_id = c.order_id and b.user_id = c.user_id /*and c.kirim <> 1*/ and c.user_id = d.user_id and
							a.diskon_id in (13) and a.order_id ". $operator ." ". $order_id . ( $operator == "<>" ? " and c.kirim <> 1 and d.grup_cabang in (select y.grup_cabang from [order] x, [user] y where x.user_id = y.user_id and x.order_id = ". $order_id ." )" : "" );
		
		elseif( $mode == "tq" )
			$sql = "select sum(diskon_bqtq) / ". $prosedur_bqtq->persentase_budget_bisa_digunakan_tq ." pemakaian, 'bq' jenis_budget from order_diskon_bqtq a, order_diskon b, [order] c 
								where 
							a.order_id = b.order_id and a.user_id = b.user_id and a.diskon_id = b.diskon_id and 
							b.order_id = c.order_id and b.user_id = c.user_id /*and c.kirim <> 1*/ and
							a.diskon_id in (14) and a.order_id ". $operator ." ". $order_id . ( $operator == "<>" ? " and c.kirim <> 1 and c.dealer_id = (select dealer_id from [order] where order_id=". $order_id .")" : "" );

		try{		return sqlsrv_fetch_array( sql::execute( $sql  ) );	}
		catch(Exception $e){$e->getMessage();}						
	}
	
	static function kirim_data_ke_bqtq( $order_id ){
		
		$prosedur_bqtq = new prosedur_khusus_tambahan_diskon("");
		
		$sql = "exec dbo.usp_entri_bqtq '". main::formatting_query_string( $order_id ) ."', '". $prosedur_bqtq->persentase_budget_bisa_digunakan_bq_diskon ."';";
		sql::execute( $sql );
	}
	
	static function front_margin_dc_charge( $data_dealer ){
		$front_margin_dc_charge = 27;
			// dapatin nilai front margin / DC charge
		$sql = "select b.*, e.* from fpp..ms_tradingTerm a, fpp..ms_tradingTermDetail b, ". $GLOBALS["database_accpac"] ."..ARCUS c, [order] d, diskon e 
						where a.termNo = b.termID and (a.idCust = c.IDCUST or a.idCust = c.IDNATACCT) and
						b.tradCode = 'B32111' and GETDATE() between a.periodStart and a.periodEnd and e.diskon_id = $front_margin_dc_charge and 
						c.IDCUST = d.dealer_id and d.order_id='". main::formatting_query_string( $data_dealer["order_id"] ) ."';";
		$front_margin = sqlsrv_fetch_array( sql::execute( $sql ) );
		return $front_margin;
	}
	
	static function pengurangan_net_item_dealer_modern($identifikasi_dealer /* array(0=>idcust, 1=>idgrp) */ , $net_dealer){
		//$identifikasi_dealer = trim($identifikasi_dealer);
		$sql = "select b.*, e.UNITPRICE pricelist, convert(bigint, e.unitprice) * (100 - $net_dealer + b.pengurangan_net) / 100 netprice_baru from pengurangan_net_item_dealer_modern b 
				inner join ". $GLOBALS["database_accpac"] ."..ICPRIC d on b.item_id = d.ITEMNO 				
				left join ". $GLOBALS["database_accpac"] ."..ICPRICP e on d.ITEMNO = e.ITEMNO and d.PRICELIST = e.PRICELIST and d.CURRENCY = e.CURRENCY   
				where 
				(
					('". main::formatting_query_string( trim($identifikasi_dealer[1]) ) ."' in (select * from dbo.ufn_split_string(dealer, ',')) and mode_dealer = 0) or
					('". main::formatting_query_string( trim($identifikasi_dealer[0]) ) ."' = dealer and mode_dealer = 1 )
				) and
				'". main::formatting_query_string( $net_dealer ) ."' in (select * from dbo.ufn_split_string(diskon_dealer_awal, ',')) and
				aktif = 1 and 
				DPRICETYPE = 1 and e.CURRENCY = 'IDR' and d.pricelist='STD'  ";
		$rs = sql::execute( $sql );
		while( $data = sqlsrv_fetch_array( $rs ) )
			$return[ trim( $data["item_id"] ) ] = $data["netprice_baru"];
		return $return;
	}
	
	static function pengurangan_net_item_dealer_modern_bertingkat($identifikasi_dealer, $net_dealer){
		//$identifikasi_dealer = trim($identifikasi_dealer);
		$sql = "select b.*, e.UNITPRICE pricelist, convert(bigint, e.unitprice) * (100 - dbo.persentase_diskon_bertingkat_2( $net_dealer, b.tambahan_net ) ) / 100 netprice_baru from penambahan_net_dealer b 
				inner join ". $GLOBALS["database_accpac"] ."..ICPRIC d on b.item_id = d.ITEMNO 				
				left join ". $GLOBALS["database_accpac"] ."..ICPRICP e on d.ITEMNO = e.ITEMNO and d.PRICELIST = e.PRICELIST and d.CURRENCY = e.CURRENCY   
				where 
				dealer = '". main::formatting_query_string( trim($identifikasi_dealer) ) ."' and aktif = 1 and 
				DPRICETYPE = 1 and e.CURRENCY = 'IDR' and d.pricelist='STD'  ";
		$rs = sql::execute( $sql );
		while( $data = sqlsrv_fetch_array( $rs ) )
			$return[ trim( $data["item_id"] ) ] = $data["netprice_baru"];
		
		if( is_array( $return ) && count( $return ) > 0 ) return $return;
		
		$return = 0;
		// tidak ada per item, cek untuk ALL item
		$sql = "select * from penambahan_net_dealer where dealer = '". main::formatting_query_string( trim($identifikasi_dealer) ) ."' and aktif = 1 and item_id = 'ALL' ";
		$rs = sql::execute( $sql );
		while( $data = sqlsrv_fetch_array( $rs ) )
			$return = $data["tambahan_net"];
		
		return $return;
	}
	
	// ########################################## TRANSAKSI SQL ###########################################################

	static function insert_order_diskon_bqtq( $arr_col ){
		$sql = "insert into order_diskon_bqtq (". implode(",", array_keys( $arr_col )) .") values(". implode(",", array_values( $arr_col )) .");";
		return sql::execute($sql);
	}
	
	static function update_order_diskon_bqtq( $arr_set, $arr_parameter ){
		$sql = "update order_diskon_bqtq set ". self::sql_parameter( $arr_set, "," ) ." where ". self::sql_parameter( $arr_parameter ) .";";
		return sql::execute($sql);
	}
	
	static function hapus_order_diskon_bqtq( $arr_parameter ){
		$sql = "delete order_diskon_bqtq where ". self::sql_parameter( $arr_parameter ) .";";
		return sql::execute($sql);
	}
	
}

?>