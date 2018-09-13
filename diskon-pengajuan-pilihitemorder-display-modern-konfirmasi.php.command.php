<?

$register_otomatis_untuk_diskon_id = 26;

if( @$_REQUEST["sc"] == "hapus_item_diskon" ){
	
	$jumlah_item = 0;
	for( $x = 1; $x < ($x+2); $x++ ){
		
		if(  $jumlah_item >= $_REQUEST["jumlah_item_campaign_noncampaign"] ) break;
		
		if( $_REQUEST["b_cb_" . $x] != "" ){
			@$sql_delete_item .= "
				delete from order_diskon_item where order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."' and item_seq = '". main::formatting_query_string( $_REQUEST["b_cb_" . $x] ) ."' and diskon_id = $register_otomatis_untuk_diskon_id;
				delete from order_item where order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."' and item_seq = '". main::formatting_query_string( $_REQUEST["b_cb_" . $x] ) ."';";
			$jumlah_item++;
		}
		
	}
		
	// cek apabila diskon_id = $register_otomatis_untuk_diskon_id sudah tidak ada anggotanya, maka langsung data di dbo.order_diskon dihapus
	$sql_delete_diskon = "delete from order_diskon where order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."' and diskon_id = $register_otomatis_untuk_diskon_id  and not exists(select 1 from order_diskon_item where order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."' and diskon_id = $register_otomatis_untuk_diskon_id)";
	
	// cek hapus data di dbo.order, apabila semua item ordernya dihapus
	$rs_jumlah_item_order = sql_dm::browse_cart( array("a.order_id" => array("=", "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'") ) ) ;

	if( $jumlah_item >= sqlsrv_num_rows( $rs_jumlah_item_order ) ){
		
		// kirim email informasi penghapusan order
		tambahan_diskon_persetujuan::kirim_email_tanggapan( $data_dealer["idcust"], $_REQUEST["order_id"], "", "<h3>Order ini dibatalkan/dihapus secara otomatis karena semua item ordernya tidak dilanjutkan untuk pengajuan diskon display!</h3>" );
	
		sql::execute( $sql_delete_item );
		sql::execute( $sql_delete_diskon );
	
	// hapus dbo.order
		$sql = "delete from [order] where order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."';";
		
		sql::execute( $sql );
		
		header("location:bm-diskon-pengajuan.php");
		exit;
	}

	sql::execute( $sql_delete_item );
	sql::execute( $sql_delete_diskon );
	
	header("location:diskon-pengajuan.php?dealer_id=". $data_dealer["idcust"] ."&order_id=". $_REQUEST["order_id"]);
	exit;	

}elseif( @$_REQUEST["sc"] == "register_item_diskon" ){
		
	// cek data diskon
	$rs_diskon = tambahan_diskon::daftar_tambahan_diskon( array("b.diskon_id" => array("=", "'". main::formatting_query_string( $_REQUEST["diskonid"] ) ."'") ), $_REQUEST["order_id"], true );
	$data_diskon = sqlsrv_fetch_array( $rs_diskon );

	$sql = "insert into order_diskon
				select order_id, user_id, $register_otomatis_untuk_diskon_id, ". $data_diskon["default_nilai"] .", (select singkatan from diskon where diskon_id=$register_otomatis_untuk_diskon_id), '', 0  from [order] where order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."'
				and not exists(select 1 from order_diskon where order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."' and diskon_id = $register_otomatis_untuk_diskon_id);";

	$jumlah_item = 0; echo $jumlah_item . " -- " . $_REQUEST["jumlah_item_campaign_noncampaign"];
	for( $x = 1; $x <= $_REQUEST["jumlah_item_campaign_noncampaign"]; $x++ ){
		
		if( $_REQUEST["b_cb_" . $x] != "" ){
			
			$sql .= "
				delete from order_diskon_item where order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."' and item_seq = '". main::formatting_query_string( $_REQUEST["b_cb_" . $x] ) ."' and diskon_id = $register_otomatis_untuk_diskon_id;
				insert into order_diskon_item
				select order_id, user_id, $register_otomatis_untuk_diskon_id, '". main::formatting_query_string( $_REQUEST["b_cb_" . $x] ) ."', '". main::formatting_query_string( $_REQUEST["q_" . $x] ) ."', NULL from [order] where order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."';";
				
				
		}

	}

	sql::execute( $sql );
	
	// cek apabila diskon_id = 7 (diskon display tanpa pengajuan) sudah tidak ada anggotanya, maka langsung data di dbo.order_diskon dihapus
	$sql = "delete from order_diskon where order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."' and diskon_id = '". main::formatting_query_string( $_REQUEST["diskonid"] ) ."'  and not exists(select 1 from order_diskon_item where order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."' and diskon_id = '". main::formatting_query_string( $_REQUEST["diskonid"] ) ."')";
	sql::execute( $sql );

	header("location:diskon-pengajuan.php?dealer_id=". $data_dealer["idcust"] ."&order_id=". $_REQUEST["order_id"]);
	exit;	
	
}

?>