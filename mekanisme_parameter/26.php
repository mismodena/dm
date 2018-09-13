<?

class akumulasi_kuantitas_order_2_invoice extends sql_dm{
	
	public 
		$akumulasi_kuantitas_order_2_invoice = 0,
		$akumulasi_nominal_order = 0,
		$maksimal_akumulasi_invoice = 2,
		$array_daftar_produk = array()
		;
	
	function __construct( $obyek_dm, $arr_parameter ){
		
		// data dealer
		$data_dealer = sqlsrv_fetch_array( sql::execute("select dealer_id from [order] where order_id = '". main::formatting_query_string( $arr_parameter["order_id"] ) ."';") );
		
		// periode campaign
		$data_campaign = sqlsrv_fetch_array( sql::execute(" select a.awal, a.akhir, c.periodeid, c.campaignid from periode a, campaign b, paket c where a.periodeid = b.periodeid and b.periodeid = c.periodeid and b.campaignid = c.campaignid and c.paketid = '". main::formatting_query_string( $arr_parameter["paketid"] ) ."'") );	
		
		// cek data item dari order lain dalam periode campaign yg tidak diterapkan campaign
		// berlaku untuk item yg tidak mengambil campaign (order_id <> ybs), atau mengambil campaign tapi diskon = 0 (order_id <> ybs), atau mengambil campaign di order ybs
		$sql = "select a1.*, a3.harga from (
					select SUM(e.QTYSHIPPED)-SUM(ISNULL( g.qtyreturn, 0 )) kuantitas, SUM(e.INVDISC) diskon, sum(e.EXTINVMISC - e.INVDISC - e.HDRDISC  - isnull(g.EXTCRDMISC, 0) - isnull(g.CRDDISC, 0) - isnull(g.HDRDISC, 0)) subtotal_order, c.order_id, c.user_id, a.item_id
					from  [order] c inner join order_item a on
						c.order_id = a.order_id and c.user_id = a.user_id 
						inner join paket_item b on 
							(
								(a.item_id = b.item and b.mode = 1) or  
								b.item in (select x.item from paket_item x, sub_kategori y where x.paketid = b.paketid and x.item = convert(varchar, y.sub_kategoriid) and x.mode = 2 and substring( a.item_id, 1, 2 ) in ( select * from dbo.[ufn_split_string](y.kode_prefiks, ',') ) )
							)
						inner join ". $GLOBALS["database_accpac"] ."..ICITEM f on
							a.item_id = f.itemno  
						inner join ". $GLOBALS["database_accpac"] ."..OEINVH d on 
							c.order_id = d.ordnumber 
						inner join ". $GLOBALS["database_accpac"] ."..OEINVD e on
							d.invuniq = e.invuniq and f.fmtitemno = e.item 
						left outer join ". $GLOBALS["database_accpac"] ."..OECRDD g on
							g.INVNUMBER = d.INVNUMBER and g.LINENUM = e.LINENUM
						where 
							c.dealer_id = '" . main::formatting_query_string( $data_dealer["dealer_id"] ) . "' and 
							c.tanggal >= '". $data_campaign["awal"]->format("m/d/Y") ."' and c.tanggal <= '". $data_campaign["akhir"]->format("m/d/Y") ."' and 
							b.paketid = '". main::formatting_query_string( $arr_parameter["paketid"] ) ."' 		
					group by c.order_id, c.user_id, a.item_id
					union 
					select sum(a.kuantitas) kuantitas, SUM(a.diskon) diskon, sum(a.harga * a.kuantitas) subtotal_order, c.order_id, c.user_id, a.item_id
					from  [order] c, order_item a, paket_item b where 
						c.order_id = a.order_id and c.user_id = a.user_id and 
						c.dealer_id = '" . main::formatting_query_string( $data_dealer["dealer_id"] ) . "' and 
						a.order_id = '". main::formatting_query_string( $arr_parameter["order_id"] ) ."' and c.kirim = 0 and
						b.paketid = '". main::formatting_query_string( $arr_parameter["paketid"] ) ."' and 
						(
							(a.item_id = b.item and b.mode = 1) or  
							b.item in (select x.item from paket_item x, sub_kategori y where x.paketid = b.paketid and x.item = convert(varchar, y.sub_kategoriid) and x.mode = 2 and substring( a.item_id, 1, 2 ) in ( select * from dbo.[ufn_split_string](y.kode_prefiks, ',') ) )
						) 					
					group by c.order_id, c.user_id, a.item_id
					) 
				a1, [order] a2, order_item a3 where a1.order_id = a2.order_id and a1.user_id = a2.user_id and a1.order_id = a3.order_id and a1.user_id = a3.user_id and a1.item_id = a3.item_id
				order by a2.tanggal desc";

		$rs_data_invoice = sql::execute( $sql );

		$invoice_diakumulasi = 0;
		while( $data_invoice = sqlsrv_fetch_array( $rs_data_invoice ) ){

			if( $invoice_diakumulasi >= $this->maksimal_akumulasi_invoice || ( $data_invoice["diskon"] > 0 && $data_invoice["order_id"] != $arr_parameter["order_id"] ) ) break;
						
			$this->akumulasi_kuantitas_order_2_invoice += $data_invoice["kuantitas"];
			$this->akumulasi_nominal_order += $data_invoice["subtotal_order"];
			
			$this->array_daftar_produk[ $data_invoice["item_id"] ] = $data_invoice["harga"];
			
			$invoice_diakumulasi++;
			
		}		

	}
	
}

?>