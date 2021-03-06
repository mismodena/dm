<?

class sql_dm extends operator{
		
	static function browse_cart( $arr_parameter = array() ){
		$sql = "select b.*, rtrim(ltrim(b.item_id)) item_id, case when isnull(g.model, '') = '' then c.[desc] else g.model end item_nama, d.keterangan_paket,
			e.*, isnull(f.item_seq, '') order_diskon_item, a.gudang gudang_asal, b.gudang, f.kuantitas_diskon_item
			from [order] a 
			inner join order_item b on a.order_id = b.order_id and a.user_id = b.user_id
			inner join ". $GLOBALS["database_accpac"] ."..icitem c on b.item_id = c.itemno 
			left outer join paket d on b.paketid = d.paketid
			left outer join order_diskon_item f on b.order_id = f.order_id and b.user_id = f.user_id and b.item_seq = f.item_seq
			left outer join order_diskon e on f.order_id = e.order_id and f.user_id = e.user_id and f.diskon_id = e.diskon_id 
				and a.order_id = e.order_id and a.user_id = e.user_id
			left outer join mesdb.dbo.tbl_icitem g on c.itemno = g.itemno
			";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql  . " order by b.item_seq " );	}
		catch(Exception $e){$e->getMessage();}
	}		
	
	static function browse_cart_split( $arr_parameter = array() ){
		$sql = "select b.*, rtrim(ltrim(b.item_id)) item_id, case when isnull(g.model, '') = '' then c.[desc] else g.model end item_nama, d.keterangan_paket,
			e.*, isnull(f.item_seq, '') order_diskon_item, h.gudang gudang_asal, a.gudang
			from [order_split] a 
			inner join order_item_split b on dbo.sambung_order_id(a.order_id, a.order_id_split, '-') = dbo.sambung_order_id(b.order_id, b.order_id_split, '-') and a.user_id = b.user_id
			inner join ". $GLOBALS["database_accpac"] ."..icitem c on b.item_id = c.itemno 
			left outer join paket d on b.paketid = d.paketid
			left outer join order_diskon_item f on b.order_id = f.order_id and b.user_id = f.user_id and b.item_seq = f.item_seq
			left outer join order_diskon e on f.order_id = e.order_id and f.user_id = e.user_id and f.diskon_id = e.diskon_id 
				and a.order_id = e.order_id and a.user_id = e.user_id
			left outer join mesdb.dbo.tbl_icitem g on c.itemno = g.itemno
			left outer join [order] h on a.order_id = h.order_id and a.user_id = h.user_id
			";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql  . " order by b.item_seq " );	}
		catch(Exception $e){$e->getMessage();}
	}		
	
	static function browse_free_item_cart( $arr_parameter = array() ){
		$sql = "select * from order_diskon_freeitem ";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql );	}
		catch(Exception $e){$e->getMessage();}
	}

	static function browse_order_item( $arr_parameter = array() ){
		$sql = "select * from order_item ";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql );	}
		catch(Exception $e){$e->getMessage();}
	}

	static function cari_paket( $arr_parameter = array(), $untuk_simulasi = false ){				
		$sql = "select b.* from paket b, campaign c, periode d
			where 
			b.campaignid = c.campaignid and b.periodeid = c.periodeid and
			c.periodeid = d.periodeid and d.persetujuan = '1' ";

		if( !$untuk_simulasi && !defined("__MODE_SIMULASI__") )
			$sql .= " and b.aktif_paket = '1' and c.aktif_campaign = '1' and getdate() >= d.awal and getdate() <= d.akhir  ";
			
		if ( count($arr_parameter) > 0 )
			$sql .= " and " . sql::sql_parameter( $arr_parameter );
echo "<!-- $sql -->";
		try{		return sql::execute( $sql . " order by c.urutan_campaign" );	}
		catch(Exception $e){$e->getMessage();}		
	}
	
	static function browse_paket_area( $arr_parameter = array() ){
		$sql = "select a.*, case when b.textdesc <> '' then b.textdesc when c.namecust <> '' then c.namecust when d.kitchen <> '' then d.kitchen when e.professional <> '' then e.professional end paket_area  
			from paket_area a left outer join ".$GLOBALS["database_accpac"]."..ARGRO b on a.area = b.idgrp 
			left outer join ".$GLOBALS["database_accpac"]."..ARCUS c on a.area = C.idcust 
			left outer join (select 'KITCHEN' kitchen) d on a.area = d.kitchen 
			left outer join (select 'PROFESSIONAL' professional) e on a.area = e.professional ";
			
		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql . " order by paket_area " );	}
		catch(Exception $e){$e->getMessage();}					
	}
	
	static function browse_paket_parameter( $arr_parameter = array(), $untuk_simulasi = false  ){
		$sql = "select b.keterangan_paket, e.* from paket b, campaign c, periode d, paket_parameter e
			where 
			b.campaignid = c.campaignid and b.periodeid = c.periodeid and
			c.periodeid = d.periodeid and
			b.periodeid = e.periodeid and b.campaignid = e.campaignid and b.paketid = e.paketid and d.persetujuan = '1' ";

		if( !$untuk_simulasi && !defined("__MODE_SIMULASI__")  )
			$sql .= "  and b.aktif_paket = '1' and c.aktif_campaign = '1'  and getdate() >= d.awal and getdate() <= d.akhir  ";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " and " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql . " order by e.urutan_parameter" );	}
		catch(Exception $e){$e->getMessage();}		
	}
	
	static function browse_paket_parameter_item( $arr_parameter = array(), $untuk_simulasi = false ){
		$sql = "select f.* from paket b, campaign c, periode d, paket_parameter e, paket_parameter_item f
			where 
			b.campaignid = c.campaignid and b.periodeid = c.periodeid and
			c.periodeid = d.periodeid and
			b.periodeid = e.periodeid and b.campaignid = e.campaignid and b.paketid = e.paketid and d.persetujuan = '1' and 
			e.periodeid = f.periodeid and e.campaignid = f.campaignid and e.paketid = f.paketid and e.urutan_parameter = f.urutan_parameter ";
		
		if( !$untuk_simulasi && !defined("__MODE_SIMULASI__")  )
			$sql .= " and b.aktif_paket = '1' and c.aktif_campaign = '1' and getdate() >= d.awal and getdate() <= d.akhir  ";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " and " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql . " order by e.urutan_parameter" );	}
		catch(Exception $e){$e->getMessage();}		
	}
	
	static function browse_paket_reward_item( $arr_parameter = array(), $untuk_simulasi = false ){
		$sql = "select g.* from paket b, campaign c, periode d, paket_parameter e, paket_reward f, paket_reward_item g
			where 
			b.campaignid = c.campaignid and b.periodeid = c.periodeid and
			c.periodeid = d.periodeid and
			b.periodeid = e.periodeid and b.campaignid = e.campaignid and b.paketid = e.paketid and d.persetujuan = '1' and 
			e.periodeid = f.periodeid and e.campaignid = f.campaignid and e.paketid = f.paketid and e.urutan_parameter = f.urutan_parameter and 
			f.periodeid = g.periodeid and f.campaignid = g.campaignid and f.paketid = g.paketid and f.urutan_parameter = g.urutan_parameter and f.urutan_reward = g.urutan_reward ";
		
		if( !$untuk_simulasi && !defined("__MODE_SIMULASI__")  )
			$sql .= " and b.aktif_paket = '1' and c.aktif_campaign = '1' and getdate() >= d.awal and getdate() <= d.akhir  ";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " and " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql . " order by e.urutan_parameter" );	}
		catch(Exception $e){$e->getMessage();}		
	}
	
	static function browse_paket_item( $arr_parameter = array(), $untuk_simulasi = false ){
		$sql = "select case e.mode when 1 then /*'['+ltrim(rtrim(g.itemno)) + '] ' +*/ ltrim(rtrim(convert(varchar(max), (  case when isnull(h.model, '') = '' then g.[desc] else h.model end ) ))) when 2 then 'Sub kategori ' + f.sub_kategori + ' ('+ convert(varchar(50), f.kode_prefiks) +')'  end item 
			from paket b 
			inner join campaign c on b.campaignid = c.campaignid and b.periodeid = c.periodeid 
			inner join periode d on c.periodeid = d.periodeid
			inner join paket_item e on b.periodeid = e.periodeid and b.campaignid = e.campaignid and b.paketid = e.paketid 
			left outer join sub_kategori f on e.item = convert(varchar, f.sub_kategoriid) and e.mode = 2
			left outer join ". $GLOBALS["database_accpac"] ."..icitem g on e.item = g.itemno and e.mode =1
			left outer join mesdb.dbo.tbl_icitem h on g.itemno = h.itemno
			where 		 
			d.persetujuan = '1' ";
		
		if( !$untuk_simulasi && !defined("__MODE_SIMULASI__")  )
			$sql .= " and b.aktif_paket = '1' and c.aktif_campaign = '1'  and getdate() >= d.awal and getdate() <= d.akhir  ";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " and " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql . " order by e.item" );	}
		catch(Exception $e){$e->getMessage();}		
	}
	
	protected static function sql_browse_paket_per_item( $paketid = "" ){
		if( defined("__MODE_SIMULASI__") ) 
			return "select a.item, a.mode, b.* from paket_item a, paket b 
					where 
					a.paketid = b.paketid and a.campaignid = b.campaignid and a.periodeid = b.periodeid 
					and a.mode = 1 
					". ( $paketid != "" ? "and b.paketid = '". main::formatting_query_string( $paketid ) ."' " : "" )
					;
		
		return "select a.item, a.mode, b.* from paket_item a, paket b, campaign c, periode d, paket_area e, ". $GLOBALS["database_accpac"] ."..arcus f
			where 
			a.paketid = b.paketid and a.campaignid = b.campaignid and a.periodeid = b.periodeid and
			b.campaignid = c.campaignid and b.periodeid = c.periodeid and
			c.periodeid = d.periodeid and b.aktif_paket = '1' and c.aktif_campaign = '1' and
			getdate() >= d.awal and getdate() <= d.akhir and d.persetujuan = '1'  and
			e.paketid = b.paketid and e.campaignid = b.campaignid and e.periodeid = b.periodeid and
			( e.area = f.idcust  or ( e.area = f.idgrp /*and f.idcust not in ( select idcust from area_pengecualian where area = f.idgrp ) */) or (e.area = f.email1 and left(f.idcust, 1) = 'D' ) ) and 
			f.SWACTV = '1' 
			and f.idcust = '". main::formatting_query_string( $_SESSION["kode_dealer"] ) ."' 
			and a.mode = 1 " 
			. ( $paketid != "" ? "and b.paketid = '". main::formatting_query_string( $paketid ) ."' " : "" )
			;
	}
	
	protected static function sql_browse_paket_per_sub_kategori( $item = "", $paketid = "" ){
		if( defined("__MODE_SIMULASI__") ) 
			return "select a.item, a.mode, b.* from paket_item a, paket b, sub_kategori e 
					where 
					a.paketid = b.paketid and a.campaignid = b.campaignid and a.periodeid = b.periodeid and a.item = e.sub_kategoriid
					and a.mode = 2 "
			. ( $item != "" && $item != "%" ? "and substring('". main::formatting_query_string( $item ) ."', 1, 2) in (select * from dbo.[ufn_split_string](e.kode_prefiks, ','))" : "" )
			. ( $paketid != "" ? "and b.paketid = '". main::formatting_query_string( $paketid ) ."' " : "" )
			;
			
		return "select a.item, a.mode, b.* from paket_item a, paket b, campaign c, periode d, sub_kategori e, paket_area f, ". $GLOBALS["database_accpac"] ."..arcus g
			where 
			a.paketid = b.paketid and a.campaignid = b.campaignid and a.periodeid = b.periodeid and
			b.campaignid = c.campaignid and b.periodeid = c.periodeid and
			c.periodeid = d.periodeid and a.item = e.sub_kategoriid and b.aktif_paket = '1' and c.aktif_campaign = '1' and
			getdate() >= d.awal and getdate() <= d.akhir and d.persetujuan = '1' and
			f.paketid = b.paketid and f.campaignid = b.campaignid and f.periodeid = b.periodeid and
			( f.area = g.idcust  or ( f.area = g.idgrp /*and g.idcust not in ( select idcust from area_pengecualian where area = g.idgrp ) */) or (f.area = g.email1 and left(g.idcust, 1) = 'D' ) ) and 
			g.SWACTV = '1' 
			and g.idcust = '". main::formatting_query_string( $_SESSION["kode_dealer"] ) ."' 
			and a.mode = 2 "
			. ( $item != "" && $item != "%" ? "and substring('". main::formatting_query_string( $item ) ."', 1, 2) in (select * from dbo.[ufn_split_string](e.kode_prefiks, ','))" : "" )
			. ( $paketid != "" ? "and b.paketid = '". main::formatting_query_string( $paketid ) ."' " : "" )
			;
	}	
	
	private function cek_dealer_masuk_paket(){
		$sql = "select 1 from paket_area a, paket b, campaign c, periode d, ". $GLOBALS["database_accpac"] ."..arcus e 
			where
			a.paketid = b.paketid and a.campaignid = b.campaignid and a.periodeid = b.periodeid and
			b.campaignid = c.campaignid and b.periodeid = c.periodeid and
			c.periodeid = d.periodeid and b.aktif_paket = '1' and c.aktif_campaign = '1' and
			getdate() >= d.awal and getdate() <= d.akhir and d.persetujuan = '1' and 
			( a.area = e.idcust  or a.area = e.idgrp or (a.area = e.email1 and left(e.idcust, 1) = 'D' ) ) and e.SWACTV = '1'
			e.idcust = '". main::formatting_query_string( $_SESSION["kode_dealer"] ) ."' ";
	}
	
	// untuk mendapatkan item muncul di paket mana saja.. sebagai saran utk sales dalam pengambilan paket utk item yg dibeli
	// juga untuk mendapatkan item-item yang ada di dalam suatu paket (dipake di simulasi)
	static function browse_paket_per_item( $item, $paketid = "", $simulasi = false ){		
		$operator = "=";
		if( $simulasi || defined("__MODE_SIMULASI__ ") ) $operator = " like ";
		
		$sql = 		self::sql_browse_paket_per_item( $paketid )	. " and " . sql::sql_parameter( array( "a.item" => array( $operator, "'". main::formatting_query_string( $item ) ."'" ) ) ) 
				. 	" union "
				. 	self::sql_browse_paket_per_sub_kategori( $item, $paketid );
				
		$arr_idcust = array( "and g.idcust = '". main::formatting_query_string( $_SESSION["kode_dealer"] ) ."'", 
							"and f.idcust = '". main::formatting_query_string( $_SESSION["kode_dealer"] ) ."'",
							"getdate() >= d.awal and getdate() <= d.akhir and" );
		if( $simulasi ||  defined("__MODE_SIMULASI__ ") ) $sql = str_replace( $arr_idcust, "",  $sql);

		try{		return sql::execute( $sql );	}
		catch(Exception $e){$e->getMessage();}
	}	
	
	static function paket_parameter_reward( $arr_parameter ){
		$sql = "select a1.*,b.*,c.*, e.*, 
			a.urutan_parameter, a.nilai_parameter, a.lingkup, a.grup_parameter, a.keterangan_paket_parameter,
			d.urutan_reward, d.nilai_reward
			from 
			paket a1 left outer join paket_parameter a on a1.campaignid = a.campaignid and a1.paketid = a.paketid
			left outer join parameter b on a.parameterid = b.parameterid 
			left outer join operator c on a.operatorid = c.operatorid 
			left outer join paket_reward d on a.paketid = d.paketid and a.urutan_parameter = d.urutan_parameter 
			left outer join reward e on d.rewardid = e.rewardid
			";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql  . " order by a.urutan_parameter, d.urutan_reward " );	}
		catch(Exception $e){$e->getMessage();}
	}
		
	static function paket_sub_kategori( $arr_parameter = array() ){// cek sub kategori paket
		$sql = "
			/*select b.sub_kategori, b.kode_prefiks from paket_item a, sub_kategori b 
			where 
			( a.item = convert(varchar, b.sub_kategoriid) or SUBSTRING(a.item, 1, 2) in (select * from dbo.ufn_split_string(b.kode_prefiks, ',')) )*/
			
			select c.brand +'-'+ b.sub_kategori sub_kategori, b.kode_prefiks from paket_item a, sub_kategori b, brand c 
				where 
				b.brandid = c.brandid and
				( a.item = convert(varchar, b.sub_kategoriid) or SUBSTRING(a.item, 1, 2) in (select * from dbo.ufn_split_string(b.kode_prefiks, ',')) ) 
			";
			
		if ( count($arr_parameter) > 0 )
			$sql .= " and " . sql::sql_parameter( $arr_parameter );

		$rs_sub_kategori = sql::execute( $sql );
		$arr_sub_kategori = array();
		
		while( $sub_kategori = sqlsrv_fetch_array( $rs_sub_kategori ) ){
			
			if( array_key_exists( $sub_kategori["sub_kategori"], $arr_sub_kategori ) )
				$koma = ",";
			@$arr_sub_kategori[ $sub_kategori["sub_kategori"] ] .= @$koma . $sub_kategori["kode_prefiks"];
		}
		
		foreach( $arr_sub_kategori as $sub_kategori => $arr_sub_kategori )
			$return[ $sub_kategori ] = explode(",", $arr_sub_kategori);
		
		return $return;
	}
	
	static function jumlah_parameter_reward_paket( $arr_parameter ){
		$sql = "select a.grup_parameter, COUNT(a.paketid) jumlah_parameter, COUNT(b.paketid) jumlah_reward 
				from paket_parameter a left outer join paket_reward b 
					on a.periodeid = b.periodeid and a.campaignid = b.campaignid and a.paketid = b.paketid and a.urutan_parameter = b.urutan_parameter
				";
			if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql  . " group by a.grup_parameter order by a.grup_parameter " );	}
		catch(Exception $e){$e->getMessage();}
	}
	
// ########################################## TRANSAKSI SQL ###########################################################
	
	/*
	$arr_set = $arr_parameter = array("kolom" => "nilai")	
	*/
	static function update_order( $arr_set, $arr_parameter ){
		$sql = "update [order] set ". self::sql_parameter( $arr_set, "," ) ." where ". self::sql_parameter( $arr_parameter ) .";";
		return sql::execute($sql);
	}

	static function update_order_item( $arr_set, $arr_parameter ){
		$sql = "update order_item set ". self::sql_parameter( $arr_set, "," ) ." where ". self::sql_parameter( $arr_parameter ) .";";
		return sql::execute($sql);
	}
	
	static function update_order_diskon_freeitem( $arr_set, $arr_parameter ){
		$sql = "update order_diskon_freeitem set ". self::sql_parameter( $arr_set, "," ) ." where ". self::sql_parameter( $arr_parameter ) .";";
		return sql::execute($sql);
	}
	
	static function insert_order_item( $arr_col ){
		$sql = "insert into order_item (". implode(",", array_keys( $arr_col )) .") values(". implode(",", array_values( $arr_col )) .");";
		return sql::execute($sql);
	}
	
	static function hapus_order_item( $arr_parameter ){
		$sql = "delete order_item where ". self::sql_parameter( $arr_parameter ) .";";
		return sql::execute($sql);
	}	
	
	static function hapus_order( $order_id ){
		$sql = "delete from [order] where order_id = '". main::formatting_query_string( $order_id ) ."';";
		return sql::execute($sql);
	}
	
	static function hapus_item( $item_seq ){
		$sql = "delete from [order_item] where 
			order_id = '". main::formatting_query_string( $_SESSION["order_id"] ) ."' and 
			item_seq = '". main::formatting_query_string( $item_seq ) ."';";
		return sql::execute($sql);
	}
	
	static function hapus_campaign( $item_seq ){
		$sql = "update [order_item] set paketid = NULL, keterangan_order_item = NULL where 
			order_id = '". main::formatting_query_string( $_SESSION["order_id"] ) ."' and 
			item_seq = '". main::formatting_query_string( $item_seq ) ."';";
		return sql::execute($sql);		
	}
	
	static function terapkan_campaign( $item_seq, $paketid ){
		$sql = "update [order_item] set paketid = '". main::formatting_query_string( $paketid ) ."' where 
			order_id = '". main::formatting_query_string( $_SESSION["order_id"] ) ."' and 
			item_seq = '". main::formatting_query_string( $item_seq ) ."';";
		return sql::execute($sql);		
	}
	
	static function ubah_kuantitas( $item_seq, $qty ){
		if( !is_numeric($qty) || $qty <= 0 ) $qty = 1;
		$sql = "update [order_item] set kuantitas = '". main::formatting_query_string( $qty ) ."' where 
			order_id = '". main::formatting_query_string( $_SESSION["order_id"] ) ."' and 
			item_seq = '". main::formatting_query_string( $item_seq ) ."';";
		return sql::execute($sql);		
	}
	
	
}
?>