<?
	
	$tambahan_script_front_margin_dc_charge = "";
	
	$total_order_utk_front_margin_dc_charge = $diskon_front_margin_dc_charge = 0;
	$total_order_utk_front_margin_dc_charge = $subtotal_noncampaign + $subtotal_campaign + $total_diskon;
	
	include_once "lib/cls_prosedur_khusus_tambahan_diskon.php";
	$front_margin = prosedur_khusus_tambahan_diskon::front_margin_dc_charge( $data_dealer );
	
	function setting_net_diskon_tambahan( $data_dealer, $order_id, $front_margin_dc_charge, $front_margin /* array(tradPercentage, singkatan) */, $total_order_utk_front_margin_dc_charge ){
		unset($arr_parameter, $arr_col);
		// reset semua pengajuan tambahan diskon
		$arr_parameter["user_id"] = array("=", "'" . main::formatting_query_string( $data_dealer["user_id"] ) . "'" );
		$arr_parameter["order_id"] = array("=", "'" . main::formatting_query_string( $order_id ) . "'" );
		$arr_parameter["diskon_id"] = array("=", "'" . main::formatting_query_string( $front_margin_dc_charge ) . "'" );
		tambahan_diskon::hapus_order_diskon( $arr_parameter );
		
		$arr_col["user_id"] = "'" . main::formatting_query_string( $data_dealer["user_id"] ) . "'";
		$arr_col["order_id"] = "'" . main::formatting_query_string( $order_id ) . "'";
		$arr_col["diskon_id"] = "'" . main::formatting_query_string( $front_margin_dc_charge ) . "'";
		$arr_col["nilai_diskon"] = "'" . main::formatting_query_string( $front_margin["tradPercentage"] ) . "'";
		$arr_col["keterangan_order_diskon"] = "'" . main::formatting_query_string( $front_margin["singkatan"] ) . "'";
		$arr_col["disetujui"] = "1";
		tambahan_diskon::insert_order_diskon( $arr_col );
		
		$arr_col = array();
		$arr_col["user_id"] = "'" . main::formatting_query_string( $data_dealer["user_id"] ) . "'";
		$arr_col["order_id"] = "'" . main::formatting_query_string( $order_id ) . "'";
		$arr_col["diskon_id"] = "'" . main::formatting_query_string( $front_margin_dc_charge ) . "'";
		$arr_col["urutan"] = "'1'";
		$arr_col["disetujui"] = "1";
		tambahan_diskon::insert_order_diskon_approval( $arr_col );
		
		$diskon_front_margin_dc_charge = $total_order_utk_front_margin_dc_charge * $front_margin["tradPercentage"] / 100;
		return $diskon_front_margin_dc_charge;
	}
	
	// untuk dealer modern yg diterapkan trading term
	if( @$front_margin["tradPercentage"] > 0 ){
		
		$front_margin_dc_charge = $front_margin["diskon_id"];
		$diskon_front_margin_dc_charge = setting_net_diskon_tambahan( $data_dealer, $order_id, $front_margin_dc_charge, $front_margin /* array(tradPercentage, singkatan) */, $total_order_utk_front_margin_dc_charge );
		
	}else{
		
		// untuk dealer professional
		$front_margin_dc_charge = 27;
		$sql = "select tambahan_net tradPercentage, $front_margin_dc_charge diskon_id, 'DFMDC' singkatan  from penambahan_net_dealer where dealer = '". main::formatting_query_string( $data_dealer["idcust"] ) ."' and aktif = '1' ";
		$rs_penambahan_net_dealer_professional = sql::execute( $sql );
		if( sqlsrv_num_rows( $rs_penambahan_net_dealer_professional ) > 0 ){
			
				$front_margin = sqlsrv_fetch_array( $rs_penambahan_net_dealer_professional );
				$front_margin_dc_charge = $front_margin["diskon_id"];
				//$diskon_front_margin_dc_charge = setting_net_diskon_tambahan( $data_dealer, $order_id, $front_margin_dc_charge, $front_margin /* array(tradPercentage, singkatan) */, $total_order_utk_front_margin_dc_charge );
				
		}
		
		
	}

?>