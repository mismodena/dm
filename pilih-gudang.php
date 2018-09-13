<?php
	include "lib/mainclass.php";
	
	if( $_REQUEST["c"] =="update_gudang" ){

		sql::execute("UPDATE order_item SET gudang='". $_REQUEST["gudang"] ."' where order_id='". $_REQUEST["order_id"] ."' and item_id='". $_REQUEST["item"] ."' and harga=0");
				
		echo "<script>location.href='transaksi-2.php'</script>";die();
	}else{

		// load dealer
		$_POST["sc"] = "cl";
	
		include_once "dealer.php";
		$rs_dealer = sql::execute( $sql );
		$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("<script>location.href='transaksi.php'</script>");

		// cek order
		if( $data_dealer["order_id"] == "" ) die("<script>location.href='transaksi.php'</script>");

		// cek data gudang spesifik per user
		$arr_gudang_user = order::daftar_item_gudang_spesifik_utk_user();

		// cek data order per gudang
		$arr_order_gudang = array();
		$rs_data_order_gudang = order::daftar_order_item( $data_dealer["order_id"] );
		while( $data_order_gudang = sqlsrv_fetch_array( $rs_data_order_gudang ) )
			@$arr_order_gudang[ $data_order_gudang["gudang"] ] += $data_order_gudang["sub_total"];

		if( count( $arr_gudang_user ) > 0 ){
			$style = ".div-gudang-display{display:none}";
		}else{
			$style = ".div-display, .button-display{display:none}";
		}

		if($_SESSION['cabang']!='GDGPST') $arr_gudang_user[] = $_SESSION['cabang'] ;
		$arr_gudang_user[] = 'GDGPST';
		
		$rs_item = order::daftar_item_semua_gudang( $_REQUEST["item"], 0, $arr_gudang_user);

		$s_data_item = "<span class='close'>&times;</span>";
		$s_data_item .= file_get_contents( "template/gudanglain.html" );
		while( $daftar_item = sqlsrv_fetch_array( $rs_item ) ){
		
			if( count( $arr_gudang_user ) > 0 && !in_array( $daftar_item["gudang"], $arr_gudang_user ) ) continue;	

			if( $daftar_item["kuantitas"] < $_REQUEST["qty"]  ) continue;		
			
			$s_data_item .= "<div style='width:100%; float:left; margin-top:5px; border-bottom:1px solid #ccc;'>";
			$s_data_item .= '<input type="button" name="btn_'. $daftar_item["gudang"].'" id="btn_'. $daftar_item["gudang"].'" onclick="ganti_gudang(\''. trim($data_dealer["order_id"]) .'\',\''. $daftar_item["gudang"].'\',\''. $_REQUEST["item"] .'\')" value="'. $daftar_item["gudang"].' ('. $daftar_item["kuantitas"] .' Unit)" style="width:100%" />';
			$s_data_item .= '</div><div style="clear:both"></div>';		
					
		}
		
		$data_item = sqlsrv_fetch_array( $rs_item, 2, SQLSRV_SCROLL_FIRST );
		$kode_item = $data_item["itemno"];	
		$arr["#nama_item#"] = $data_item["model"];

		// mekanisme terkait dengan pengecualian net item yang disetting di tabel pengurangan_net_item_dealer_modern
		include_once "lib/cls_prosedur_khusus_tambahan_diskon.php";
		$arr_net_price_baru = prosedur_khusus_tambahan_diskon::pengurangan_net_item_dealer_modern( array($data_dealer["idcust"], $data_dealer["idgrp"]), $data_dealer["disc"] );
		$arr_net_price_baru_bertingkat = prosedur_khusus_tambahan_diskon::pengurangan_net_item_dealer_modern_bertingkat($data_dealer["idcust"], $data_dealer["disc"]);

		// untuk link ubah net dealer oleh sales
		$display_ubah_net_dealer = "none";
		if( in_array( $data_dealer["idcust"], explode(",", str_replace("'", "",$arr_dealer_wajib_professional . "," . $arr_dealer_wajib_project) ) ) )  $display_ubah_net_dealer = "inline";

		if( is_array($arr_net_price_baru) && in_array( trim($data_item["itemno"]), array_keys( $arr_net_price_baru ) ) )
			$harga_item = $arr_net_price_baru[ trim($data_item["itemno"]) ];	
		else
			$harga_item = $data_item["unitprice"];

		if( is_array( $arr_net_price_baru_bertingkat ) )
			$harga_item = in_array( trim($data_item["itemno"]), array_keys( $arr_net_price_baru_bertingkat ) ) ? $arr_net_price_baru_bertingkat[ trim($data_item["itemno"]) ] : $harga_item;
		else
			$harga_item = (100 - $arr_net_price_baru_bertingkat) * $harga_item / 100;

		$arr["#harga_item#"] = main::number_format_dec($harga_item);
		$arr["#net_dealer#"] = @$_SESSION["disc_dealer"] != "" ? $_SESSION["disc_dealer"] : $data_dealer["disc"];
		$s_data_item = str_replace( array_keys( $arr ), array_values( $arr ), $s_data_item );
		echo $s_data_item;
	}
?>