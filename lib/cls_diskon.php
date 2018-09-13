<?

class tambahan_diskon extends order{
	
	static function daftar_order( $arr_parameter = array() ){
		
		$sql = "select a.*, b.*,
			ltrim(rtrim(idcust)) idcust, ltrim(rtrim(namecust)) namecust,  '['+ltrim(rtrim(textsnam))+'] '+ltrim(rtrim(textstre1))+' '+ltrim(rtrim(textstre2))+' '+ltrim(rtrim(textstre3))+' '+ltrim(rtrim(textstre4)) addr, namecity
			from [order] a, [user] b,  ". $GLOBALS["database_accpac"] ."..arcus c
			where a.user_id = b.user_id and a.dealer_id = c.idcust ";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " and " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql  . " order by a.tanggal " );	}
		catch(Exception $e){$e->getMessage();}
	}
	
	static function kolom_status_persetujuan(){
		
		return "case 	when isnull( c.disetujui_oleh, '' ) = '' then  
					case 
						when isnull(c.disetujui, -1) = -1 then 0 /*belum dikirimkan*/
						else	4 /* tidak memerlukan persetujuan, disetujui*/
					end
					when isnull( c.disetujui_oleh, '' ) <> '' and isnull(c.disetujui, -1) = -1 then 1 /*belum mendapatkan persetujuan*/
					when isnull( c.disetujui_oleh, '' ) <> '' and c.disetujui = 1 then 2 /*disetujui*/
					when isnull( c.disetujui_oleh, '' ) <> '' and c.disetujui = 0 then 3 /*tidak disetujui*/
			end status_persetujuan";
		
	}

	static function daftar_tambahan_diskon( $arr_parameter = array() , $order_id ="", $browse_diskon = false ){
		$sql = "select a.*, b.nilai_diskon, b.keterangan_order_diskon, isnull(b.diskon_id, '') order_diskon, b.lampiran_order_diskon ";
		
		if( $order_id != "" && !$browse_diskon ) $sql .= "
			,
			c.disetujui, c.disetujui_tanggal, c.disetujui_oleh, c.disetujui_keterangan,  " . self::kolom_status_persetujuan();
		else
			$sql .= " , '' disetujui, '' disetujui_tanggal, '' disetujui_oleh, '' disetujui_keterangan, '' status_persetujuan ";
		
		$sql .= "
			from diskon a left outer join order_diskon b on a.diskon_id = b.diskon_id and 
				a.aktif_diskon = '1'";		
		
		if( $order_id != "" && $browse_diskon )
			$sql .= " and b.order_id = '". main::formatting_query_string( $order_id ) ."'  ";
		
		if( $order_id != "" && !$browse_diskon ) $sql .= " 				
			left outer join order_diskon_approval c on b.order_id = c.order_id and b.user_id = c.user_id and b.diskon_id = c.diskon_id 
			left outer join dbo.ufn_diskon_approval('". main::formatting_query_string( $order_id ) ."', 0) d on 
				c.order_id = d.order_id and c.user_id = d.user_id and c.diskon_id = d.diskon_id and c.urutan = d.urutan 
			inner join dbo.ufn_diskon_approval_berjenjang('". main::formatting_query_string( $order_id ) ."') e on
				e.diskon_id = (case when isnull(c.diskon_id, '') = '' then a.diskon_id  else c.diskon_id end) and isnull(e.disetujui_oleh, '') = (case when isnull(c.disetujui_oleh, '') = '' then ''  else c.disetujui_oleh end)
				/*c.disetujui_oleh = e.disetujui_oleh and a.diskon_id = e.diskon_id*/	";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql  . " order by isnull(a.urutan, 4) desc, diskon " );	}
		catch(Exception $e){$e->getMessage();}
	}
	
	static function nilai_tambahan_diskon( $order_id,  $nominal_order = 1, $arr_parameter = array() ){
		if( $nominal_order == "" ) $nominal_order = 1;
		$sql = "
			select x.*, a.nilai_diskon, b.kuantitas_diskon_item, c.item_id, ((c.harga * c.kuantitas) - c.diskon) / c.kuantitas harga_per_item_setelah_diskon_campaign,
			case 
				when isnull(b.kuantitas_diskon_item, 0) = 0 and isnull(d.kuantitas, 0) = 0 then
					case
						when a.nilai_diskon <= 100 then ". $nominal_order ." * a.nilai_diskon / 100 
						else a.nilai_diskon
					end				
				else 
					case 
						when isnull(b.kuantitas_diskon_item, 0) <> 0 then
							case 
								when a.nilai_diskon <= 100 then ((((c.harga * c.kuantitas) - c.diskon) / c.kuantitas) * a.nilai_diskon / 100 ) * b.kuantitas_diskon_item
								else a.nilai_diskon * b.kuantitas_diskon_item			
							end
						when isnull(d.kuantitas, 0) <> 0 then
							d.harga * d.kuantitas
					end
			end total_nilai_rupiah_diskon_tambahan,
			case 
				when isnull(b.kuantitas_diskon_item, 0) = 0 and isnull(d.kuantitas, 0) = 0 then
					case
						when a.nilai_diskon <= 100 then a.nilai_diskon
						else 100 * a.nilai_diskon / ". $nominal_order . "
					end
				else
					case
						when isnull(b.kuantitas_diskon_item, 0) <> 0 then
							case 
								when a.nilai_diskon <= 100 then a.nilai_diskon
								else /*a.nilai_diskon / ((c.harga * c.kuantitas) - c.diskon) / c.kuantitas*/ ( 100 * a.nilai_diskon * b.kuantitas_diskon_item ) /  ((c.harga * c.kuantitas) - c.diskon)
							end
						when isnull(d.kuantitas, 0) <> 0 then
							a.nilai_diskon
					end
			end total_nilai_persen_diskon_tambahan
			from diskon x inner join order_diskon a 	on x.diskon_id = a.diskon_id
			left outer join order_diskon_item b 		on a.order_id = b.order_id and a.user_id = b.user_id and b.diskon_id = x.diskon_id
			left outer join order_item c 					on b.order_id = c.order_id and b.user_id = c.user_id and b.item_seq = c.item_seq
			left outer join order_diskon_freeitem d	on a.order_id = d.order_id and a.user_id = d.user_id and d.diskon_id = x.diskon_id
			where 	a.nilai_diskon > 0 and x.gift_diskon = 2 and a.order_id = '". main::formatting_query_string( $order_id ) ."' ";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " and " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql  . " order by case when isnull(x.urutan, 0) = 0 then 10 else x.urutan end desc " );	}
		catch(Exception $e){$e->getMessage();}

	}
	
	static function daftar_order_diskon_item( $arr_parameter = array() ){
		$sql = "select b.*, c.item_seq, c.item_id, case when isnull(e.model, '') = '' then d.[desc] else e.model end item_nama, 
			c.harga, b.kuantitas_diskon_item kuantitas, c.diskon, (c.harga * c.kuantitas) - c.diskon item_subtotal, 
			case 
				when a.nilai_diskon <= 100 
					/* then ( (c.harga * c.kuantitas) - c.diskon ) * a.nilai_diskon / 100 */
					then ( c.harga - round( c.diskon / c.kuantitas, 0 ) ) * b.kuantitas_diskon_item * a.nilai_diskon / 100
				else
					/* a.nilai_diskon * c.kuantitas */
					a.nilai_diskon * b.kuantitas_diskon_item
			end nilai_diskon,
			case 
				when a.nilai_diskon <= 100 
					/* then ( (c.harga * c.kuantitas) - c.diskon ) * ( 100 - a.nilai_diskon ) / 100 */
					then ( c.harga - round( c.diskon / c.kuantitas, 0 ) ) * b.kuantitas_diskon_item * ( 100 - a.nilai_diskon ) / 100
				else 
					/* ( (c.harga * c.kuantitas) - c.diskon ) - ( a.nilai_diskon * c.kuantitas ) */
					( (c.harga * c.kuantitas) - c.diskon ) - ( a.nilai_diskon * b.kuantitas_diskon_item )
			end item_subtotal_diskon, c.gudang, f.gudang gudang_asal
			from order_diskon a inner join order_diskon_item b on a.user_id = b.user_id and a.order_id = b.order_id and a.diskon_id = b.diskon_id
			inner join order_item c on b.user_id = c.user_id and b.order_id = c.order_id and b.item_seq = c.item_seq
			inner join ". $GLOBALS["database_accpac"] ."..icitem d on c.item_id = d.itemno
			inner join [order]  f on c.order_id = f.order_id and c.user_id = f.user_id
			left outer join mesdb.dbo.tbl_icitem e on d.itemno = e.itemno
			 ";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql );	}
		catch(Exception $e){$e->getMessage();}		
	}
	
	static function daftar_order_diskon_itemfree( $arr_parameter = array() ){
		$sql = "select b.*, case when isnull(d.model, '') = '' then c.[desc] else d.model end item_nama, e.gudang gudang_asal, b.harga nilai_diskon from 
			order_diskon a inner join order_diskon_freeitem b on a.user_id = b.user_id and a.order_id = b.order_id and a.diskon_id = b.diskon_id
			inner join ". $GLOBALS["database_accpac"] ."..icitem c on b.item_id =c.itemno 
			inner join [order] e on b.order_id = e.order_id and b.user_id = e.user_id
			left outer join mesdb.dbo.tbl_icitem d on c.itemno =d.itemno 
			";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql );	}
		catch(Exception $e){$e->getMessage();}
	}
	
	static function hitung_diskon( $basis_perhitungan = 0, $nilai_diskon = 0 ){
		$return_diskon = $nilai_diskon <= 100 ? $basis_perhitungan * $nilai_diskon / 100  : $nilai_diskon;
		return $return_diskon;
		//return floor($return_diskon / __KONSTANTA_PEMBULATAN__) * __KONSTANTA_PEMBULATAN__;
	}		
	
	// ########################################## TRANSAKSI SQL ###########################################################

	static function insert_order_diskon( $arr_col ){
		$sql = "insert into order_diskon (". implode(",", array_keys( $arr_col )) .") values(". implode(",", array_values( $arr_col )) .");";
		return sql::execute($sql);
	}
	
	static function update_order_diskon( $arr_set, $arr_parameter ){
		$sql = "update order_diskon set ". self::sql_parameter( $arr_set, "," ) ." where ". self::sql_parameter( $arr_parameter ) .";";
		return sql::execute($sql);
	}
	
	static function hapus_order_diskon( $arr_parameter ){
		$sql = "delete order_diskon where ". self::sql_parameter( $arr_parameter ) .";";
		return sql::execute($sql);
	}
	
	static function insert_order_diskon_item( $arr_col ){
		$sql = "insert into order_diskon_item (". implode(",", array_keys( $arr_col )) .") values(". implode(",", array_values( $arr_col )) .");";
		return sql::execute($sql);		
	}

	static function hapus_order_diskon_item( $arr_parameter ){
		$sql = "delete order_diskon_item where ". self::sql_parameter( $arr_parameter ) .";";
		return sql::execute($sql);
	}
	
	static function insert_order_diskon_itemfree( $arr_col ){
		$sql = "insert into order_diskon_freeitem (". implode(",", array_keys( $arr_col )) .") values(". implode(",", array_values( $arr_col )) .");";
		return sql::execute($sql);		
	}
	
	static function hapus_order_diskon_itemfree( $arr_parameter ){
		$sql = "delete order_diskon_freeitem where ". self::sql_parameter( $arr_parameter ) .";";
		return sql::execute($sql);
	}
	
	static function hapus_order_diskon_approval( $arr_parameter ){
		$sql = "delete order_diskon_approval where ". self::sql_parameter( $arr_parameter ) .";";
		return sql::execute($sql);
	}
	
	static function insert_order_diskon_approval( $arr_col ){
		$sql = "insert into order_diskon_approval (". implode(",", array_keys( $arr_col )) .") values(". implode(",", array_values( $arr_col )) .");";
		return sql::execute($sql);		
	}
	
	static function update_order_diskon_approval( $arr_set, $arr_parameter ){
		$sql = "update order_diskon_approval set ". self::sql_parameter( $arr_set, "," ) ." where ". self::sql_parameter( $arr_parameter ) .";";
		return sql::execute($sql);
	}


}

?>