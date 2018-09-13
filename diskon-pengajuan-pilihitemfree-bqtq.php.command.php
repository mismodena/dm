<?

if( @$_REQUEST["c"] == "" ) goto Skip_Semua;

if( $_REQUEST["c"] == "pilih_itemfree" ){		
	
	// cek saldo dulu
	$total_alokasi = $item_free_nominal;
	foreach( $arr_tambahan_diskon_share as $diskon_id ){		
		$diskon_id_budget = $saldo_tersedia[ $diskon_id ];
		$total_alokasi -=  $diskon_id_budget;
		if( $total_alokasi <= 0 ) break; // kebutuhan dana budget sudah tercukupi
	}
	
	if( $total_alokasi > 0 ){ // saldo tetap tidak mencukupi
		$_REQUEST["c"] = "saldo_insufficient";
		die("<script>location.href='". $page ."?". http_build_query($_REQUEST) ."'</script>");
	}
		
	// reset item bqtq apabila ada, khusus untuk diskon_id yg saling share
	unset( $arr_parameter );
	$arr_parameter["order_id"] = array("=", "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'");
	$arr_parameter["user_id"] = array("=", "'". main::formatting_query_string( $data_dealer["user_id"] ) ."'");
	$arr_parameter["diskon_id"] = array(" in ", "(". implode(",", $arr_tambahan_diskon_share ) .")");
	$arr_parameter["item_bqtq"] = array("=", "'". main::formatting_query_string( $_REQUEST["item_id"] ) ."'");
	$arr_parameter["mode_bqtq"] = array("=", 1);		
	prosedur_khusus_tambahan_diskon::hapus_order_diskon_bqtq( $arr_parameter );
	// reset order_diskon_freeitem apabila ada, khusus untuk diskon_id yg saling share
	$arr_parameter = array();
	$arr_parameter["user_id"] = array("=", "'". main::formatting_query_string( $data_dealer["user_id"] ) ."'");
	$arr_parameter["order_id"] = array("=", "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'");
	$arr_parameter["diskon_id"] = array(" in ", "(". implode(",", $arr_tambahan_diskon_share ) .")");
	$arr_parameter["item_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["item_id"] ) ."'");	
	tambahan_diskon::hapus_order_diskon_itemfree( $arr_parameter );
	
	// re-entri data diskon, dengan alokasi nilai diskon bqtq masing-masing budget
	$total_alokasi = $item_free_nominal;

	foreach( $arr_tambahan_diskon_share as $diskon_id ){		
		$diskon_id_budget = $saldo_tersedia[ $diskon_id ];

		unset( $arr_parameter );
		$arr_parameter["b.order_id"] = array("=", "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'");
		$arr_parameter["a.diskon_id"] = array("=", "'". main::formatting_query_string( $diskon_id ) ."'");
		$rs_tambahan_diskon = tambahan_diskon::daftar_tambahan_diskon( $arr_parameter , $data_dealer["order_id"] );
		if( sqlsrv_num_rows($rs_tambahan_diskon) <= 0 ){
			$arr_col = array();
			$arr_col["user_id"] = "'" . main::formatting_query_string( $data_dealer["user_id"] ) . "'";
			$arr_col["order_id"] = "'" . main::formatting_query_string( $data_dealer["order_id"] ) . "'";
			$arr_col["diskon_id"] = "'" . main::formatting_query_string( $diskon_id ) . "'";
			tambahan_diskon::insert_order_diskon( $arr_col );
		}		
		
		$arr_col = array();
		$arr_col["user_id"] = "'" . main::formatting_query_string( $data_dealer["user_id"] ) . "'";
		$arr_col["order_id"] = "'" . main::formatting_query_string( $data_dealer["order_id"] ) . "'";
		$arr_col["diskon_id"] = "'" . main::formatting_query_string( $diskon_id ) . "'";
		$arr_col["item_bqtq"] = "'" . main::formatting_query_string( $_REQUEST["item_id"] ) . "'";
		$arr_col["kuantitas_bqtq"] = "'" . main::formatting_query_string( $_REQUEST["qty"] ) . "'";
		$arr_col["mode_bqtq"] = 1;
		$arr_col["diskon_bqtq"] = $total_alokasi > $diskon_id_budget ? $diskon_id_budget : $total_alokasi ;
		prosedur_khusus_tambahan_diskon::insert_order_diskon_bqtq( $arr_col );
		
		$arr_parameter = array();
		$arr_parameter["user_id"] = "'". main::formatting_query_string( $data_dealer["user_id"] ) ."'";
		$arr_parameter["order_id"] = "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'";
		$arr_parameter["diskon_id"] = "'". main::formatting_query_string( $diskon_id ) ."'";
		$arr_parameter["item_id"] = "'". main::formatting_query_string( $_REQUEST["item_id"] ) ."'";
		$arr_parameter["harga"] = "'". main::formatting_query_string( $_REQUEST["harga"] ) ."'";
		$arr_parameter["kuantitas"] = "'". main::formatting_query_string( $_REQUEST["qty"] ) ."'";
		$arr_parameter["gudang"] = "'". main::formatting_query_string( $_REQUEST["gudang"] ) ."'";	
		tambahan_diskon::insert_order_diskon_itemfree( $arr_parameter );
		
		$total_alokasi -=  $diskon_id_budget;
		if( $total_alokasi <= 0 ) break; // kebutuhan dana budget sudah tercukupi
		
	}
	
	if( $total_alokasi > 0 ){ // saldo tetap tidak mencukupi
		// reset item bqtq lagi, khusus untuk diskon_id yg saling share
		unset( $arr_parameter );
		$arr_parameter["order_id"] = array("=", "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'");
		$arr_parameter["user_id"] = array("=", "'". main::formatting_query_string( $data_dealer["user_id"] ) ."'");
		$arr_parameter["diskon_id"] = array(" in ", "(". implode(",", $arr_tambahan_diskon_share ) .")");
		$arr_parameter["item_bqtq"] = array("=", "'". main::formatting_query_string( $_REQUEST["item_id"] ) ."'");
		$arr_parameter["mode_bqtq"] = array("=", 1);		
		prosedur_khusus_tambahan_diskon::hapus_order_diskon_bqtq( $arr_parameter );

		$_REQUEST["c"] = "saldo_insufficient";	
		die("<script>location.href='". $page ."?". http_build_query($_REQUEST) ."'</script>");
	}
	
	$_REQUEST["c"] = "pilih_itemfree";
	die("<script>location.href='diskon-pengajuan.php?". http_build_query($_REQUEST) ."'</script>");
			
}elseif( $_REQUEST["c"] == "saldo_insufficient" ){
	
	$readonly = "";
	$display_konfirmasi_share = "munculkan";		
	
	// eksekusi share budget (dk = diskon kombinasi)
	if( @$_REQUEST["dk"] != "" ){
		
		$_REQUEST["c"] = "pilih_itemfree";
		die("<script>location.href='". $page ."?". http_build_query($_REQUEST) ."'</script>");
						
	}
	
}

Skip_Semua:
?>