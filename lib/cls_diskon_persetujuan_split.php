<?

class tambahan_diskon_persetujuan_split extends tambahan_diskon_persetujuan{
			
	public static function isi_detail_order( $data_dealer, $mode = "", $item_stok = array() ){
		// cek order dealer yg blm dikirimkan
		$nominal_order = array("nominal_order" => 0);
		if( $data_dealer["order_id"] != "" )
			$nominal_order = order::nominal_order( $data_dealer["idcust"], 
				array( 
					"b.order_id" => array("=",  "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'" ), 
					"a.gudang" => array("=", "'". main::formatting_query_string( $data_dealer["gudang"] ) ."'") 
					) 
				);

		//$parameter["b.order_id"] = array( "=", "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'" );
		//$rs_daftar_diskon = tambahan_diskon::daftar_tambahan_diskon( $parameter, $data_dealer["order_id"] );
				
		$rasio_campaign_non_campaign = round(100 * $nominal_order["total_order_campaign"] / $nominal_order["nominal_order"], 2) . " - " . round(100 * $nominal_order["total_order_non_campaign"] / $nominal_order["nominal_order"], 2);
		
		$rs_data_order = order::daftar_order_item_split( $data_dealer["order_id_split"] );		
		
		$template = $tabel_header = "";
		if( $mode != "" ){
			$tabel_header = "
			<tr>
				<td width=\"10px\">No</td>
				<td>Item</td>
				<td>Qty (Unit)</td>
				<td>Stok Gudang</td>
				<td>Harga/Unit (Rp)</td>
				<td>Disc Campaign (Rp)</td>
				<td>Disc Tambahan (Rp)</td>
				<td>Total Disc (Rp)</td>
				<td>Total Disc (%)</td>
				<td>Sub Total (Rp)</td>
			</tr>";
			$colspan = "colspan=9";
			$template = "-table";
		}

		$template = file_get_contents("template/daftar-item". $template .".html");
		$daftar_order = "<table cellpadding=3 cellspacing=3 border=1 width=100% style=\"border:none\">" . $tabel_header;
		
		$counter = 1;
		$order_sub_total = 0;
		
		if( count( $item_stok ) <= 0 ){
			$arr_stok = order::cek_cek_stok_item_order( $data_dealer["order_id"] );
			while( $stok = sqlsrv_fetch_array( $arr_stok ) )
				$item_stok[ $stok["item_id"] ][ $stok["gudang"] ] = $stok["kuantitas"];
		}
		
		while( $data_order = sqlsrv_fetch_array( $rs_data_order ) ){
			$total_diskon_nominal = $data_order["diskon_total"];//$data_order["diskon"] + $data_order["tambahan_diskon_belum_disetujui"];
			$persentase_diskon_nominal = $data_order["sub_total"] > 0 ? round( 100 * $total_diskon_nominal  / $data_order["sub_total"], 2) : 100;
			$sub_total = $data_order["sub_total"];//$data_order["sub_total_campaign"] - $data_order["tambahan_diskon_belum_disetujui"];
			$order_sub_total += $sub_total;
			$arr["#nomor#"]=$counter;
			$arr["#nama_item#"]=$data_order["nama_item"];
			$arr["#kuantitas#"]=$data_order["kuantitas"];
			$arr["#harga#"]=main::number_format_dec($data_order["harga"]);
			$arr["#sub_total_gross#"]=main::number_format_dec($data_order["kuantitas"]*$data_order["harga"]);
			$arr["#diskon_campaign#"]=main::number_format_dec($data_order["diskon"]);
			$arr["#diskon_tambahan#"]=main::number_format_dec( $data_order["tambahan_diskon"] /*$data_order["tambahan_diskon_belum_disetujui"]*/ );
			$arr["#total_diskon_nominal#"]=main::number_format_dec( $total_diskon_nominal );
			$arr["#total_diskon_persen#"]=  $data_order["diskon_total_persen"]; //$persentase_diskon_nominal;
			$arr["#rasio_diskon_total_order#"]=round( 100 * $total_diskon_nominal / $nominal_order["nominal_order_gross"], 2 ) . "% / " . round( 100 * $total_diskon_nominal / $nominal_order["nominal_order"], 2 );
			$arr["#sub_total#"]=main::number_format_dec( $sub_total );
			$arr["#keterangan#"]=$data_order["keterangan"] . ( $data_order["keterangan"] != "" && $data_order["keterangan_diskon_tambahan"] != ""? ", " . $data_order["keterangan_diskon_tambahan"] : $data_order["keterangan_diskon_tambahan"] );
			
			 if( $item_stok[ $data_order["item_id"] ][ $data_order["gudang"] ] < 0 )	{
					 $item_stok_habis = true;		
					 $arr["#stok_habis#"] = "<sup class=\"peringatan\"><br />Mohon ubah kuantitas sesuai ketersediaan stok</sup>"; 
			 }else	$arr["#stok_habis#"] = ""; 
			$arr["#stok#"] = $item_stok[ $data_order["item_id"] ][ $data_order["gudang"] ]; 
			$arr["#gudang#"] = $data_order["gudang"]; 
			$arr["#display-gudang-non-lokal#"] = trim(strtoupper($data_order["gudang"])) != trim(strtoupper($data_order["gudang_asal"])) ? "block" : "none" ; 
			$arr["#gudang_lokal#"] = $data_order["gudang"];//trim(strtoupper($data_order["gudang"])) != trim(strtoupper($data_order["gudang_asal"])) ? "" : "YA" ; 
			
			$daftar_order .= str_replace( array_keys($arr), array_values($arr), $template );
			
			$counter++;
		}

		// diskon per faktur
		unset( $parameter );
		$parameter["b.order_id"] = array(" is ", " null ");
		$parameter["d.order_id"] = array(" is ", " null ");
		//$parameter["c.gudang"] = array("=", "'". main::formatting_query_string( $data_dealer["gudang"] ) ."'");
		$rs_diskon_per_faktur = tambahan_diskon::nilai_tambahan_diskon( $data_dealer["order_id"],  $order_sub_total, $parameter );

		$diskon_tambahan_per_faktur = 0;
		
		$total_nominal_order = array("nominal_order" => 0);
		if( $data_dealer["order_id"] != "" )
			$total_nominal_order = order::nominal_order( $data_dealer["idcust"], 
				array( 
					"b.order_id" => array("=",  "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'" )
					) 
				);

		while( $diskon_per_faktur = sqlsrv_fetch_array( $rs_diskon_per_faktur ) ){
			$diskon_per_faktur["nilai_diskon"] = $diskon_per_faktur["nilai_diskon"] * ( $order_sub_total / $total_nominal_order["nominal_order_net_exc_discfaktur"] );
			$nilai_diskon_tambahan_per_faktur = $diskon_per_faktur["nilai_diskon"] <= 100 ? ($order_sub_total - $diskon_tambahan_per_faktur ) * $diskon_per_faktur["total_nilai_persen_diskon_tambahan"] / 100 : $diskon_per_faktur["nilai_diskon"];
			$diskon_tambahan_per_faktur += $nilai_diskon_tambahan_per_faktur;
			@$daftar_diskon_per_faktur .= "
				<tr>
					<td ". $colspan ." align=left style=\"border-bottom:solid 1px #CCC; padding-top:3px; width:50%\">". $diskon_per_faktur["diskon"] ." - ". $diskon_per_faktur["singkatan"] ." ". $diskon_per_faktur["total_nilai_persen_diskon_tambahan"] ."%<br />Rasio diskon : order gross / net campaign = ". round($nilai_diskon_tambahan_per_faktur * 100 / $nominal_order["nominal_order_gross"], 2) ."% /  ". round($nilai_diskon_tambahan_per_faktur * 100 / $nominal_order["nominal_order"], 2) ."%</td>
					<td align=right style=\"border-bottom:solid 1px #CCC; padding-top:3px\">-(Rp". main::number_format_dec( $nilai_diskon_tambahan_per_faktur ) .")</td>
				</tr>";
		}
		

		$daftar_order .= "
			<tr>
				<td ". $colspan ." align=left style=\"border-bottom:solid 1px #CCC; padding-top:3px\"><strong>TOTAL</strong></td>
				<td align=right  style=\"border-bottom:solid 1px #CCC; padding-top:3px\"><strong>Rp". main::number_format_dec( $order_sub_total ) ."</strong></td>
			</tr>
			". @$daftar_diskon_per_faktur ."
			<tr>
				<td ". $colspan ."align=left style=\"border-bottom:solid 1px #CCC; padding-top:3px\"><strong>GRAND TOTAL</strong></td>
				<td align=right  style=\"border-bottom:solid 1px #CCC; padding-top:3px\"><strong>Rp". main::number_format_dec( $order_sub_total  -  $diskon_tambahan_per_faktur) ."</strong></td>
			</tr>
		</table>";
		
		return array( $daftar_order, $rasio_campaign_non_campaign, $order_sub_total  -  $diskon_tambahan_per_faktur );
	}
	
	private static function isi_email_persetujuan_split( $dealer_id, $order_id, $nominal_order, $rs_daftar_diskon, $nik = "", $gudang = "" ){
		
		$nominal_order_setelah_diskon = $nominal_order["nominal_order"];
		$content = "";
		$counter = 1;
		
		$item_stok = array();
		$arr_stok = order::cek_cek_stok_item_order( $order_id["order_id"] );
		while( $stok = sqlsrv_fetch_array( $arr_stok ) )
			$item_stok[ $stok["item_id"] ][ $stok["gudang"] ] = $stok["kuantitas"];
		
		while( $diskon = sqlsrv_fetch_array( $rs_daftar_diskon ) ){
			
			//$readonly = "-readonly";
			$readonly = true;
			
			$arr_content = self::detail_order_diskon_single($counter, $diskon,  $dealer_id, $order_id["order_id"], $nominal_order, $rs_daftar_diskon, $nik, $readonly, $item_stok , $arr_budget_diskon_tersedia_terkait, $nominal_order_setelah_diskon);
			$content .= $arr_content["content"];
			$item_stok_habis = $arr_content["item_stok_habis"] ? $arr_content["item_stok_habis"] : $item_stok_habis ;
			$arr_budget_diskon_tersedia_terkait = $arr_content["arr_budget_diskon_tersedia_terkait"];
			$nominal_order_setelah_diskon = $arr_content["nominal_order_setelah_diskon"];
			
			$counter++;						
			
		}
		
		// cek overlimit kredit dealer, nominal order direset utk dijumlahkan dengan induk order nya
		$s_overlimit = "";
		$nominal_order = order::nominal_order( $dealer_id, 
					array( 
						"b.order_id" => array("=",  "'". main::formatting_query_string( $order_id["order_id"] ) ."'" ),
					) 
				);
		$overlimit = order::check_overlimit( $dealer_id, $nominal_order["nominal_order_net"] ) ;
		if( $overlimit["is_overlimit"] ) 
			$s_overlimit = "<h4 style=\"background-color:yellow\">DEALER OVERLIMIT</h4>";
		
		$detail_order =  self::isi_detail_order( array("idcust"=>$dealer_id, "order_id"=>$order_id["order_id"], "order_id_split"=>$order_id["order_id_split"], "gudang" => $gudang ), "xxxx", $item_stok );
		
		$content .= 
			$s_overlimit .
			"<h4>Rasio Campaign - Non Campaign : ". $detail_order[1] ."</h4>" .
			"<h4>Detail Order</h4>" .  $detail_order[0] ;
			
		$nominal_order_setelah_diskon = $detail_order[2];
		
		return array( $content, $nominal_order_setelah_diskon, $overlimit["is_overlimit"] );
		
	}
		
	
	static function kirim_email_tanggapan_split( $dealer_id, $order_id, $order_id_split, $gudang, $diskon_id, $konten_tambahan = "" ){
		
		// load dealer
		$_POST["sc"] = "cl";
		$_REQUEST["order_id"] = $order_id;

		include __DIR__ . "/../dealer.php";

		$rs_dealer = sql::execute( $sql );
		$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die( "<script>alert('Gagal mendapatkan info dealer. [fungsi : kirim email tanggapan]')</script>");

		$subject_tambahan = "";
		if( $konten_tambahan != "" ) $subject_tambahan = "[URGENT!!!]";
		$subject = $subject_tambahan . "Tanggapan pengajuan tambahan diskon [order no. ". $order_id_split ."] ";
		if( $konten_tambahan == "NODISKON" ) {
			$konten_tambahan = "";
			$subject = "mobile sales :: order baru [order no. ". $order_id_split ."] ";
		}

		$nominal_order = order::nominal_order( $dealer_id, 
					array( 
						"b.order_id" => array("=",  "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'" ),
						"a.gudang" => array("=", "'". main::formatting_query_string( $data_dealer["cabang"] ) ."'") 
					) 
				);
		//$nominal_order_setelah_diskon = $nominal_order["nominal_order"];		
		
		$parameter["b.order_id"] = array( "=", "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'" );
		//$parameter["b.diskon_id"] = array( "=", "'". main::formatting_query_string( $diskon_id ) ."'" );
		$rs_daftar_diskon = tambahan_diskon::daftar_tambahan_diskon( $parameter, $data_dealer["order_id"] );
		
		$arr_order_id = array( "order_id" => $order_id, "order_id_split" => $order_id_split );
		list($content, $nominal_order_setelah_diskon, $overlimit) = self::isi_email_persetujuan_split( $dealer_id, $arr_order_id, $nominal_order, $rs_daftar_diskon, "", $gudang );

		unset( $arr );
		$kontainer = file_get_contents("template/diskon-approval-header.html") . "<style>" . file_get_contents( __DIR__ . "/../css/main.css") . "</style>";
		$arr["#idcust#"] = $data_dealer["idcust"];
		$arr["#namecust#"] = $data_dealer["namecust"];
		$arr["#nominal_order#"] = main::number_format_dec( $nominal_order["nominal_order"] );
		$arr["#nominal_order_setelah_diskon#"] = main::number_format_dec( $nominal_order_setelah_diskon );
		$arr["#diajukan_oleh#"] = $data_dealer["nama_lengkap"];
		$arr["#nomor_order#"] = $order_id_split; 
		$arr["#alamat_memastikan_order#"] = __NAMA_SERVER__ . "__oe_for_sure.php?order_id=" . $data_dealer["order_id"] . "&order_id_split=" . $order_id_split;
		
		$data_detail_order = "<strong style=\"font-size:13px\">Referensi PO : " . $data_dealer["po_referensi"] . "</strong><br />";
		
		if( trim( $data_dealer["nama_kirim"] ) != "" ){
			$data_detail_order .= "<br /><strong style=\"font-size:15px\">Alamat Pengiriman</strong><br />" . 
				$data_dealer["nama_kirim"] . "<br />" . $data_dealer["alamat_kirim"] . " " . $data_dealer["kota_kirim"] . " " . $data_dealer["propinsi_kirim"] . "<br />" .
				$data_dealer["telp_kirim"] . " / " . $data_dealer["hp_kirim"] . "<br /><br />"
				;
		
			if( trim( $data_dealer["nama_tagih"] ) != "" )
				$data_detail_order .= "<strong style=\"font-size:15px\">Alamat Penagihan</strong><br />" . 
					$data_dealer["nama_tagih"] . "<br />" . $data_dealer["alamat_tagih"] . " " . $data_dealer["kota_tagih"] . " " . $data_dealer["propinsi_tagih"] . "<br />" .
					$data_dealer["telp_tagih"] . " / " . $data_dealer["hp_tagih"] . "<br /><br />"
					;
			else
				$data_detail_order .= "<strong style=\"font-size:15px\">Alamat Penagihan</strong><br />Sesuai dengan alamat dealer<br /><br />" ;
			
		}
				
		$data_detail_order .= "<strong style=\"font-size:13px\">Keterangan Tambahan</strong><br />" . $data_dealer["keterangan_order"];
		
		// tambah script utk register log
		$content .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"". __NAMA_SERVER__ ."__register_email.php?order_id=". $data_dealer["order_id"] ."\" />";
		
		$arr["#daftar-diskon#"] = $konten_tambahan . $content . $data_detail_order;
		$kontainer = str_replace( array_keys( $arr ), array_values( $arr ), $kontainer );

		//echo $data_dealer["email"] . "<br />" . $kontainer . "<br /><br /><br />";
		//$data_dealer["email"] = "zaenal.fanani@modena.co.id";
		
		// untuk dealer yg ada setting email_cabang, ditambahkan ke $data_dealer["email"]
		$sql = "select distinct email_sales from order_item a, email_cabang b where a.gudang = b.kode_gudang and a.order_id = '". main::formatting_query_string( $data_dealer["order_id"] ) ."';";
		$rs_email_cabang = sql::execute($sql);
		while( $email_cabang = sqlsrv_fetch_array( $rs_email_cabang ) )
			$data_dealer["email"] .= $email_cabang["email_sales"] != "" ? ", " . $email_cabang["email_sales"] : "";
		
		// email admin sales sesuai kode teritori dealer ybs
		list( $order_id_cabang, $order_id_userid, $order_id_nomorurut ) = explode("-", $data_dealer["order_id"]);
		$sql = "select b.* from SGTDAT..MIS_TERRITORY a inner join email_cabang B ON A.cabang = b.inisial_cabang where a.terrAlias = '". main::formatting_query_string( $order_id_cabang ) ."'";
		$rs_email_cabang = sql::execute($sql);
		while( $email_cabang = sqlsrv_fetch_array( $rs_email_cabang ) )
			$data_dealer["email"] .= $email_cabang["email_sales"] != "" ? ", " . $email_cabang["email_sales"] : "";
		
		if( $overlimit ) $data_dealer["email"] .= ", " . $data_dealer["email_finance_untuk_overlimit"];
		
		main::send_email($data_dealer["email"], $subject,$kontainer);
	}
		
}

?>