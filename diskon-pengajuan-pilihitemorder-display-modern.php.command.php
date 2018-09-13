<?

if( @$_REQUEST["c"] == "" ) goto SkipCommand;

if( @$_REQUEST["c"] == "pilih_itemorder" ){
	
	$data_dealer["order_id"] = $_POST["order_id"];
	
	$parameter["a.dealer_id"] = array("=", "'". main::formatting_query_string( $_POST["dealer_id"] ) ."'");
	$parameter["a.order_id"] = array("=", "'". main::formatting_query_string( $_POST["order_id"] ) ."'");

	$rs_order = sql_dm::browse_cart( $parameter );
	
	$arr_diskon_display_tidak_diperbolehkan = array();

	while( $order = sqlsrv_fetch_array( $rs_order ) ){
		
		if( @$_POST[ "b_cb_" . $order["item_seq"] ] != "" && ( $order["order_diskon_item"] == "" || $order["order_diskon_item"] == $order["item_seq"] ) ){
		
			$cek_blm_display = true;
			
			// cek data item display di dealer ini.. 									
			// cek dari server dm, kecuali untuk order ini
			$sql = "select * from [order] x, order_item a, order_diskon_item b 
						where 
						x.order_id = a.order_id and x.user_id = a.user_id and
						a.order_id = b.order_id and a.user_id = b.user_id and a.item_seq = b.item_seq and 
						x.dealer_id = '". $_POST["dealer_id"] ."' and a.order_id <> '". main::formatting_query_string( $_POST["order_id"] ) ."' and
						a.item_id = '". main::formatting_query_string( $order["item_id"] ) ."' and b.diskon_id in (". implode(",", $arr_diskon_display_tidak_perlu_persetujuan) .");";
			$rs = sql::execute( $sql );
			if( sqlsrv_num_rows( $rs ) > 0 ) 	{
				$data = sqlsrv_fetch_array( $rs );
				$tanggal = $data["tanggal"]->format("d M Y");
				@$_POST[ "b_cb_" . $order["item_seq"] ] = ""; $arr_diskon_display_tidak_diperbolehkan[ $order["item_id"] ] =  array( $order["item_nama"], $tanggal, "ORDER : " . $data["order_id"] ); continue;
			}
			
			// cek dari invoicing
			$sql = "select d.itemno, b.orddate, b.INVNUMBER from item_diskon_display a, ". $GLOBALS["database_accpac"] ."..OEINVH b, ". $GLOBALS["database_accpac"] ."..OEINVD c, ". $GLOBALS["database_accpac"] ."..ICITEM d
					, ". $GLOBALS["database_accpac"] ."..OESHIH e, ". $GLOBALS["database_accpac"] ."..OESHID f 
					where a.dealer_id = '". main::formatting_query_string( $_POST["dealer_id"] ) ."' and d.itemno = '". main::formatting_query_string( $order["item_id"] ) ."' and
					a.invoice_no = b.INVNUMBER and b.INVUNIQ = c.INVUNIQ and c.ITEM = d.FMTITEMNO 
					and b.ORDNUMBER = e.ORDNUMBER and e.SHIUNIQ = f.SHIUNIQ and c.ITEM = f.ITEM and f.QTYSHIPPED > 0";
			$rs = sql::execute( $sql );
			if( sqlsrv_num_rows( $rs ) > 0 ) 	{
				$data = sqlsrv_fetch_array( $rs );
				$tanggal =  substr($data["orddate"], 6, 2) . " " .  $arr_month[ ( (int)substr($data["orddate"], 4, 2) ) ] . " " .  substr($data["orddate"], 0, 4);
				@$_POST[ "b_cb_" . $order["item_seq"] ] = ""; $arr_diskon_display_tidak_diperbolehkan[ $order["item_id"] ] =  array( $order["item_nama"], $tanggal, "INVOICE : " . $data["INVNUMBER"] ); continue;
			}
			
		}
		
	}

	if( count($arr_diskon_display_tidak_diperbolehkan) > 0 )	{

		include_once "diskon-pengajuan-pilihitemorder-display-modern-konfirmasi.php.function.php";
		include_once "diskon-pengajuan-pilihitemorder-display-modern-konfirmasi.php.command.php";
		include_once "diskon-pengajuan-pilihitemorder-display-modern-konfirmasi.php";
		include_once "diskon-pengajuan.php.command.php";
		echo "<script>";
		include_once "script/srv/diskon-pengajuan-pilihitemorder-display-modern-konfirmasi.php.js";
		echo "</script>";
		exit;
		
		$string = "Item berikut ini telah mendapatkan diskon display sebelumnya dan tidak diperbolehkan untuk mendapatkan diskon display lagi untuk dealer ini!\\n";
		$counter = 1;
		foreach( $arr_diskon_display_tidak_diperbolehkan as $item_nama ){
			$string .= $counter . ". " . $item_nama . "\\n";
			$counter++;
		}
		echo "<script>alert('". $string ."')</script>";
		
	}
	
	include_once "diskon-pengajuan.php.command.php";
	echo "<script>location.href='diskon-pengajuan.php?dealer_id=". $_POST["dealer_id"] ."&order_id=". $_POST["order_id"] ."'</script>";
	
}

SkipCommand:

?>