<?

function sql_cek_po_invoice( $order_no = "", $item_no = "" ){
	$sql = "select a.ORDDATE, a.ORDNUMBER, a.PONUMBER, c.idcust, c.namecust, d.ITEMNO, d.[desc], b.ORIGQTY, 
			b.UNITPRICE, b.DISCPER, (b.UNITPRICE * b.DISCPER / 100) * b.ORIGQTY diskon, 
			/*b.origqty * b.unitprice sub_total, b.origqty * ( b.unitprice - ( (b.UNITPRICE * b.DISCPER / 100) * b.ORIGQTY ) )*/ 
			b.origqty * b.unitprice sub_total, (b.origqty * b.unitprice) - ((b.UNITPRICE * b.DISCPER / 100) * b.ORIGQTY) sub_total_net_item, case when a.TERMTTLDUE <= 0 then a.ORDTOTAL else a.TERMTTLDUE end  sub_total_net
						from ". $GLOBALS["database_accpac"] ."..OEORDH a, ". $GLOBALS["database_accpac"] ."..OEORDD b,". $GLOBALS["database_accpac"] ."..ARCUS c, ". $GLOBALS["database_accpac"] ."..ICITEM d 
						where 
						a.ORDUNIQ = b.ORDUNIQ and a.CUSTOMER = c.IDCUST and b.ITEM = d.FMTITEMNO ";
						
	if( $order_no != "" ) $sql  .= " and a.ordnumber = '". main::formatting_query_string( trim( $order_no ) ) ."' ";
	else				$sql .= "and ltrim(rtrim(a.PONUMBER))='". main::formatting_query_string( trim( $_REQUEST["t_po"] ) ) ."' ";
	if( $item_no != "" ) $sql .= " and d.itemno = '". main::formatting_query_string( $item_no ) ."' ";
	return $sql;
}

function sql_cek_po_dm(  $order_no = "", $item_no = ""  ){
	$sql = "select convert(varchar(4),year(tanggal)) 
						+''+ case when len(month(tanggal))<2 then '0'+convert(varchar(2), month(tanggal)) else convert(varchar(2), MONTH(tanggal)) end
						+''+ case when len(day(tanggal))<2 then '0'+convert(varchar(2), day(tanggal)) else convert(varchar(2), day(tanggal)) end ORDDATE
						,
						a.order_id ORDNUMBER, a.po_referensi PONUMBER, c.idcust, c.namecust, d.ITEMNO, d.[desc], b.kuantitas ORIGQTY,
						b.harga UNITPRICE, b.diskon_total_persen DISCPER, (b.diskon + b.diskon_total) diskon, 
						b.kuantitas * b.harga sub_total, (b.kuantitas * b.harga) - (b.diskon + b.diskon_total) sub_total_net_item, 
						(select SUM( (harga * kuantitas) - (diskon + diskon_total) ) from order_item where user_id = a.user_id and order_id = a.order_id) sub_total_net
						from [order] a, order_item b, ". $GLOBALS["database_accpac"] ."..ARCUS c, ". $GLOBALS["database_accpac"] ."..ICITEM d 
					where 
						a.user_id = b.user_id and a.order_id = b.order_id and
						a.dealer_id = c.IDCUST and
						b.item_id = d.ITEMNO ";
		if( $order_no != "" ) $sql  .= " and a.order_id = '". main::formatting_query_string( trim( $order_no ) ) ."' ";
		else				$sql .= "and ltrim(rtrim(a.po_referensi))='". main::formatting_query_string( trim( $_REQUEST["t_po"] ) ) ."' ";
		if( $item_no != "" ) $sql .= " and d.itemno = '". main::formatting_query_string( $item_no ) ."' ";
		return $sql;
}

function cek_po( $order_no = "", $item_no = "", $tampilan_dari_cari_item = false ){

	$rs_cek_po = sql::execute( sql_cek_po_invoice( $order_no, $item_no ) );

	if( sqlsrv_num_rows( $rs_cek_po ) <= 0 ) {
		
		// cek ke data order yg sedang dalam proses pengajuan diskon tambahan
		$rs_cek_po = sql::execute( sql_cek_po_dm( $order_no, $item_no ) );
		if( sqlsrv_num_rows( $rs_cek_po ) <= 0 ) return "";		
	}
	
	$arr_data_po = $arr_no_po = array();
	while( $data_po = sqlsrv_fetch_array( $rs_cek_po ) ){
		$arr_data_po[ $data_po["ORDNUMBER"] ][] = $data_po;
		$arr_no_po[ $data_po["ORDNUMBER"] ]["tanggal_order"] = $data_po["ORDDATE"];
		$arr_no_po[ $data_po["ORDNUMBER"] ]["dealer"] = "[" . $data_po["idcust"] . "] " . $data_po["namecust"];
		$arr_no_po[ $data_po["ORDNUMBER"] ]["nomor_po"] = $data_po["PONUMBER"];
		@$arr_no_po[ $data_po["ORDNUMBER"] ]["sub_total_net"] = $data_po["sub_total_net"];
	}

	$template_header_po = file_get_contents("template/data-order-po-header.html");
	$header_po = "";
	$arr_nomor_order = array_keys( $arr_no_po );
	foreach( $arr_no_po as $nomor_order => $detail_order ){
		$tanggal_order = substr($detail_order["tanggal_order"], 6, 2) . " " . $GLOBALS["arr_month"][ (int)substr($detail_order["tanggal_order"], 4, 2) ] .  " " . substr($detail_order["tanggal_order"], 0, 4);
		$arr_rpl["#no_order#"] = $nomor_order;
		$arr_rpl["#no_po#"] = $detail_order["nomor_po"];
		$arr_rpl["#dealer#"] = $detail_order["dealer"];
		$arr_rpl["#tanggal_order#"] = $tanggal_order;
		$arr_rpl["#nilai_order#"] = main::number_format_dec( $detail_order[ "sub_total_net" ] );
		$header_po .= str_replace( array_keys( $arr_rpl ), array_values( $arr_rpl ),  $template_header_po );
	}
	
	if( $order_no != "" || $tampilan_dari_cari_item ){
		
		$return_data_po = $header_po . "<style>.tombol-lihat-detail-order-po-referensi{display:none}</style>" ;
		$template_detail_po = file_get_contents("template/data-order-po-item.html");
		
		foreach( $arr_data_po as $order_no => $detail_po ){
			
			$counter = 1;
			foreach( $detail_po as $sub_detail_po ){
				$arr_rpl["#nomor#"] = $counter;
				$arr_rpl["#nama_item#"] = $sub_detail_po["desc"];
				$arr_rpl["#kuantitas#"] = (int)$sub_detail_po["ORIGQTY"];
				$arr_rpl["#sub_total#"] = main::number_format_dec( $sub_detail_po["sub_total"] );
				$arr_rpl["#diskon#"] = main::number_format_dec( $sub_detail_po["diskon"] );
				$arr_rpl["#sub_total_net#"] = main::number_format_dec( $sub_detail_po["sub_total_net_item"] );
				
				$return_data_po .= str_replace( array_keys($arr_rpl), array_values($arr_rpl), $template_detail_po );
				
				$counter++;
			}
			
		}
		
	}else{
		
		$return_data_po = "<div style=\"font-weight:bold; font-size:14px; margin-top:17px; padding-top:17px; border-top:solid 3px black\">					
					<span class=\"tanda-seru\">!</span>
					Berikut adalah daftar order yang sudah diinput di ACCPAC menggunakan nomor PO ". $_REQUEST["t_po"] .".<br />
					Klik tombol \"Lanjutkan\" untuk melanjutkan proses order Anda, atau \"Batalkan\" untuk membatalkan order ini.</div>";				
		$return_data_po .= $header_po . "<style>.tombol-tutup-detail-order-po-referensi{display:none}</style>".
							"	<input type=\"button\" name=\"b_batalkan_order\" id=\"b_batalkan_order\" value=\"Batalkan\" style=\"width:49%; float:left; background-color:#ff6756; font-weight:900\" onclick=\"hapus_order('". $_SESSION["order_id"] ."')\" />
								<input type=\"button\" name=\"b_lanjutkan_order\" id=\"b_lanjutkan_order\" value=\"Lanjutkan\" style=\"width:49%; float:right; background-color:#5bff56; font-weight:900\" onclick=\"javascript:lanjut_proses_tanpa_ampun()\"  />";
	}
	
	return $return_data_po;

}
?>