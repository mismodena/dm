<?

if( $_REQUEST["c"] == "kirim_order" || $_REQUEST["c"] == "kirim_order_daripersetujuan" ){
	
	// ubah order.order_id yg draft menjadi format nomor order M-XXXX
	// ubah order.status kirim menjadi 1

	$_SESSION["order_id"] = $data_dealer["order_id"];
	$order_id_kirim = $data_dealer["order_id"];	
	
	// ubah nomor order menjadi asli, kecuali untuk yg mengajukan diskon tambahan (karena proses pengajuan diskon tambahan sudah mengubah nomor order menjadi asli)
	//if( $data_dealer["pengajuan_diskon"] != 1 )	$order_id_kirim = trim( order::orderid( $data_dealer["idcust"], $data_dealer["disc"], false ) );	

	$arr_set["order_id"] = array("=", "'". $order_id_kirim ."'");
	$arr_set["kirim"] = array("=", "'1'");
	$arr_set["tanggal"] = array("=", "getdate()");
	$arr_parameter["order_id"] = array("=", "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'");
	sql_dm::update_order( $arr_set, $arr_parameter );
	
	$data_dealer["order_id"] = $order_id_kirim;
	
	include_once "transaksi-3.php.command.ext.php";
	
	// nominal order
	$nominal_order = order::nominal_order( $data_dealer["idcust"], array( "b.order_id" => array("=",  "'". $data_dealer["order_id"] ."'" ), "b.kirim" => array("=",  "'1'" ) ) );
	
	// cek overlimit
	$status_order = 0;
	$overlimit = order::check_overlimit( $data_dealer["idcust"], $nominal_order["nominal_order_net"] ) ;
	if( $overlimit["is_overlimit"] ) $status_order = 1;
	
	// cek ulang nilai stok tersedia vs unit pembelian	
	unset($arr_stok_item);
	$rs_cek_stok = order::cek_cek_stok_item_order($data_dealer["order_id"]);
	while( $cek_stok = sqlsrv_fetch_array($rs_cek_stok) )
		@$arr_stok_item[ $cek_stok["item_id"] ] = $cek_stok["kuantitas"];
	
	$string_peringatan_stok_kosong = order::cek_stok_kosong( $data_dealer, $arr_stok_item );
			
	if( $string_peringatan_stok_kosong != "" ){	
		
		if( $_REQUEST["c"] == "kirim_order_daripersetujuan" ){
			tambahan_diskon_persetujuan::kirim_email_tanggapan( $data_dealer["idcust"], $data_dealer["order_id"], "", $string_peringatan_stok_kosong );
			
			die("<script>alert('Pengajuan otomatis dibatalkan karena terdapat perubahan stok item dalam order!\nEmail telah dikirimkan juga ke sales bersangkutan untuk melakukan review order ini.');window.close();</script>");
		}
	
		echo $string_peringatan_stok_kosong;
		include "includes/bottom.php";
		exit;
	}		
	
	$order_split = order::kirim_data_ke_accpac( $data_dealer, $nominal_order, $status_order );		
	
	// entri data BQ/TQ
	include_once "lib/cls_prosedur_khusus_tambahan_diskon.php";
	prosedur_khusus_tambahan_diskon::kirim_data_ke_bqtq( $data_dealer["order_id"] );
	
	$_REQUEST["order_id"] = $data_dealer["order_id"];
	
	$flag_subject = "NODISKON";
	if( $_REQUEST["c"] == "kirim_order_daripersetujuan" ) $flag_subject = "";
	
	if( !$order_split )	tambahan_diskon_persetujuan::kirim_email_tanggapan( $data_dealer["idcust"], $data_dealer["order_id"], "", $flag_subject );
	
	else{

		$rs_order_split = order::daftar_order_original_split( array("a.order_id" => array("=", "'". main::formatting_query_string($data_dealer["order_id"]) ."'") ) );
		while( $data_order_split = sqlsrv_fetch_array( $rs_order_split ) )
			tambahan_diskon_persetujuan_split::kirim_email_tanggapan_split( $data_dealer["idcust"], $data_dealer["order_id"], $data_order_split["order_id_split"], $data_order_split["gudang"], "", $flag_subject );
		
	}
	
	// auto ppok jika overlimit --
	include "auto_ppok.php";

	if( $_REQUEST["c"] == "kirim_order_daripersetujuan" ){
		if( isset( $_REQUEST["sc"] ) )
			$tambahan_script = "try{opener.document.location.reload();}catch(e){location.href='diskon-persetujuan.php?dealer=". $data_dealer["idcust"] ."&order_id=".$data_dealer["order_id"]."'}";
		
		die( "<script>". @$tambahan_script ."alert('Persetujuan berhasil direkam di database!\\nData order sudah sudah di-entri ke dalam ACCPAC.'); window.close();</script>" );
	}
	
	echo "<script>location.href='transaksi-4.php?c=kirim_order_daripersetujuan&order_id=". $data_dealer["order_id"] ."&split=". ( $order_split ? sha1($data_dealer["order_id"]) : "" ) ."'</script>";
	echo "Nomor Order : " . $data_dealer["order_id"];
	exit;
}

?>