<?

if( @$_REQUEST["c"] == "" ) goto Skip_Semua;

if( $_REQUEST["c"] == "pilih_itemorder_bqtq" ){		
	
	$data_diskon["nilai_diskon"] = str_replace(",", "", $_REQUEST["nilai_diskon"]);
	
	// update nilai diskon di tabel order_diskon
	unset( $arr_parameter );
	$arr_set["nilai_diskon"] = array("=", "'". main::formatting_query_string( str_replace(",", "", $_REQUEST["nilai_diskon"]) ) ."'");
	$arr_parameter["user_id"] = array("=", "'" . main::formatting_query_string( $data_dealer["user_id"] ) . "'");
	$arr_parameter["order_id"] = array("=", "'" . main::formatting_query_string( $_REQUEST["order_id"] ) . "'");
	$arr_parameter["diskon_id"] = array("=", "'" . main::formatting_query_string( $_REQUEST["diskonid"] ) . "'");		
	tambahan_diskon::update_order_diskon( $arr_set, $arr_parameter );			

	// reset order_diskon_bqtq
	unset( $arr_parameter );
	$arr_parameter["order_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'");
	$arr_parameter["user_id"] = array("=", "'". main::formatting_query_string( $data_dealer["user_id"] ) ."'");
	$arr_parameter["diskon_id"] = array(" in ", "(". main::formatting_query_string( $_REQUEST["diskonid"] ) .")");
	prosedur_khusus_tambahan_diskon::hapus_order_diskon_bqtq( $arr_parameter );
	
	unset( $arr_parameter );
	$arr_parameter["user_id"] = array("=", "'". main::formatting_query_string( $data_dealer["user_id"] ) ."'");
	$arr_parameter["order_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'");
	$arr_parameter["diskon_id"] = array(" in ", "(". main::formatting_query_string( $_REQUEST["diskonid"] ) .")");
	tambahan_diskon::hapus_order_diskon_item( $arr_parameter );
	
	$parameter["a.order_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'");
	//$rs_order = sql_dm::browse_cart( $parameter );
	$rs_order = sql::execute( order::sql_order_item_simpel( $_REQUEST["order_id"] ) );
	
	while( $order = sqlsrv_fetch_array( $rs_order ) ){
		
		if( @$_POST[ "b_cb_" . $order["item_seq"] ] != "" && ( $order["order_diskon_item"] == "" || $order["order_diskon_item"] == $order["item_seq"] ) ){
			
			//if( $order["order_diskon_item"] == $order["item_seq"] ) continue; // tidak bisa double diskon
			if( $order["order_diskon_item"] == $order["item_seq"] && $order["diskon_id"] == $_POST["diskonid"] ){ //hapus dulu baru entri ulang continue; // biar tidak error primary key
				$arr_parameter = array();
				$arr_parameter["user_id"] = array("=", "'". main::formatting_query_string( $data_dealer["user_id"] ) ."'");
				$arr_parameter["order_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'");
				$arr_parameter["diskon_id"] = array("=", "'". main::formatting_query_string( $_POST["diskonid"] ) ."'");
				$arr_parameter["item_seq"] = array("=", "'". main::formatting_query_string( $order["item_seq"] ) ."'");
				
				tambahan_diskon::hapus_order_diskon_item( $arr_parameter );		
				
				// cek apakah berasal dari splitting item_seq baru untuk diskon tambahan, hapus apabila item_seq tambahan dan nilainya diupdate ke item_seq_asal-nya
				if( $order["item_seq_asal"] != "" ){
					order::item_tambahan_diskon( $order, 0, 0 );
				}
				
			}

			$kuantitas_diskon_item = $_POST[ "q_" . $order["item_seq"] ] != "" ? $_POST[ "q_" . $order["item_seq"] ] : 1;
			if ( (int) $kuantitas_diskon_item > $order["kuantitas"] ) $kuantitas_diskon_item = 1;
			
			elseif( (int) $kuantitas_diskon_item < $order["kuantitas"] ){ //buat item_seq baru untuk diskon tambahan
				$item_tambahan_diskon = order::item_tambahan_diskon( $order, $kuantitas_diskon_item );

				if( @$item_tambahan_diskon["item_seq"] != "" ) 
					$order["item_seq"] = $item_tambahan_diskon["item_seq"];
			}
			
			$arr_parameter = array();
			$arr_parameter["user_id"] = "'". main::formatting_query_string( $data_dealer["user_id"] ) ."'";
			$arr_parameter["order_id"] = "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'";
			$arr_parameter["diskon_id"] = "'". main::formatting_query_string( $_POST["diskonid"] ) ."'";
			$arr_parameter["item_seq"] = "'". main::formatting_query_string( $order["item_seq"] ) ."'";
			$arr_parameter["kuantitas_diskon_item"] = "'". main::formatting_query_string( $kuantitas_diskon_item ) ."'";
			
			tambahan_diskon::insert_order_diskon_item( $arr_parameter );
			
			$arr_col = array();
			$arr_col["user_id"] = "'" . main::formatting_query_string( $data_dealer["user_id"] ) . "'";
			$arr_col["order_id"] = "'" . main::formatting_query_string( $_REQUEST["order_id"] ) . "'";
			$arr_col["diskon_id"] = "'" . main::formatting_query_string( $_POST["diskonid"] ) . "'";
			$arr_col["item_bqtq"] = "'" . main::formatting_query_string( $order["item_seq"] ) . "'";
			$arr_col["kuantitas_bqtq"] = "'" . main::formatting_query_string( $kuantitas_diskon_item ) . "'";
			$arr_col["mode_bqtq"] = 2;
			$arr_col["diskon_bqtq"] = $data_diskon["nilai_diskon"] <= 100 ? tambahan_diskon::hitung_diskon( ( $order["harga"] - ($order["diskon"] / $order["kuantitas"])) * $kuantitas_diskon_item , $data_diskon["nilai_diskon"] ) : $data_diskon["nilai_diskon"] * $kuantitas_diskon_item ;
			prosedur_khusus_tambahan_diskon::insert_order_diskon_bqtq( $arr_col );
			
		} else {
			
			$arr_parameter = array();
			$arr_parameter["user_id"] = array("=", "'". main::formatting_query_string( $data_dealer["user_id"] ) ."'");
			$arr_parameter["order_id"] = array("=", "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'");
			$arr_parameter["diskon_id"] = array("=", "'". main::formatting_query_string( $_POST["diskonid"] ) ."'");
			$arr_parameter["item_seq"] = array("=", "'". main::formatting_query_string( $order["item_seq"] ) ."'");
			
			tambahan_diskon::hapus_order_diskon_item( $arr_parameter );			
			
			// cek apakah berasal dari splitting item_seq baru untuk diskon tambahan, hapus apabila item_seq tambahan dan nilainya diupdate ke item_seq_asal-nya
			if( $order["item_seq_asal"] != "" ){
				order::item_tambahan_diskon( $order, 0, 0 );
			}
			
		}
		
		
	}
	
	// cek ulang
	$nominal_order_setelah_diskon = $nominal_order["nominal_order"];
	$rs_daftar_diskon = tambahan_diskon::daftar_tambahan_diskon( array( "b.order_id" => array( "=", "'". $data_dealer["order_id"] ."'" ) ), $data_dealer["order_id"] );
	$arr_budget_diskon_tersedia_terkait = array();
	while( $diskon = sqlsrv_fetch_array( $rs_daftar_diskon ) ){		

		$arr_content = tambahan_diskon_persetujuan::detail_order_diskon_single($counter, $diskon,  $data_dealer["idcust"], $data_dealer["order_id"], $nominal_order, $rs_daftar_diskon, "", $readonly, $item_stok , $arr_budget_diskon_tersedia_terkait, $nominal_order_setelah_diskon);
		if( $diskon["diskon_id"] == $_REQUEST["diskonid"] ){
			$saldo_budget[ $obyek_diskon->prefiks_identifikasi_bqtq() . "Avail" ] = $arr_content["saldo_tersedia_awal"];
			
		}
		$arr_budget_diskon_tersedia_terkait = $arr_content["arr_budget_diskon_tersedia_terkait"];
		$nominal_order_setelah_diskon = $arr_content["nominal_order_setelah_diskon"];
		$counter++;
	}

	$arr_parameter = array();
	$arr_parameter["a1.order_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'");
	$arr_parameter["a1.diskon_id"] = array("=", "'". main::formatting_query_string( $_POST["diskonid"] ) ."'");
	$arr_parameter["a0.kuantitas"] = array("=", "a.kuantitas_bqtq");
	$pemakaian_saldo = @sqlsrv_fetch_array( prosedur_khusus_tambahan_diskon::daftar_order_diskon_bqtq( "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'", $arr_parameter, "sum(diskon_bqtq) total_nilai_diskon") );
	$item_order_nominal = $pemakaian_saldo["total_nilai_diskon"];
	//$item_order_nominal_formatted = main::number_format_dec( $item_order_nominal );
	$item_order_nominal_formatted = main::number_format_dec( $item_order_nominal ) . " (-) (Pemotongan Saldo BQ Rp" .  main::number_format_dec( $item_order_nominal / $obyek_diskon->persentase_budget_bisa_digunakan() ) . ")" ;
	
	foreach( $arr_tambahan_diskon_share as $diskon_id ){	
		$saldo_tersedia[ $diskon_id ] = ( $saldo_budget[ $obyek_diskon->prefiks_identifikasi_bqtq() . "Avail" ] * $obyek_diskon->persentase_budget_bisa_digunakan() ) + $item_order_nominal;
		$saldo_tersedia_formatted[ $diskon_id ] = main::number_format_dec( $saldo_tersedia[ $diskon_id ] );
		$saldo_awal[ $diskon_id ] = $saldo_tersedia[ $diskon_id ];
		$saldo_awal_formatted[ $diskon_id ] = $saldo_tersedia_formatted[ $diskon_id ];
	}
	
	if( $saldo_tersedia[ $_REQUEST["diskonid"] ] - @$item_order_nominal < 0 ){ // saldo tetap tidak mencukupi
		$warning = true;			
		
		// reset order_diskon_bqtq
		unset( $arr_parameter );
		$arr_parameter["order_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'");
		$arr_parameter["user_id"] = array("=", "'". main::formatting_query_string( $data_dealer["user_id"] ) ."'");
		$arr_parameter["diskon_id"] = array(" in ", "(". main::formatting_query_string( $_REQUEST["diskonid"] ) .")");
		prosedur_khusus_tambahan_diskon::hapus_order_diskon_bqtq( $arr_parameter );
		
		unset( $arr_parameter );
		$arr_parameter["user_id"] = array("=", "'". main::formatting_query_string( $data_dealer["user_id"] ) ."'");
		$arr_parameter["order_id"] = array("=", "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'");
		$arr_parameter["diskon_id"] = array(" in ", "(". main::formatting_query_string( $_REQUEST["diskonid"] ) .")");
		tambahan_diskon::hapus_order_diskon_item( $arr_parameter );
		
		unset( $arr_set, $arr_parameter );
		$arr_set["nilai_diskon"] = array("=", "0");		
		$arr_parameter["user_id"] = array("=", "'" . main::formatting_query_string( $data_dealer["user_id"] ) . "'");
		$arr_parameter["order_id"] = array("=", "'" . main::formatting_query_string( $_REQUEST["order_id"] ) . "'");
		$arr_parameter["diskon_id"] = array("=", "'" . main::formatting_query_string( $_REQUEST["diskonid"] ) . "'");		
		tambahan_diskon::update_order_diskon( $arr_set, $arr_parameter );				
	}
	
	
}

Skip_Semua:
?>