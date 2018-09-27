<?

class order extends sql_dm{

	private static function durasi_pengajuan_persetujujuan ( $kolom_pembanding ){
		return "datediff(day, ". $kolom_pembanding .", GETDATE()) <= 0";
	}
	
	static function sql_item_info_khusus_untuk_cek_stok( $diskon = 0){
		if( defined("__MODE_SIMULASI__") ) 
			return "select ltrim(rtrim(b.itemno)) itemno, 
				100000 qty_pst,
				100000 qty_lokal,
				case when isnull(c.model, '') = '' then b.[desc] else c.model end [desc] , b.itembrkid, b.fmtitemno, c.[model], b.comment1, b.comment2,
				convert(bigint, e.unitprice) * (100 - ". $diskon .") / 100 unitprice, e.unitprice pricelist
				from 
				". $GLOBALS["database_accpac"] ."..ICITEM b left join mesdb..tbl_icitem c 
					on b.ITEMNO = c.ITEMNO
				left join ". $GLOBALS["database_accpac"] ."..ICPRIC d on b.ITEMNO = d.ITEMNO and d.pricelist='STD'
				left join ". $GLOBALS["database_accpac"] ."..ICPRICP e on d.ITEMNO = e.ITEMNO and d.PRICELIST = e.PRICELIST and d.CURRENCY = e.CURRENCY  and e.DPRICETYPE = 1 and e.CURRENCY = 'IDR'
				where 
					c.MODEL is not null and b.ITEMBRKID in ('FG', 'MDS','HD','ACS') and 
					b.INACTIVE = 0 and b.[DESC] not like '%SAMPLE%' 
					 /*ITEMNO*/";
		
		return "			
			select 
				 l.qtyonhand+l.qtyadnocst+l.qtyrenocst-l.qtyshnocst-l.qtycommit qty_accpac_pusat,
				 m.qtyonhand+m.qtyadnocst+m.qtyrenocst-m.qtyshnocst-m.qtycommit qty_accpac_cabang,
				ltrim(rtrim(b.itemno)) itemno, isnull(f.kuantitas_pusat_terambil,0) as kuantitas_pusat_terambil, isnull(g.kuantitas_cabang_terambil,0) as kuantitas_cabang_terambil,h.kuantitas,
				(select case when convert(int, qtyonhand+qtyadnocst+qtyrenocst-qtyshnocst-qtycommit-isnull(f.kuantitas_pusat_terambil, 0) ) - ( case when h.gudang = 'GDGPST' then isnull( h.kuantitas, 0) else 0 end )  <=0 then 
							convert(int, qtyonhand+qtyadnocst+qtyrenocst-qtyshnocst-qtycommit-isnull(f.kuantitas_pusat_terambil, 0) ) - ( case when h.gudang = 'GDGPST' then isnull( h.kuantitas, 0) else 0 end )
					else 
					convert(int, qtyonhand+qtyadnocst+qtyrenocst-qtyshnocst-qtycommit-isnull(f.kuantitas_pusat_terambil, 0) ) - ( case when h.gudang = 'GDGPST' then isnull( h.kuantitas, 0) else 0 end ) 
					end from sgtdat..iciloc where itemno=b.itemno and location='GDGPST') qty_pst,
				(select case when convert(int, qtyonhand+qtyadnocst+qtyrenocst-qtyshnocst-qtycommit-isnull(g.kuantitas_cabang_terambil, 0) ) - ( case when i.gudang = k.gudang then isnull( i.kuantitas, 0) else 0 end )  <=0 then 
							convert(int, qtyonhand+qtyadnocst+qtyrenocst-qtyshnocst-qtycommit-isnull(g.kuantitas_cabang_terambil, 0) ) - ( case when i.gudang = k.gudang then isnull( i.kuantitas, 0) else 0 end )
					else 
					convert(int, qtyonhand+qtyadnocst+qtyrenocst-qtyshnocst-qtycommit-isnull(g.kuantitas_cabang_terambil, 0) ) - ( case when i.gudang = k.gudang then isnull( i.kuantitas, 0) else 0 end ) 
					end from sgtdat..iciloc where itemno=b.itemno and location=k.gudang) qty_lokal,
				case when isnull(c.model, '') = '' then b.[desc] else c.model + case when isnull(c.ITEMDESC, '') = '' then '' else '-'+ isnull(c.ITEMDESC, '') end end [desc], b.itembrkid, b.fmtitemno, c.[model], b.comment1, b.comment2,
				convert(bigint, e.unitprice) * (100 - ". $diskon .") / 100 unitprice, e.unitprice pricelist, case when isnull(arrivaldate,'') = '' then convert(varchar, exparrival, 105) + ' (Estimasi)' else convert(varchar, arrivaldate, 105) end estimasi_kedatangan, reqqty estimasi_kuantitas_kedatangan
				/*KOLOM_TAMBAHAN*/
				from 
				". $GLOBALS["database_accpac"] ."..ICITEM b inner join mesdb..tbl_icitem c 
					on b.ITEMNO = c.ITEMNO
				inner join ". $GLOBALS["database_accpac"] ."..ICPRIC d on b.ITEMNO = d.ITEMNO 		
				/*JOIN_TAMBAHAN*/				
				left join ". $GLOBALS["database_accpac"] ."..ICPRICP e on d.ITEMNO = e.ITEMNO and d.PRICELIST = e.PRICELIST and d.CURRENCY = e.CURRENCY 
				left outer join (
					select SUM(kuantitas) kuantitas_pusat_terambil, item_id, a.gudang from (
						select order_id, item_id, kuantitas, gudang from order_item  union all
						( select order_id, item_id, kuantitas, gudang from order_diskon_freeitem x, diskon y where x.diskon_id = y.diskon_id  )  
					) a, [order] b where a.order_id = b.order_id and a.gudang = 'GDGPST' and b.pengajuan_diskon = 1 and b.kirim <> 1 and 
						/* rilis stok stlh ganti hari datediff(day, b.tanggal, GETDATE()) <=1*/ /* diganti menjadi 24 jam datediff(hour, b.tanggal, GETDATE()) <=24 */ ". self::durasi_pengajuan_persetujujuan( "b.tanggal" ) . 
					( @$_SESSION["order_id"] != "" ? " and a.order_id <>  '". main::formatting_query_string( $_SESSION["order_id"] ) ."'" : "" ) ." 
					group by item_id, a.gudang
				) f on b.itemno = f.item_id
				outer apply (
					select /*x.order_id,*/ sum(kuantitas) kuantitas_cabang_terambil, x.item_id, x.gudang from (
						select order_id, item_id, kuantitas, gudang from order_item  union all
						( select order_id, item_id, kuantitas, gudang from order_diskon_freeitem x, diskon y where x.diskon_id = y.diskon_id  )  
					) x, [order] y where x.order_id = y.order_id and x.gudang = k.gudang and y.pengajuan_diskon = 1 and y.kirim <> 1 and 
						/* rilis stok stlh ganti hari datediff(day, b.tanggal, GETDATE()) <=1*/ /* diganti menjadi 24 jam datediff(hour, y.tanggal, GETDATE()) <=24 */ ".  self::durasi_pengajuan_persetujujuan( "y.tanggal" ) . 
					( @$_SESSION["order_id"] != "" ? " and x.order_id <>  '". main::formatting_query_string( $_SESSION["order_id"] ) ."'" : "" ) ." 
					and x.item_id = b.itemno
					group by /*x.order_id,*/ x.item_id, x.gudang
				) g
				". ( @$_SESSION["order_id"] != "" ? " 
				left outer join (
					select /*a.order_id,*/ sum(kuantitas) kuantitas, a.item_id, a.gudang from (
						select order_id, item_id, kuantitas, gudang from order_item  union all
						( select order_id, item_id, kuantitas, gudang from order_diskon_freeitem x, diskon y where x.diskon_id = y.diskon_id  )  
					) a, [order] b where a.order_id = b.order_id and a.gudang = 'GDGPST' and a.order_id = '". main::formatting_query_string( $_SESSION["order_id"] ) ."'
					group by /*a.order_id,*/ a.item_id, a.gudang
				) h on b.itemno = h.item_id 
				outer apply (
					select /*x.order_id, */sum(kuantitas) kuantitas, x.item_id, x.gudang from (
						select order_id, item_id, kuantitas, gudang from order_item  union all
						( select order_id, item_id, kuantitas, gudang from order_diskon_freeitem x, diskon y where x.diskon_id = y.diskon_id  )  
					) x, [order] y where x.order_id = y.order_id and x.gudang = k.gudang and x.order_id = '". main::formatting_query_string( $_SESSION["order_id"] ) ."'
					and x.item_id = b.itemno
					group by /*x.order_id,*/ x.item_id, x.gudang
				) i
				" :"" ) ."
				left outer join ( select * from psibj.[dbo].[ufn_psi_itemarrival]() ) j on b.fmtitemno = j.fmtitemno
				outer apply (
					select * from sgtdat..iciloc where itemno=b.itemno and location='GDGPST'
				) l
				outer apply (
					select * from sgtdat..iciloc where itemno=b.itemno and location='/*LOCATION*/'
				) m
				where 
					c.MODEL is not null and b.ITEMBRKID in ('FG', 'MDS','HD','ACS') and 
					b.INACTIVE = 0 and b.[DESC] not like '%SAMPLE%' and 
					DPRICETYPE = 1 and e.CURRENCY = 'IDR' and d.pricelist='STD' /*ITEMNO*/";
	}

	static function sql_item_info( $diskon = 0){
		if( defined("__MODE_SIMULASI__") ) 
			return "select ltrim(rtrim(b.itemno)) itemno, 
				100000 qty_pst,
				100000 qty_lokal,
				case when isnull(c.model, '') = '' then b.[desc] else c.model end [desc] , b.itembrkid, b.fmtitemno, c.[model], b.comment1, b.comment2,
				convert(bigint, e.unitprice) * (100 - ". $diskon .") / 100 unitprice, e.unitprice pricelist
				from 
				". $GLOBALS["database_accpac"] ."..ICITEM b left join mesdb..tbl_icitem c 
					on b.ITEMNO = c.ITEMNO
				left join ". $GLOBALS["database_accpac"] ."..ICPRIC d on b.ITEMNO = d.ITEMNO and d.pricelist='STD'
				left join ". $GLOBALS["database_accpac"] ."..ICPRICP e on d.ITEMNO = e.ITEMNO and d.PRICELIST = e.PRICELIST and d.CURRENCY = e.CURRENCY  and e.DPRICETYPE = 1 and e.CURRENCY = 'IDR'
				where 
					c.MODEL is not null and b.ITEMBRKID in ('FG', 'MDS','HD','ACS') and 
					b.INACTIVE = 0 /*and b.[DESC] not like '%SAMPLE%' */
					 /*ITEMNO*/";
		
		return "			
			select ltrim(rtrim(b.itemno)) itemno, f.kuantitas_pusat_terambil, g.kuantitas_cabang_terambil,
				(select case when convert(int, qtyonhand+qtyadnocst+qtyrenocst-qtyshnocst-qtycommit-isnull(f.kuantitas_pusat_terambil, 0) ) ". 
					( @$_SESSION["order_id"] != "" ? "- ( case when h.gudang = 'GDGPST' then isnull( h.kuantitas, 0) else 0 end ) " : "" ) . 
					" <=0 then 0 
					else 
					convert(int, qtyonhand+qtyadnocst+qtyrenocst-qtyshnocst-qtycommit-isnull(f.kuantitas_pusat_terambil, 0) ) ". 
					( @$_SESSION["order_id"] != "" ? "- ( case when h.gudang = 'GDGPST' then isnull( h.kuantitas, 0) else 0 end ) " : "" ) . 
					"
					end from ". $GLOBALS["database_accpac"] ."..iciloc where itemno=b.itemno and location='GDGPST') qty_pst,
				(select case when convert(int, qtyonhand+qtyadnocst+qtyrenocst-qtyshnocst-qtycommit-isnull(g.kuantitas_cabang_terambil, 0) ) ". 
					( @$_SESSION["order_id"] != "" ? "- ( case when i.gudang = '/*LOCATION*/' then isnull( i.kuantitas, 0) else 0 end ) " : "" ) . 
					" <=0 then 0 
					else 
					convert(int, qtyonhand+qtyadnocst+qtyrenocst-qtyshnocst-qtycommit-isnull(g.kuantitas_cabang_terambil, 0) ) ". 
					( @$_SESSION["order_id"] != "" ? "- ( case when i.gudang = '/*LOCATION*/' then isnull( i.kuantitas, 0) else 0 end ) " : "" ) . 
					"
					end from ". $GLOBALS["database_accpac"] ."..iciloc where itemno=b.itemno and location='/*LOCATION*/') qty_lokal,
				case when isnull(c.model, '') = '' then b.[desc] else c.model + case when isnull(c.ITEMDESC, '') = '' then '' else '-'+ isnull(c.ITEMDESC, '') end end [desc], b.itembrkid, b.fmtitemno, c.[model], b.comment1, b.comment2,
				convert(bigint, e.unitprice) * (100 - ". $diskon .") / 100 unitprice, e.unitprice pricelist, case when isnull(arrivaldate,'') = '' then convert(varchar, exparrival, 105) + ' (Estimasi)' else convert(varchar, arrivaldate, 105) end estimasi_kedatangan, reqqty estimasi_kuantitas_kedatangan
				from 
				". $GLOBALS["database_accpac"] ."..ICITEM b inner join mesdb..tbl_icitem c 
					on b.ITEMNO = c.ITEMNO
				inner join ". $GLOBALS["database_accpac"] ."..ICPRIC d on b.ITEMNO = d.ITEMNO 				
				left join ". $GLOBALS["database_accpac"] ."..ICPRICP e on d.ITEMNO = e.ITEMNO and d.PRICELIST = e.PRICELIST and d.CURRENCY = e.CURRENCY 
				left outer join (
					select SUM(kuantitas) kuantitas_pusat_terambil, item_id, a.gudang from (
						select order_id, item_id, kuantitas, gudang from order_item  union all
						( select order_id, item_id, kuantitas, gudang from order_diskon_freeitem x, diskon y where x.diskon_id = y.diskon_id  )  
					) a, [order] b where a.order_id = b.order_id and a.gudang = 'GDGPST' and b.pengajuan_diskon = 1 and b.kirim <> 1 and datediff(day, b.tanggal, GETDATE()) <=1 ". 
					( @$_SESSION["order_id"] != "" ? " and a.order_id <>  '". main::formatting_query_string( $_SESSION["order_id"] ) ."'" : "" ) ." 
					group by item_id, a.gudang
				) f on b.itemno = f.item_id
				left outer join (
					select SUM(kuantitas) kuantitas_cabang_terambil, item_id, a.gudang from (
						select order_id, item_id, kuantitas, gudang from order_item  union all
						( select order_id, item_id, kuantitas, gudang from order_diskon_freeitem x, diskon y where x.diskon_id = y.diskon_id  )  
					) a, [order] b where a.order_id = b.order_id and a.gudang = '/*LOCATION*/' and b.pengajuan_diskon = 1 and b.kirim <> 1 and datediff(day, b.tanggal, GETDATE()) <=1 ". 
					( @$_SESSION["order_id"] != "" ? " and a.order_id <>  '". main::formatting_query_string( $_SESSION["order_id"] ) ."'" : "" ) ." 
					group by item_id, a.gudang
				) g on b.itemno = g.item_id
				". ( @$_SESSION["order_id"] != "" ? " 
				left outer join (
					select a.order_id, sum(kuantitas) kuantitas, a.item_id, a.gudang from (
						select order_id, item_id, kuantitas, gudang from order_item  union all
						( select order_id, item_id, kuantitas, gudang from order_diskon_freeitem x, diskon y where x.diskon_id = y.diskon_id  )  
					) a, [order] b where a.order_id = b.order_id and a.gudang = 'GDGPST' and a.order_id = '". main::formatting_query_string( $_SESSION["order_id"] ) ."'
					group by a.order_id, a.item_id, a.gudang
				) h on b.itemno = h.item_id 
				left outer join (
					select a.order_id, sum(kuantitas) kuantitas, a.item_id, a.gudang from (
						select order_id, item_id, kuantitas, gudang from order_item  union all
						( select order_id, item_id, kuantitas, gudang from order_diskon_freeitem x, diskon y where x.diskon_id = y.diskon_id  )  
					) a, [order] b where a.order_id = b.order_id and a.gudang = '/*LOCATION*/' and a.order_id = '". main::formatting_query_string( $_SESSION["order_id"] ) ."'
					group by a.order_id, a.item_id, a.gudang
				) i on b.itemno = i.item_id 
				" :"" ) ."
				left outer join ( select * from psibj.[dbo].[ufn_psi_itemarrival]() ) j on b.fmtitemno = j.fmtitemno
				where 
					c.MODEL is not null and b.ITEMBRKID in ('FG', 'MDS','HD','ACS') and 
					b.INACTIVE = 0 and /*b.[DESC] not like '%SAMPLE%' and */
					DPRICETYPE = 1 and e.CURRENCY = 'IDR' and d.pricelist='STD' /*ITEMNO*/";
	}
	
	static function daftar_item_semua_gudang( $item, $diskon = 0, $arr_gudang_user = array() ){				
		if( count($arr_gudang_user)  > 0 ){
			foreach( $arr_gudang_user as $gudang )	$arr_gudang_kutip[] = "'". main::formatting_query_string( $gudang ) ."'";
			$str_parameter_gudang = implode(",", $arr_gudang_kutip);
		}else
			$str_parameter_gudang = " 'GDGSBY','GDGMLG','GDGDPS','GDGBDG','GDGSMR','GDGYGY','GDGMKS','GDGMND','GDGSMG','GDGLPG','GDGBJM','GDGPLB','GDGPKB','GDGPWK','GDGKDR','GDGPTK','GDJNMD','GDGTBB','DMC03L','GDGSOS'";
		
		$sql = "select d.model, ltrim(rtrim(a.itemno)) itemno, 
						isnull( 
									convert(int, qtyonhand+qtyadnocst+qtyrenocst-qtyshnocst-qtycommit 
										- isnull( c.kuantitas_terambil, 0 ) 
										- ISNULL( y.kuantitas_terambil_order_ini,0 ) 
										) 
						, 0 ) kuantitas, b.location gudang, convert(bigint, g.unitprice) * (100 - ". $diskon .") / 100 unitprice
						from  
						". $GLOBALS["database_accpac"] ."..icitem a 
						inner join  ". $GLOBALS["database_accpac"] ."..ICPRIC f on a.ITEMNO = f.ITEMNO 
						left join  ". $GLOBALS["database_accpac"] ."..ICPRICP g on g.ITEMNO = f.ITEMNO and g.PRICELIST = f.PRICELIST and g.CURRENCY = f.CURRENCY 
						LEFT outer join  ". $GLOBALS["database_accpac"] ."..iciloc b on a.itemno = b.itemno 
						left outer join ( select location from mesdb..tbl_icloc where STATE = 1 and INACTIVE = 0 union select 'GDGPST' ) e on b.location = e.location 
						left outer join mesdb..tbl_icitem d on A.ITEMNO = d.ITEMNO 
						LEFT outer join ( 
							select SUM(kuantitas) kuantitas_terambil, item_id, x.gudang from 
								( select order_id, item_id, kuantitas, gudang from order_item union all ( select order_id, item_id, kuantitas, gudang from order_diskon_freeitem x, diskon y where x.diskon_id = y.diskon_id ) ) x, [order] y 
								where x.order_id = y.order_id and y.pengajuan_diskon = 1 and y.kirim <> 1 and 
								datediff(day, y.tanggal, GETDATE()) <=1 and y.order_id <> '". main::formatting_query_string( $_SESSION["order_id"] ) ."' group by item_id, x.gudang ) c on b.ITEMNO = c.item_id and b.LOCATION = c.gudang 
						left outer join (
												select sum(kuantitas) kuantitas_terambil_order_ini, a.item_id, a.gudang from dbo.ufn_daftar_order_item( '". main::formatting_query_string( $_SESSION["order_id"] ) ."') a, [order] b where a.order_id = b.order_id 
												group by a.item_id, a.gudang
								) y on b.ITEMNO = y.item_id and b.LOCATION = y.gudang 		
						/*inner join (select distinct cabang from [user]) z on b.LOCATION = z.cabang */
						where DPRICETYPE = 1 and g.CURRENCY = 'IDR' and g.pricelist='STD' and a.ITEMNO = '". main::formatting_query_string( $item ) ."' and 
						b.LOCATION in ($str_parameter_gudang)
						order by case e.location when '". main::formatting_query_string( $_SESSION["cabang"] ) ."' then 1 when 'GDGPST' then 2 else 3 end asc";

		return sql::execute($sql);  
	}
	
	static function daftar_item_gudang_spesifik_utk_user(){
		$sql = "select * from gudang_user where user_id = '". main::formatting_query_string( $_SESSION["sales_id"] ) ."' and aktif='1';";
		$rs = sql::execute($sql);
		$arr_return = array();
		while( $data = sqlsrv_fetch_array( $rs ) )
			$arr_return[] = $data["gudang"];
		return $arr_return;
	}
	
	static function daftar_dealer_spesifik_utk_user(){
		$sql = "
		select ltrim(rtrim(idcust)) idcust, ltrim(rtrim(namecust)) namecust, ltrim(rtrim(textstre1))+' '+ltrim(rtrim(textstre2))+' '+ltrim(rtrim(textstre3))+' '+ltrim(rtrim(textstre4)) addr, namecity, custtype, priclist, 
		 ".$GLOBALS["database_accpac"].".dbo.ufnDiskonDealer(a.IDCUST) disc 
		 from 
		".$GLOBALS["database_accpac"]."..ARCUS a, dealer_user  b
		 where 
		 a.idcust = b.dealer and
		 b.user_id = '". main::formatting_query_string( $_SESSION["sales_id"] ) ."' and b.aktif='1';";
		return sql::execute($sql);
	}

	static function orderid( $dealer_id, $diskon, $is_draft = true ){
		Start_Get_Orderid:
		// format : M-MMYY-COUNTER
		// dari database mobile sales
		/*$sql="select 
			isnull((select max(cast(right(order_id, 5) as int))+1 from ".$GLOBALS["database_mobilesales"]."..[order] where ( kirim = '1' or pengajuan_diskon = '1' ) and year(tanggal)=year(getdate()) and isnumeric(right(order_id, 5)) = 1 ), 1) [id],
			case when month(current_timestamp)<10 then '0'+cast(month(current_timestamp) as varchar(2)) else cast(month(current_timestamp) as varchar(2)) end mon, 
			substring(convert(varchar,year(current_timestamp), 101), 3,2) yr";
		$sqlo=sql::execute($sql);
		$id=sqlsrv_fetch_array($sqlo);

		$orderid = "IM/MS-".
				$id["mon"].$id["yr"]."-".
				str_repeat('0', 5-strlen($id["id"])).$id["id"];
		*/
		if( $is_draft ){ 
			
			$orderid = self::orderid_temporer();
			
			$sql = "select * from [order] where order_id = '". main::formatting_query_string( $orderid ) ."' and dealer_id = '". main::formatting_query_string( $dealer_id ) ."' ;";
			
			$rs_cek_order = sql::execute( $sql );
			if( sqlsrv_num_rows( $rs_cek_order ) > 0 ) return $orderid;
			
			$sql="
				insert into ".$GLOBALS["database_mobilesales"]."..[order] (user_id, order_id, dealer_id, gudang, diskon) 
				values('".main::formatting_query_string($_SESSION["sales_id"])."', 
					'".main::formatting_query_string($orderid)."',
					'".main::formatting_query_string($dealer_id)."',
					'".main::formatting_query_string($_SESSION["cabang"] )."',
					'".main::formatting_query_string($diskon)."');";	
			try{
				sql::execute($sql);
			}catch(Exception $e){
				goto Start_Get_Orderid;
			}
			
		}else{
			
			$sql = "select order_id from [order] where dealer_id = '". main::formatting_query_string( $dealer_id ) ."' and user_id = '". main::formatting_query_string( $_SESSION["sales_id"] ) ."' 
					and kirim = 0 and pengajuan_diskon = 0 and ltrim(order_id) like 'DRAFT%'";
			$rs_order_id_input = sql::execute($sql);
			$order_id_input = sqlsrv_fetch_array( $rs_order_id_input ) or die("<script>alert('Gagal mendapatkan draft order!');history.back()</script>");
			
			$sql = "select dbo.ufn_order_id('". $order_id_input["order_id"] ."') order_id";
			$rs_order_id = sql::execute($sql);
			$order_id = sqlsrv_fetch_array( $rs_order_id ) or die("<script>alert('Gagal mendapatkan nomor order!');history.back()</script>");
			$orderid = $order_id["order_id"];
			
		}
		
		return 	$orderid;
	}
	
	private static function orderid_temporer(){
		return "DRAFT-" . trim($_SESSION["sales_id"]) . "-" . trim($_SESSION["kode_dealer"]);
	}
	
	static function daftar_item( $diskon = 0, $keluarkan_stok_nol = ""  ){						
	
		if( $_SESSION["order_id"] == "" ) $_SESSION["order_id"] = "temporary";
		$sql = self::sql_item_info_khusus_untuk_cek_stok( $diskon );
		//echo $sql;
		$sql_keluarkan_stok_nol = "";
		if( $keluarkan_stok_nol == 1 )
			$sql_keluarkan_stok_nol = " and (select case when convert(int, qtyonhand+qtyadnocst+qtyrenocst-qtyshnocst-qtycommit-isnull(g.kuantitas_cabang_terambil, 0) ) ". 
					( $_SESSION["order_id"] != "" ? "- ( case when i.gudang = '/*LOCATION*/' then isnull( i.kuantitas, 0) else 0 end ) " : "" ) . 
					" <=0 then 0 
					else 
					convert(int, qtyonhand+qtyadnocst+qtyrenocst-qtyshnocst-qtycommit-isnull(g.kuantitas_cabang_terambil, 0) ) ". 
					( $_SESSION["order_id"] != "" ? "- ( case when i.gudang = '/*LOCATION*/' then isnull( i.kuantitas, 0) else 0 end ) " : "" ) . 
					"
					end from ". $GLOBALS["database_accpac"] ."..iciloc where itemno=b.itemno and location='/*LOCATION*/') > 0";		
		
		$ap=array(
			"/*ITEMNO*/"	=>	(@$_REQUEST["item"]		!=""?	
				" and (b.itemno like '". main::formatting_query_string( trim(strtoupper(@$_REQUEST["item"])) )."%' or 
						upper( replace( replace(b.[desc], 'MODENA', ''), 'DOMO', '' ) ) like '%".main::formatting_query_string( trim(strtoupper(@$_REQUEST["item"])) )."%')"	:""), 
			"/*LOCATION*/"	=>	(@$_SESSION["cabang"]	!=""?	main::formatting_query_string( trim(strtoupper(@$_SESSION["cabang"])) )					:""),
			"k.gudang"	=>	(@$_SESSION["cabang"]	!=""? "'".	main::formatting_query_string( trim(strtoupper(@$_SESSION["cabang"])) )	. "'"				:"")
		);
		
		$sql=str_replace(array_keys($ap), array_values($ap), $sql . $sql_keluarkan_stok_nol );

		return sql::execute( $sql );
	}
	
	static function cek_cek_stok_item_order( $order_id = "" ){		
		$order_id = $order_id == "" ? $_SESSION["order_id"] : $order_id;		
		
		// rapikan commit
		$sql = "exec dbo.usp_benerin_commit_pak_mus '". main::formatting_query_string( $order_id ) ."';";
		//sql::execute( $sql );
		
		$sql = "select ltrim(rtrim(a.item_id)) item_id, 
					isnull( 
						convert(int, qtyonhand+qtyadnocst+qtyrenocst-qtyshnocst-qtycommit-
								( case when aa.gudang <> a.gudang then isnull(f.kuantitas_pusat_terambil, 0) else isnull(g.kuantitas_cabang_terambil, 0) end )
								- isnull( a.kuantitas , 0)
								) 
						, 0 
						) kuantitas, a.gudang
					from 
					[order] aa left outer join
					(
						select a.order_id, sum(kuantitas) kuantitas, a.item_id, a.gudang from dbo.ufn_daftar_order_item( '". main::formatting_query_string( $order_id ) ."') a, [order] b where a.order_id = b.order_id /*and b.pengajuan_diskon = 1 and b.kirim <> 1 and datediff(day, b.tanggal, GETDATE()) <=1 */
						group by a.order_id, a.item_id, a.gudang
					) a on aa.order_id = a.order_id 
					left outer join ". $GLOBALS["database_accpac"] ."..iciloc b 
						on a.item_id=b.itemno and a.gudang = b.location
					left outer join (
						select SUM(kuantitas) kuantitas_pusat_terambil, item_id, x.gudang from dbo.ufn_daftar_order_item( '". main::formatting_query_string( $order_id ) ."') x, [order] y where x.order_id = y.order_id /*and x.gudang = 'GDGPST'*/ and y.pengajuan_diskon = 1 and y.kirim <> 1 and datediff(day, y.tanggal, GETDATE()) <=1 and y.order_id <>  '". main::formatting_query_string( $order_id ) ."'
						group by item_id, x.gudang
					) f on b.itemno = f.item_id and f.gudang <> a.gudang
					left outer join (
						select SUM(kuantitas) kuantitas_cabang_terambil, item_id, x.gudang from dbo.ufn_daftar_order_item( '". main::formatting_query_string( $order_id ) ."') x, [order] y where x.order_id = y.order_id and y.pengajuan_diskon = 1 and y.kirim <> 1 and datediff(day, y.tanggal, GETDATE()) <=1 and y.order_id <>  '". main::formatting_query_string( $order_id ) ."'
						group by item_id, x.gudang
					) g on b.itemno = g.item_id and /*b.location = g.gudang*/ aa.gudang = g.gudang
					where 
					a.order_id = '". main::formatting_query_string( $order_id ) ."' "; 
					
		if( @$_SESSION["order_id"] != "" ) $order_id_temporary = $_SESSION["order_id"];
		$_SESSION["order_id"] = $order_id;
		$sql = "select case when gudang = 'GDGPST' then qty_pst else qty_lokal end kuantitas, itemno item_id, gudang from ( " . 
							str_replace
								( 
								array("/*KOLOM_TAMBAHAN*/", "/*JOIN_TAMBAHAN*/") , 
								array(",k.gudang", " left outer join order_item k on b.ITEMNO = k.item_id "), 
								self::sql_item_info_khusus_untuk_cek_stok( 0 ) . " and k.order_id = '". main::formatting_query_string( $order_id ) ."' "
								) . ") x" ;
		if( isset( $order_id_temporary ) )	$_SESSION["order_id"] = $order_id_temporary;
		return sql::execute( $sql );
	}
	static function draft_stock( $item = "", $gudang = ""){
		$sql = "select a.order_id,a.user_id,a.tanggal,a.kirim,a.pengajuan_diskon,b.item_id,b.kuantitas,b.gudang ";
		$sql .="from dm..[order] a inner join dm..order_item b ON a.order_id=b.order_id ";
		$sql .="left join SGTDAT..oeordh c on c.ORDNUMBER=a.order_id ";
		$sql .="where ";
		$sql .="(b.item_id='" . $item . "' and b.gudang='". $gudang ."' and kirim=0 /*and c.ORDNUMBER is null*/ and pengajuan_diskon=0 and ( " . self::durasi_pengajuan_persetujujuan( "a.tanggal" ) . " ) ) ";
		$sql .="OR (b.item_id='" . $item . "' and b.gudang='". $gudang ."' and kirim = 0 and pengajuan_diskon=1 and ( " . self::durasi_pengajuan_persetujujuan( "a.tanggal" ) . " ) /*and c.ORDNUMBER is null*/ ) order by a.tanggal desc";	

		return sql::execute( $sql );
	}
	static function tambah_akumulasi_log( $order_id = "", $dealer_id = "" ){		
				
		if($_SESSION["pilih_cn"]==0 ) {

			sql::execute("UPDATE order_item SET credit_note=0 where order_id='". $order_id ."'");
			unset($_SESSION['pilih_cn']);
			
		}else{

			$sql_paket = " select top 1 order_item.paketid from order_item 					
						inner join paket_parameter on paket_parameter.paketid=order_item.paketid
						where order_id='". $order_id ."' and parameterid=28 and order_item.credit_note>0";	
			$rs_paket = sql::execute( $sql_paket );

			if( sqlsrv_num_rows( $rs_paket ) > 0 ){

				$data_paket = sqlsrv_fetch_array($rs_paket);

				$data_campaign = sqlsrv_fetch_array( sql::execute(" select a.awal, a.akhir, c.periodeid, c.campaignid from periode a, campaign b, paket c where a.periodeid = b.periodeid and b.periodeid = c.periodeid and b.campaignid = c.campaignid and c.paketid = '". main::formatting_query_string( $data_paket["paketid"] ) ."'") );

				$sql = "insert into order_akumulasi_log (order_id, user_id,parent_order) 
						select a1.order_id,a1.user_id,'". $order_id ."' from (					
							select SUM(e.QTYCOMMIT)-SUM(ISNULL( g.qtyreturn, 0 )) kuantitas, SUM(e.INVDISC) diskon, sum(e.EXTINVMISC - e.INVDISC - e.HDRDISC  - isnull(g.EXTCRDMISC, 0) - isnull(g.CRDDISC, 0) - isnull(g.HDRDISC, 0)) subtotal_order, c.order_id, c.user_id
							from  [order] c inner join order_item a on
								c.order_id = a.order_id and c.user_id = a.user_id 
								inner join paket_item b on 
									(
										(a.item_id = b.item and b.mode = 1) or  
										b.item in (select x.item from paket_item x, sub_kategori y where x.paketid = b.paketid and x.item = convert(varchar, y.sub_kategoriid) and x.mode = 2 and substring( a.item_id, 1, 2 ) in ( select * from dbo.[ufn_split_string](y.kode_prefiks, ',') ) )
									)
								inner join ". $GLOBALS["database_accpac"] ."..ICITEM f on
									a.item_id = f.itemno  						
								inner join sgtdat..OEINVH d on c.order_id = d.ordnumber
								inner join sgtdat..OEINVD e on d.invuniq = e.invuniq and f.fmtitemno = e.item 	
								left outer join ". $GLOBALS["database_accpac"] ."..OECRDD g on
									g.INVNUMBER = d.INVNUMBER and g.LINENUM = e.LINENUM
								where 
									c.dealer_id = '" . main::formatting_query_string( $dealer_id ) . "' and 
									CONVERT(DATE,c.tanggal) >= '". $data_campaign["awal"]->format("m/d/Y") ."' and CONVERT(DATE,c.tanggal) <= '". $data_campaign["akhir"]->format("m/d/Y") ."' and 
									b.paketid = '". main::formatting_query_string( $data_paket["paketid"] ) ."' 		
							group by c.order_id, c.user_id
							union 
							select sum(a.kuantitas) kuantitas, SUM(a.diskon) diskon, sum(a.harga * a.kuantitas) subtotal_order, c.order_id, c.user_id
							from  [order] c, order_item a, paket_item b where 
								c.order_id = a.order_id and c.user_id = a.user_id and 
								c.dealer_id = '" . main::formatting_query_string( $dealer_id ) . "' and 
								a.order_id = '". main::formatting_query_string( $order_id ) ."' and c.kirim = 0 
								and MONTH(c.tanggal)=MONTH(getdate()) and 
								b.paketid = '". main::formatting_query_string( $data_paket["paketid"] ) ."' and 
								(
									(a.item_id = b.item and b.mode = 1) or  
									b.item in (select x.item from paket_item x, sub_kategori y where x.paketid = b.paketid and x.item = convert(varchar, y.sub_kategoriid) and x.mode = 2 and substring( a.item_id, 1, 2 ) in ( select * from dbo.[ufn_split_string](y.kode_prefiks, ',') ) )
								) 					
							group by c.order_id, c.user_id
							) 
						a1, [order] a2 
						left outer join order_akumulasi_log oal on oal.order_id=a2.order_id
						where a1.order_id = a2.order_id and a1.user_id = a2.user_id
						and oal.order_id is null
						order by a2.tanggal desc";

				$rs_data_invoice = sql::execute( $sql );

				$sql_calc = " select sum((kuantitas*harga) -diskon_default) as total_order, SUM(credit_note) as CN from order_item where order_id='". $order_id ."' ";	

				$data_calc = sqlsrv_fetch_array(sql::execute( $sql_calc ));
				if(($data_calc["total_order"]  - $data_calc["CN"]) > 0 ){

					//UPDATE FLAG CN yang potong Inv
					sql::execute("UPDATE order_akumulasi_log SET cut_inv_pkp=1 where parent_order='". $order_id ."'");
					//update diskon,diskon_total + CN 
					$parameter["order_id"] = array("=", "'". $order_id ."'");
					$rs_order_item = self::browse_order_item( $parameter );

					while( $order_item = sqlsrv_fetch_array($rs_order_item) ){
						$arr_set["diskon"] = array("=", "'". $order_item["diskon"] + $order_item["credit_note"] .""); 
						$arr_set["diskon_total"] = array("=", "'". $order_item["diskon_total"] + $order_item["credit_note"] ."");
						$arr_parameter_update["order_id"] = array("=", "'". $order_id ."'") ;
						$arr_parameter_update["item_seq"] = array("=", "'". main::formatting_query_string( $order_item["item_seq"] ) ."'") ;

						self::update_order_item( $arr_set, $arr_parameter_update );

					}
				}
			}
		}
		return true;
	}

	static function daftar_order( $arr_parameter = array() ){
		$sql = "select a.*, b.namecust, c.invnet nilai_order, c.ordnumber from [order] a inner join ". $GLOBALS["database_accpac"] ."..arcus b on a.dealer_id = b.idcust 
			left outer join ". $GLOBALS["database_accpac"] ."..oeordh c on a.order_id = c.ordnumber ";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql  . " order by a.tanggal desc " );	}
		catch(Exception $e){$e->getMessage();}
	}
	
	static function daftar_order_split( $arr_parameter = array() ){
		$sql = "select a.*, b.namecust, c.invnet nilai_order, c.ordnumber from [order_split] a inner join ". $GLOBALS["database_accpac"] ."..arcus b on a.dealer_id = b.idcust 
			left outer join ". $GLOBALS["database_accpac"] ."..oeordh c on dbo.sambung_order_id(a.order_id, a.order_id_split, '-') = c.ordnumber ";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql  . " order by a.tanggal desc " );	}
		catch(Exception $e){$e->getMessage();}
	}	
	
	static function daftar_order_original_split( $arr_parameter = array() ){
		$sql = "select a.*, dbo.sambung_order_id(a.order_id, a.order_id_split, '-') order_id_split  from [order_split] a ";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql  . " order by a.tanggal desc " );	}
		catch(Exception $e){$e->getMessage();}
	}
	
	static function daftar_order_histori( $arr_parameter = array() ){
		$sql = "select a.*, '['+ltrim(rtrim(textsnam))+'] '+ b.namecust namecust, isnull(e.invnet, c.invnet) nilai_order, dbo.sambung_order_id(d.order_id, d.order_id_split, '-') order_id_split, 
			case isnull(d.gudang, '') when '' then a.gudang else d.gudang end gudang
			from [order] a inner join ". $GLOBALS["database_accpac"] ."..arcus b on a.dealer_id = b.idcust 
			left outer join order_split d on a.order_id = d.order_id
			left outer join ". $GLOBALS["database_accpac"] ."..oeordh c on a.order_id = c.ordnumber 
			left outer join ". $GLOBALS["database_accpac"] ."..oeordh e on dbo.sambung_order_id(d.order_id, d.order_id_split, '-') = e.ordnumber ";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );
		//echo $sql; exit;
		try{		return sql::execute( $sql  . " order by a.tanggal desc " );	}
		catch(Exception $e){$e->getMessage();}
	}
	
	static function check_overlimit( $dealer_id, $order=0 ){
		$sql="select * from ". $GLOBALS["database_accpac"] .".dbo.ufnCekOverLimit(" . main::formatting_query_string( $order ) . ", '". main::formatting_query_string( $dealer_id )."');"; 			
		return @sqlsrv_fetch_array ( sql::execute( $sql ) );
	}
	
	static function nominal_order( $dealer_id = "", $arr_parameter = array(), $is_split = false ){	
		
		$split_tabel = "";
		$split_onparameter = "";
		if( $is_split ){
			$split_tabel = "_split";
			$split_onparameter = " and a.gudang = b.gudang ";
		}
		
		$tabel = " (					
					select a.harga, a.kuantitas, a.diskon, a.tambahan_diskon, a.user_id, a.order_id, a.gudang 
					from order_item a  union all
				select b.harga, b.kuantitas, b.harga diskon, 0 tambahan_diskon, b.user_id, b.order_id, b.gudang 
					from order_diskon_freeitem b
			)";
		if( array_key_exists("b.order_id", $arr_parameter) )
			$tabel = " dbo.ufn_daftar_order_item(". $arr_parameter["b.order_id"][1] .") ";
		
		$sql="select  a.order_id, round(sum( (a.harga * a.kuantitas ) - isnull( a.diskon, 0 ) ),0) nominal_order /* order dkurangi diskon campaign */, 
			round(sum(a.diskon), 0) diskon_campaign, round(sum( (a.harga * a.kuantitas ) ), 0) nominal_order_gross /* order tanpa diskon */,
			round(sum( (a.harga * a.kuantitas ) - ( isnull( a.diskon, 0) + isnull( a.tambahan_diskon, 0 ) ) ), 0) nominal_order_net_exc_discfaktur /* net order setelah diskon campaign, tambahan kecuali diskon faktur */,
			round(sum(a.sub_total) - (case when b.diskon <= 100 then (b.diskon * sum(a.sub_total) / 100) else b.diskon end), 0) /*sum( (a.harga * a.kuantitas ) - ( isnull( a.diskon, 0) + isnull( a.tambahan_diskon, 0 ) ) ) - isnull( b.diskon_nominal, 0 )*/ nominal_order_net /* net order setelah diskon campaign, tambahan + diskon faktur */,
			round(sum( case when a.diskon>0 then (a.harga * a.kuantitas) - a.diskon else 0 end ), 0) total_order_campaign,
			round(sum( case when a.diskon<=0 then (a.harga * a.kuantitas) else 0 end ), 0) total_order_non_campaign,
			b.diskon diskon_faktur
			from ". $tabel ." a, [order". $split_tabel ."] b where a.order_id = b.order_id and a.user_id = b.user_id " . $split_onparameter;
		
		if( $dealer_id != "" )
			$sql .= " and b.dealer_id = '". main::formatting_query_string( $dealer_id ) ."' ";
			
		if ( count($arr_parameter) > 0 )
			$sql .= " and " . sql::sql_parameter( $arr_parameter );

		return @sqlsrv_fetch_array ( sql::execute( $sql . " group by a.order_id, /*b.diskon_nominal,*/ b.diskon " ) );
	}
	
	public static function cek_stok_kosong($data_dealer, $arr_stok_item /* format kode_accpac_item => stok_item */, $mode = "1" /* 1=pembuat; 2=pemberi persetujuan*/){
		
		// cek ulang nilai stok tersedia vs unit pembelian
		$arr_stok_kosong = array();
		foreach( $arr_stok_item as $item=>$stok_item )
			if( $stok_item < 0 ) $arr_stok_kosong[] = $item;		
			
		if( count( $arr_stok_kosong ) > 0 ){
			// reset status order.kirim apabila ada stok item order yg kosong
			unset( $arr_set, $arr_parameter );
			$arr_set["kirim"] = array("=", "'0'");
			$arr_parameter["order_id"] = array("=", "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'");
			$arr_parameter["user_id"] = array("=", "'". main::formatting_query_string( $data_dealer["user_id"] ) ."'");
			self::update_order( $arr_set, $arr_parameter );
		
			$arr_replace["#kode_dealer#"] = $data_dealer["idcust"];
			$arr_replace["#nama_dealer#"] = $data_dealer["namecust"];
			
			$tombol_aksi = "<input type=\"button\" name=\"b_periksa_order\" id=\"b_periksa_order\" value=\"Periksa Ulang Order\" onclick=\"location.href='transaksi-2.php?dealer=". $data_dealer["idcust"] ."'\" class=\"tombol-hijau\"/>";
			if( $mode == 2 )
				$tombol_aksi = "";
			
			$string_peringatan_stok_kosong = str_replace( array_keys( $arr_replace ), array_values( $arr_replace ), file_get_contents( "template/header-form-order.html" ) ) . 
					"
					<link rel=\"stylesheet\" href=\"css/main.css\" />
					<div>
						<h4><span class=\"tanda-seru\">!</span>Stok Kosong</h4>
						Item berikut mengalami perubahan level stok
						<ul>#item_stok_kosong#</ul>
						". $tombol_aksi ."
					</div>"
					;
			foreach($arr_stok_kosong as $item)	@$string_item_stok_kosong .= "<li><strong>[". $item ."] ". $GLOBALS["arr_nama_item"][ $item ] ."</strong></li>";	
			
			return str_replace( "#item_stok_kosong#", $string_item_stok_kosong, $string_peringatan_stok_kosong );
		}
		
		return "";
	}
	
	public static function daftar_order_item($order_id, $diskon_tambahan_disetujui = false){								
		$sql = "select * from dbo.ufn_daftar_order_item('". main::formatting_query_string( $order_id ) ."')";
		//echo $sql; exit();
		try{		return sql::execute( $sql );	}
		catch(Exception $e){$e->getMessage();}
		
	}
	
	public static function daftar_order_item_split($order_id, $diskon_tambahan_disetujui = false){
		$sql = "select a.*, case when ISNULL(d.gudang, '') = '' then e.gudang else d.gudang end gudang, c.gudang gudang_asal 
				from dbo.ufn_daftar_order_item_split('". main::formatting_query_string( $order_id ) ."') a 
				inner join [order_split] b on a.order_id = dbo.sambung_order_id(b.order_id, b.order_id_split, '-') 
				inner join [order] c on b.order_id = c.order_id 
				left outer join order_item d on c.order_id = d.order_id and c.user_id = d.user_id and a.item_seq = d.item_seq
				left outer join order_diskon_freeitem e on c.order_id = e.order_id and c.user_id = e.user_id and a.item_id = e.item_id and 
				convert(varchar, e.diskon_id)  in (select * from dbo.[ufn_split_string](',', convert(varchar, a.diskon_id_all)))  and a.item_seq = 0 ";
				
		try{		return sql::execute( $sql );	}
		catch(Exception $e){$e->getMessage();}
		
	}
	
	static function kirim_data_ke_accpac( $data_dealer, $nominal_order, $status_order ){
		// pastikan kirim = 1
		$sql = "update [order] set kirim = 1 where order_id = '". main::formatting_query_string( $data_dealer["order_id"] ) ."'";
		sql::execute( $sql );
		
		// cek apakah berasal dari satu gudang atau lebih
		$sql = "select * from dbo.[ufn_order_split]('". main::formatting_query_string( $data_dealer["order_id"] ) ."')";
		$rs = sql::execute( $sql );
		if( sqlsrv_num_rows( $rs ) <= 0 ){
			// kirimkan data ke accpac, tanpa splitting karena berasal dari satu gudang
			$sql = "exec dbo.DM_uspApvOrderH_Post_" . $GLOBALS["database_accpac"] . "
						'".main::formatting_query_string( $data_dealer["order_id"] )."',
						'ADMIN',
						". $status_order . ", 
						".$nominal_order["nominal_order_net"].", 
						".$nominal_order["nominal_order_net_exc_discfaktur"].", 
						".$nominal_order["nominal_order_gross"].";";
			
			sql::execute( $sql );
			return false;
		}
		
		while( $data_split = sqlsrv_fetch_array( $rs ) ){
			
			$nominal_order = self::nominal_order( $data_dealer["idcust"], array( 
				"b.order_id" => array("=",  "'". main::formatting_query_string( $data_split["order_id"] ) ."'" ), 
				"b.kirim" => array("=",  "'1'" ), 
				"a.gudang" => array("=", "'". main::formatting_query_string( $data_split["gudang"] ) ."'") ) 
				, true );
				
			$sql = "exec dbo.DM_uspApvOrderH_Post_split_" . $GLOBALS["database_accpac"] . "
						'".main::formatting_query_string( $data_split["order_id"] . "-" . $data_split["order_id_split"] )."',
						'ADMIN',
						". $status_order . ", 
						".$nominal_order["nominal_order_net"].", 
						".$nominal_order["nominal_order_net_exc_discfaktur"].", 
						".$nominal_order["nominal_order_gross"].";";

			sql::execute( $sql );
			
		}
		return true;
	}
	
	public static function sql_order_item_simpel( $order_id, $item_seq = "" ){
			if( @$_REQUEST["item"] != "" ){
				$arr_t_item = explode(",", $_REQUEST["item"]);
				foreach( $arr_t_item as $item )
					$arr_par_item[] = " case when isnull(g.model, '') = '' then c.[desc] else g.model end like '%". trim( main::formatting_query_string( $item ) ) ."%'";
				$string_par_item = is_array( $arr_par_item ) ? implode(" or ", $arr_par_item) : "";
			}
			
			$sql = "select b.*, rtrim(ltrim(b.item_id)) item_id, case when isnull(g.model, '') = '' then c.[desc] else g.model end item_nama, d.keterangan_paket,
						'' diskon_id, '' order_diskon_item, a.gudang gudang_asal, b.gudang, 0 kuantitas_diskon_item
						from [order] a 
						inner join order_item b on a.order_id = b.order_id and a.user_id = b.user_id
						inner join ". $GLOBALS["database_accpac"] ."..icitem c on b.item_id = c.itemno 
						left outer join paket d on b.paketid = d.paketid
						left outer join mesdb.dbo.tbl_icitem g on c.itemno = g.itemno where a.order_id = '". main::formatting_query_string( $order_id ) ."'
						" . ( $item_seq != "" ? " and b.item_seq = '". main::formatting_query_string( $item_seq ) ."' " : "" )
						. ( $string_par_item != "" ? " and ( $string_par_item )" : "" ) 
						;
			return $sql;
	}
	
	public static function sql_order_diskon_item_simpel( $order_id, $diskon_id = "", $operator_diskon_id = "=", $item_seq = "" ){
		$sql = "select a.*, b.nilai_diskon, c.diskon, c.keterangan_diskon, c.singkatan, d.item_seq_asal from order_diskon_item a, order_diskon b, diskon c, order_item d 
						where a.order_id = b.order_id and a.user_id = b.user_id and a.diskon_id = b.diskon_id
						and b.diskon_id = c.diskon_id and c.aktif_diskon = 1 
						and a.order_id = d.order_id and a.user_id = d.user_id and a.item_seq = d.item_seq  
						and a.order_id = '". main::formatting_query_string( $order_id ) . "'"
						. ( $diskon_id != "" ? " and a.diskon_id $operator_diskon_id '". main::formatting_query_string( $diskon_id ) ."' " : "" )
						. ( $item_seq != "" ? " and a.item_seq = '". main::formatting_query_string( $item_seq ) ."' " : "" );
		return $sql;
	}
	
	public static function item_tambahan_diskon( $data_order, $kuantitas_diskon_item, $mode = 1 ){//return array();
		$sql = "select dbo.ufn_tambahin_kurangin_order_item('". main::formatting_query_string( $data_order["order_id"] ) ."', '". main::formatting_query_string( $data_order["item_seq"] ) ."', " . main::formatting_query_string( $kuantitas_diskon_item ) . ", " . main::formatting_query_string( $mode ) . ") item_seq;";				
		$rs = sql::execute( $sql );
		return sqlsrv_fetch_array( $rs );
		
	}
	
	
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
			,$arr_free_item_paket_gudang /* [paketid] = array( itemid => array(gudang =>kuantitas ) )*/
			,$item_stok /* [itemid] = array( gudang => kuantitas) */
			,$belum_ada_order
			,$arr_item_diterapkan_tambahan_diskon
			,$arr_order_item_diskon_kuantitas
			,$arr_item_diskon_kuantitas
			,$arr_item_cn
			;
			
	public $gudang_pusat = "GDGPST";
	
	function __construct($order_id, $diskon_id = "", $arr_item_id = array()){
		
		$this->belum_ada_order = true;
		
		// cek ketersediaan stok
		$arr_stok = self::cek_cek_stok_item_order( $order_id );
		while( $stok = sqlsrv_fetch_array( $arr_stok ) )
			$this->item_stok[ $stok["item_id"] ][ $stok["gudang"] ] = $stok["kuantitas"];
		
		$arr_parameter["a.order_id"] = array("=", "'". main::formatting_query_string( $order_id ) ."'");
		if( $diskon_id != "" ){
			
			//$arr_parameter["e.diskon_id"] = array("=", "'". main::formatting_query_string( $diskon_id ) ."'");
			$rs_item = self::browse_cart( $arr_parameter );
			if( sqlsrv_num_rows( $rs_item ) <= 0 ){
				
				$rs_item = sql::execute( self::sql_order_item_simpel( $order_id ) );

			}
				
		}else{
			
			if( @$_REQUEST["item"] != "" ){
				$arr_t_item = explode(",", $_REQUEST["item"]);
				foreach( $arr_t_item as $item )
					$arr_par_item[] = " case when isnull(g.model, '') = '' then c.[desc] else g.model end like '%". trim( main::formatting_query_string( $item ) ) ."%'";
				$string_par_item = is_array( $arr_par_item ) ? implode(" or ", $arr_par_item) : "";
			}
			
			if( $string_par_item != "" ) $arr_parameter["/*browse nama item*/"] = array("", "( $string_par_item )");
			
			$rs_item = self::browse_cart( $arr_parameter );
		}
		
		if( count( $arr_item_id ) > 0 ){
			
				$rs_item = sql::execute( self::sql_order_item_simpel( $order_id ) . " and b.item_id in (". implode(",", $arr_item_id) .") " );
		
		}
		
		if( $diskon_id != "" ){
			
			$rs_item_diterapkan_diskon = sql::execute( self::sql_order_diskon_item_simpel( $order_id ) );
			while( $item_diterapkan_diskon = sqlsrv_fetch_array( $rs_item_diterapkan_diskon ) )
				$this->arr_item_diterapkan_tambahan_diskon[ $item_diterapkan_diskon["diskon_id"] ][ $item_diterapkan_diskon["item_seq"] ] = $item_diterapkan_diskon;
			
			$rs_item = sql::execute( self::sql_order_item_simpel( $order_id ) );
				
			$rs_item_diskon = sql::execute( self::sql_order_diskon_item_simpel( $order_id, $diskon_id ) );
			while( $item_diskon = sqlsrv_fetch_array( $rs_item_diskon ) )
				$this->arr_item_diskon_kuantitas[ $item_diskon["item_seq"] ] = $item_diskon["kuantitas_diskon_item"];
			
		}

		while( $item = sqlsrv_fetch_array( $rs_item ) ){
			
			if( $diskon_id != "" ){
				if( in_array( $item["item_seq"], array_keys( $this->arr_item_diskon_kuantitas ) ) ) {
					$item["diskon_id"] = $diskon_id;
					$item["kuantitas_diskon_item"] = $this->arr_item_diskon_kuantitas[ $item["item_seq"] ];
				}else{
					$item["diskon_id"] = "";
					$item["kuantitas_diskon_item"] = 0;
				}
			}

			$this->gudang = $item["gudang"];
			
			$this->arr_item_nama[ $item["item_id"] ] = $item["item_nama"];
			
			if( $item["paketid"] == ""){
				
				$saran_paket = array();
		
				$rs_paket_tersedia = self::browse_paket_per_item( $item["item_id"] );		
				if( sqlsrv_num_rows($rs_paket_tersedia) > 0 ){
					while( $paket_tersedia = sqlsrv_fetch_array( $rs_paket_tersedia ) )
						$saran_paket[ $paket_tersedia["paketid"] ] = $paket_tersedia["keterangan_paket"];
				}
				$this->arr_item_non_paket[] = array( "item_seq" => $item["item_seq"], "item" => $item["item_id"], "harga" => $item["harga"], "kuantitas" => $item["kuantitas"], "saran_paket" => $saran_paket, "gudang" => $item["gudang"], "gudang_asal" => $item["gudang_asal"], "diskon_id" => $item["diskon_id"], "kuantitas_diskon_item" => $item["kuantitas_diskon_item"] );	
				
			}else{
				if( $item["harga"] <= 0 || ($item["harga"] * $item["kuantitas"]) == $item["diskon"] ){					
				
					$this->arr_free_item_paket_gudang[ $item["paketid"] ][ $item["item_id"] ][ $item["gudang"] ] = $item["kuantitas"];
					$stok_item_tanpa_freeitem = $this->item_stok[ $item["item_id"] ][ $item["gudang"] ] + $this->arr_free_item_paket_gudang[ $item["paketid"] ][ $item["item_id"] ][ $item["gudang"] ];
					
					if( $stok_item_tanpa_freeitem < 0 && $this->item_stok[ $item["item_id"] ][ $this->gudang_pusat ] > 0 ){ // stok item free di cabang tidak cukup, so ambil dari stok gudang pusat
						
						unset( $this->arr_free_item_paket_gudang[ $item["paketid"] ][ $item["item_id"] ][ $item["gudang"] ] );
						$this->arr_free_item_paket_gudang[ $item["paketid"] ][ $item["item_id"] ][ $this->gudang_pusat ] = $item["kuantitas"];
						
						unset( $arr_set, $arr_parameter );
						$arr_set["gudang"] = array("=", "'" . main::formatting_query_string( $this->gudang_pusat ) . "'");
						$arr_parameter["order_id"] = array("=", "'" . main::formatting_query_string( $order_id ) . "'");
						$arr_parameter["item_seq"] = array("=", "'" . main::formatting_query_string( $item["item_seq"] ) . "'");
						self::update_order_item( $arr_set, $arr_parameter );	
					}
					
					$this->arr_free_item_paket[ $item["paketid"] ][ $item["item_id"] ] = $item["kuantitas"];
				
				}else
					$this->arr_item_dengan_paket[] = array( "item_seq" => $item["item_seq"], "item" => $item["item_id"], "harga" => $item["harga"], "kuantitas" => $item["kuantitas"], "paketid" => $item["paketid"], "gudang" => $item["gudang"], "gudang_asal" => $item["gudang_asal"], "diskon_id" => $item["diskon_id"], "kuantitas_diskon_item" => $item["kuantitas_diskon_item"] );
			}
			
			$item["urutan_parameter"] = $item["urutan_parameter"] == "" || $item["urutan_parameter"] == 0 ? 1 : $item["urutan_parameter"];
			
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

			$this->arr_item_cn[ $item["item_seq"] ][ $item["paketid"] ] = $item["credit_note"];	

			if($_SESSION["pilih_cn"]==0 ) {
				$this->arr_item_cn[ $item["item_seq"] ][ $item["paketid"] ]=0;
			}
			$this->belum_ada_order = false;
			
		}
		
	}


}

?>