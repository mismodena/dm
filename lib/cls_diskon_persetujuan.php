<?

class tambahan_diskon_persetujuan extends tambahan_diskon{
	
	/* key array untuk variabel $GLOBALS["arr_moa"] di lib/var.php */
	public 	$posisi,
			$nik,
			$email;

	function __construct( $order_id, $diskon_id ){
		
		$this->reset_variabel();
		
		// data tambahan diskon dealer
		$parameter["a.diskon_id"] = array("=", "'". main::formatting_query_string( $diskon_id ) ."'");
		$rs_diskon = self::daftar_tambahan_diskon( $parameter, $order_id, true );
		if( sqlsrv_num_rows( $rs_diskon ) <= 0 ) return array();
		$diskon = sqlsrv_fetch_array( $rs_diskon );

		if( is_array( @$GLOBALS["arr_moa"][ $diskon_id ] ) )
			$this->set_variabel( $diskon_id );
		
		// nilai total order
		$nominal_order = self::nominal_order( "", array( "b.order_id" => array("=",  "'". $order_id ."'" ) ) );

		// override atau mekanisme yg beda untuk penentuan mekanisme persetujuan
		$tambahan_mekanisme = __DIR__ . "/../mekanisme_persetujuan/" . $diskon_id . ".php";
		if( file_exists( $tambahan_mekanisme ) )
			require $tambahan_mekanisme;

	}
	
	private function reset_variabel(){
		$this->posisi = $this->nik = $this->email = array();
	}
	
	private function set_variabel( $diskon_id ){
		foreach( $GLOBALS["arr_moa"][ $diskon_id ] as $pic_index )				
			$this->set_variabel_detail( $pic_index );
	}
	
	private function set_variabel_detail( $pic_index ){
		if( is_array( $pic_index ) ){
			foreach( $pic_index as $pic_index_single ){
				$this->posisi[] = $GLOBALS["arr_pic"][$pic_index_single]["posisi"];
				$this->nik[] = $GLOBALS["arr_pic"][$pic_index_single]["nik"];
				$this->email[] = $GLOBALS["arr_pic"][$pic_index_single]["email"];
			}
		}else{
			$this->posisi[] = $GLOBALS["arr_pic"][$pic_index]["posisi"];
			$this->nik[] = $GLOBALS["arr_pic"][$pic_index]["nik"];
			$this->email[] = $GLOBALS["arr_pic"][$pic_index]["email"];
		}
	}
	
	public static function order_diskon_persetujuan( $arr_parameter = array() ){
		$sql = "select *, ". self::kolom_status_persetujuan() ." from order_diskon_approval c ";
		
		if ( count($arr_parameter) > 0 )
			$sql .= " where " . sql::sql_parameter( $arr_parameter );

		try{		return sql::execute( $sql  . " order by urutan " );	}
		catch(Exception $e){$e->getMessage();}
	}
	
	public static function isi_detail_order( $data_dealer, $mode = "", $item_stok = array() ){
		// cek order dealer yg blm dikirimkan
		$nominal_order = array("nominal_order" => 0);
		if( $data_dealer["order_id"] != "" )
			$nominal_order = order::nominal_order( $data_dealer["idcust"], array( "b.order_id" => array("=",  "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'" ) ) );

		//$parameter["b.order_id"] = array( "=", "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'" );
		//$rs_daftar_diskon = tambahan_diskon::daftar_tambahan_diskon( $parameter, $data_dealer["order_id"] );
				
		$rasio_campaign_non_campaign = round(100 * $nominal_order["total_order_campaign"] / $nominal_order["nominal_order"], 2) . " - " . round(100 * $nominal_order["total_order_non_campaign"] / $nominal_order["nominal_order"], 2);
		
		$rs_data_order = order::daftar_order_item( $data_dealer["order_id"] );		
		
		$template = $tabel_header = "";
		if( $mode != "" ){
			$tabel_header = "
			<tr>
				<td width=\"10px\">No</td>
				<td>Item</td>
				<td>Qty (Unit)</td>
				<td>Stok Gudang Lokal</td>
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
		$rs_diskon_per_faktur = tambahan_diskon::nilai_tambahan_diskon( $data_dealer["order_id"],  $order_sub_total, $parameter );

		$diskon_tambahan_per_faktur = 0;

		while( $diskon_per_faktur = sqlsrv_fetch_array( $rs_diskon_per_faktur ) ){
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
		
		return array( $daftar_order, $rasio_campaign_non_campaign );
	}
	
	public static function isi_email_persetujuan( $dealer_id, $order_id, $nominal_order, $rs_daftar_diskon, $nik = "" ){
		
		$nominal_order_setelah_diskon = $nominal_order["nominal_order"];
		$content = "";
		$counter = 1;
		
		$item_stok = array();
		$arr_stok = order::cek_cek_stok_item_order( $order_id );
		while( $stok = sqlsrv_fetch_array( $arr_stok ) )
			$item_stok[ $stok["item_id"] ][ $stok["gudang"] ] = $stok["kuantitas"];

		while( $diskon = sqlsrv_fetch_array( $rs_daftar_diskon ) ){
			
			$readonly = "-readonly";
			
			$arr_content = tambahan_diskon_persetujuan::detail_order_diskon_single($counter, $diskon,  $dealer_id, $order_id, $nominal_order, $rs_daftar_diskon, $nik, $readonly, $item_stok , $arr_budget_diskon_tersedia_terkait, $nominal_order_setelah_diskon);
			$content .= $arr_content["content"];
			$item_stok_habis = $arr_content["item_stok_habis"] ? $arr_content["item_stok_habis"] : $item_stok_habis ;
			$arr_budget_diskon_tersedia_terkait = $arr_content["arr_budget_diskon_tersedia_terkait"];
			$nominal_order_setelah_diskon = $arr_content["nominal_order_setelah_diskon"];
			
			$counter++;
			
		}
		
		// cek overlimit kredit dealer
		$s_overlimit = "";
		$overlimit = order::check_overlimit( $dealer_id, $nominal_order["nominal_order_net"] ) ;
		$tag_overlimit = 0;
		if( $overlimit["is_overlimit"] ){ 
			$s_overlimit = "<h4 style=\"background-color:yellow\">DEALER OVERLIMIT</h4>";
			$tag_overlimit = 1;
		}
		sql::execute("update [order] set overlimit = $tag_overlimit where order_id = '". main::formatting_query_string($order_id) ."'");
		
		$detail_order =  self::isi_detail_order( array("idcust"=>$dealer_id, "order_id"=>$order_id), "xxxx", $item_stok );
		
		$content .= 
			$s_overlimit .
			"<h4>Rasio Campaign - Non Campaign : ". $detail_order[1] ."</h4>" .
			"<h4>Detail Order</h4>" .  $detail_order[0] ;
		
		return array( $content, $nominal_order_setelah_diskon, $overlimit["is_overlimit"] );
		
	}
	
	static function kirim_email_persetujuan( $dealer_id, $dealer_nama, $order_id, $email, $nik ){
		// load dealer
		$_POST["sc"] = "cl";
		$_REQUEST["order_id"] = $order_id;

		include __DIR__ . "/../dealer.php";

		$rs_dealer = sql::execute( $sql );
		$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die( "<script>alert('Gagal mendapatkan info dealer. [fungsi : kirim email persetujuan]')</script>");
		
		$subject = "Persetujuan tambahan diskon [order no. ". $order_id ."]";
		if( $nik == "" )	$subject = "Permintaan tambahan diskon [order no. ". $order_id ."]";

		$nominal_order = order::nominal_order( $dealer_id, array( "b.order_id" => array("=",  "'". $order_id ."'" ), "b.kirim" => array("=",  "'0'" ) ) );
		//$nominal_order_setelah_diskon = $nominal_order["nominal_order"];		
		
		$parameter["b.order_id"] = array( "=", "'". main::formatting_query_string( $_REQUEST["order_id"] ) ."'" );
		$rs_daftar_diskon = tambahan_diskon::daftar_tambahan_diskon( $parameter, $_REQUEST["order_id"] );
		
		list($content, $nominal_order_setelah_diskon, $overlimit) = self::isi_email_persetujuan( $dealer_id, $order_id, $nominal_order, $rs_daftar_diskon, $nik );
		
		unset( $arr );
		$kontainer = file_get_contents("template/diskon-approval-header.html") . "<style>" . file_get_contents( __DIR__ . "/../css/main.css") . "</style>";
		$arr["#idcust#"] = $dealer_id;
		$arr["#namecust#"] = $dealer_nama;
		$arr["#nominal_order#"] = main::number_format_dec( $nominal_order["nominal_order"] );
		$arr["#nominal_order_setelah_diskon#"] = main::number_format_dec( $nominal_order_setelah_diskon );
		$arr["#diajukan_oleh#"] = $_SESSION["nama_lengkap"];
		$arr["#nomor_order#"] = $order_id;
		
		$data_detail_order = "<strong style=\"font-size:15px\">Referensi PO : " . $data_dealer["po_referensi"] . "</strong><br />";
		
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
		
		$data_detail_order .= "<strong style=\"font-size:15px\">Keterangan Tambahan</strong><br />" . $data_dealer["keterangan_order"];
		$arr["#daftar-diskon#"] = $content . $data_detail_order;
		
		$kontainer = str_replace( array_keys( $arr ), array_values( $arr ), $kontainer );

		//echo "<strong>" . $email . "</strong><br /><br />" . $kontainer . "<br /><br /><br /><hr />";
		//$email = "zaenal.fanani@Modena.co.id";
		main::send_email($email, $subject,$kontainer);
		
	}
	
	static function kirim_email_tanggapan( $dealer_id, $order_id, $diskon_id, $konten_tambahan = "" ){
		
		// load dealer
		$_POST["sc"] = "cl";
		$_REQUEST["order_id"] = $order_id;

		include __DIR__ . "/../dealer.php";

		$rs_dealer = sql::execute( $sql );
		$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die( "<script>alert('Gagal mendapatkan info dealer. [fungsi : kirim email tanggapan]')</script>");

		$subject_tambahan = "";
		if( $konten_tambahan != "" ) {
			$subject_tambahan = "[URGENT!!!]";
			
			unset( $arr_set, $parameter );
			$arr_set["kirim"] = array("=", "0");
			$parameter["order_id"] = array("=", "'" . main::formatting_query_string( $data_dealer["order_id"] ) . "'");
			sql_dm::update_order( $arr_set, $parameter );		
		}
		$subject = $subject_tambahan . "Tanggapan pengajuan tambahan diskon [order no. ". $data_dealer["order_id"] ."] ";
		if( $konten_tambahan == "NODISKON" ) {
			$konten_tambahan = $subject_tambahan = "";
			$subject = "mobile sales :: order baru [order no. ". $data_dealer["order_id"] ."] ";
		}

		$nominal_order = order::nominal_order( $dealer_id, array( "b.order_id" => array("=",  "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'" )  ) );
		//$nominal_order_setelah_diskon = $nominal_order["nominal_order"];		
		unset($parameter);
		$parameter["b.order_id"] = array( "=", "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'" );
		//$parameter["b.diskon_id"] = array( "=", "'". main::formatting_query_string( $diskon_id ) ."'" );
		$rs_daftar_diskon = tambahan_diskon::daftar_tambahan_diskon( $parameter, $data_dealer["order_id"] );
		
		list($content, $nominal_order_setelah_diskon, $overlimit) = self::isi_email_persetujuan( $dealer_id, $data_dealer["order_id"], $nominal_order, $rs_daftar_diskon );

		unset( $arr );
		$kontainer = file_get_contents("template/diskon-approval-header.html") . "<style>" . file_get_contents( __DIR__ . "/../css/main.css") . "</style>";
		$arr["#idcust#"] = $data_dealer["idcust"];
		$arr["#namecust#"] = $data_dealer["namecust"];
		$arr["#nominal_order#"] = main::number_format_dec( $nominal_order["nominal_order"] );
		$arr["#nominal_order_setelah_diskon#"] = main::number_format_dec( $nominal_order_setelah_diskon );
		$arr["#diajukan_oleh#"] = $data_dealer["nama_lengkap"];
		$arr["#nomor_order#"] = $data_dealer["order_id"];
		$arr["#alamat_memastikan_order#"] = $subject_tambahan == "" ? __NAMA_SERVER__ . "__oe_for_sure.php?order_id=" . $data_dealer["order_id"] : "";
		
		$data_detail_order = "<strong style=\"font-size:15px\">Referensi PO : " . $data_dealer["po_referensi"] . "</strong><br />";
		
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
		
		$data_detail_order .= "<strong style=\"font-size:15px\">Keterangan Tambahan</strong><br />" . $data_dealer["keterangan_order"];
		
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
	
	static function detail_order_diskon_single($counter, $diskon, $dealer_id, $order_id, $nominal_order, $rs_daftar_diskon, $nik = "", $readonly=true, $item_stok=array(), $arr_budget_diskon_tersedia_terkait=array(), $nominal_order_setelah_diskon = 0){

			$template_diskon = file_get_contents("template/diskon". ( $readonly ? "-readonly" : "" ) .".html") . "<hr width=100%/>";
			$tambahan_sufiks_template_item = $script_dikembalikan = "";
			
			// mekanisme khusus untuk diskon (misalnya karena ada integrasi BQ/TQ)
			$file_mekanisme_diskon = "mekanisme_prosedur_diskon/". $diskon["diskon_id"] .".php";

			// sementara saja, cek untuk dealer jakarta, idgrp = B, C, M, E, F, J khusus untuk BQ/TQ (diskon_id = 1,13,14)
			$sql = "select upper(ltrim(rtrim(idgrp))) idgrp from sgtdat..arcus where idcust = '". main::formatting_query_string($dealer_id) ."';";
			$cek_bcm = sqlsrv_fetch_array( sql::execute( $sql ) );
			$array_grup_cabang_aktif_bqtq = array('B','C','M','E','F','J', 'A',     'D',     'Q',     'P',     'K',     'U',     'N',     'O',     'R',
				'Y12', 'S', 'G', 'I', 'H', 'Y11', 'A5', 'A1', 'T');
			if( !in_array( $cek_bcm["idgrp"], $array_grup_cabang_aktif_bqtq ) && in_array( $diskon["diskon_id"], array(1,13,14,2,32,43,49,50,51) ) ) goto Skip_Mekanisme_Diskon;

			if( file_exists( $file_mekanisme_diskon ) ){
				include_once $file_mekanisme_diskon;
				unset( $arr_parameter );
				$arr_parameter["a3.dealer_id"] = array( "=", "'" . main::formatting_query_string( $dealer_id ) . "'" );				
				$arr_parameter["a.order_id"] = array( "=", "'" . main::formatting_query_string( $order_id ) . "'" );				
				$mekanisme_prosedur_diskon = "mekanisme_prosedur_diskon_" . $diskon["diskon_id"];
				$obyek_diskon =  ( new $mekanisme_prosedur_diskon($arr_parameter, $readonly, "", $arr_budget_diskon_tersedia_terkait ) );
				$pengganti_template_diskon =$obyek_diskon->mekanisme_prosedur_diskon();
				$template_diskon = $pengganti_template_diskon != "" ? $pengganti_template_diskon : $template_diskon;

				$arr_budget_diskon_tersedia_terkait[ $obyek_diskon->prefiks_identifikasi_bqtq() ] = $obyek_diskon->saldo_tersedia_akhir();

				$diskon_ada_yg_blm_dialokasikan = $obyek_diskon->ada_yg_blm_dialokasikan();
				$tambahan_sufiks_template_item = $obyek_diskon->sufiks_identifikasi_bqtq();
				$saldo_tersedia_awal = $obyek_diskon->saldo_tersedia_awal();

				if( method_exists($obyek_diskon, "script_dikembalikan") ) $script_dikembalikan = $obyek_diskon->script_dikembalikan();

			}
			
			Skip_Mekanisme_Diskon:
			
			// persetujuan
			$tambahan_diskon_persetujuan = new tambahan_diskon_persetujuan( $order_id, $diskon["diskon_id"] );
			
			$arr["#kelas#"] = $counter % 2 == 0 ? 1 : 2;
			$arr["#counter#"] = $counter;
			$arr["#diskon-label#"] = $diskon["diskon"] . " (KODE : ". $diskon["singkatan"] .")";
			$arr["#diskonid#"] = $diskon["diskon_id"];
			
			// override  $diskon["nilai_diskon"], apabila ada mekanisme dari folder mekanisme_diskon_default
			$readonly_disc_input = "";
			$mekanisme_default = "mekanisme_diskon_default/" . $diskon["diskon_id"] . ".php";
			if( file_exists( $mekanisme_default ) && !$readonly ){
			
				require_once $mekanisme_default;
				//$readonly_disc_input = "readonly";
				
				if( $diskon["nilai_diskon"] != "" ){

					unset( $arr_set, $arr_parameter );
					$arr_set["nilai_diskon"] = array("=", "'". main::formatting_query_string( $diskon["nilai_diskon"] ) ."'");
					//$arr_set["keterangan_order_diskon"] = array("=", "'". main::formatting_query_string( $diskon["singkatan"] ) ."'");
					if( count( $tambahan_diskon_persetujuan->posisi ) <= 0 )	// klo persetujuan tidak dibutuhkan, so langsung update status order_diskon.disetujui = 1
						$arr_set["disetujui"] = array("=", "'1'");

					$arr_parameter["order_id"] = array("=", "'" . main::formatting_query_string( $order_id ) . "'");
					$arr_parameter["diskon_id"] = array("=", "'" . main::formatting_query_string( $diskon["diskon_id"] ) . "'");
					
					tambahan_diskon::update_order_diskon( $arr_set, $arr_parameter );				
					
				}
				
			} 		
		
			$arr["#diskon#"] = $diskon["nilai_diskon"] > 100 ? main::number_format_dec
				( 
					$diskon["nilai_diskon"] != "" ? $diskon["nilai_diskon"] : $diskon["default_nilai"]
				) : ($diskon["nilai_diskon"] != "" ? $diskon["nilai_diskon"] : $diskon["default_nilai"]) ;
				
			if( $readonly )
				$arr["#diskon#"] .= " - SATUAN " . ( ( $diskon["nilai_diskon"] != "" ? $diskon["nilai_diskon"] : $diskon["default_nilai"] ) > 100 ? "RUPIAH (RP)" : "PERSEN (%)" );
			
			$arr["#readonly-disc#"] = $readonly_disc_input;
		
			$arr["#opsi_tampilan-pilih-item#"] = $diskon["pilih_item"] != 1 ? "sembunyikan" : "";
			$arr["#opsi_tampilan-pilih-free#"] = $diskon["gift_diskon"] == 2 ? "sembunyikan" : "";
		
			$arr["#diskon-label-keterangan#"] = $diskon["keterangan_diskon"] != "" ? "<br />" . $diskon["keterangan_diskon"] : "";		
			$arr["#diskon-keterangan#"] =  $diskon["keterangan_order_diskon"];

			if( !$readonly ){
				$arr_peringatan[1] = "<br /><span class=\"peringatan\">Pastikan item telah dipilih dari daftar order (diskon hanya berlaku untuk item tertentu, bukan diskon per faktur).</span>";
				$arr_peringatan[2] = "<br /><span class=\"peringatan\">Pastikan item free yang dipilih adalah item MERCHANDISE (kode 9MD).</span>";		
				$arr["#diskon-label-keterangan#"] .= @$arr_peringatan[ $diskon["wajib_pilih_item"] ];
				$arr["#display-nilai-diskon#"] = $diskon["gift_diskon"] != 1 ? "block" : "none";
				$arr["#display_lampiran#"] = $diskon["lampiran_order_diskon"] != "" ? "block"  : "block";
			}
			
			$arr["#data_lampiran#"] = $diskon["lampiran_order_diskon"];
			$arr["#url_lampiran#"] = __NAMA_SERVER__ . __UPLOAD_PATH__ . $diskon["lampiran_order_diskon"];
			
			unset($arr_parameter); $string_order_diskon_approval = "";
			$arr_parameter["order_id"] = array("=", "'". main::formatting_query_string($order_id) ."'");
			$arr_parameter["diskon_id"] = array("=", "'". main::formatting_query_string($diskon["diskon_id"]) ."'");
			$rs_order_diskon_approval = self::order_diskon_persetujuan( $arr_parameter );
			while( $order_diskon_approval = sqlsrv_fetch_array( $rs_order_diskon_approval ) ){
				
				unset($arr_order_diskon_approval);
				//$arr_order_diskon_approval["#persetujuan-melalui#"] = count( $tambahan_diskon_persetujuan->posisi ) > 0 ? implode(", ", $tambahan_diskon_persetujuan->posisi ) : 'Tidak memerlukan persetujuan';
				$arr_order_diskon_approval["#persetujuan-melalui#"] = $order_diskon_approval["disetujui_posisi"] != "" ? $order_diskon_approval["disetujui_posisi"] : 'Tidak memerlukan persetujuan';

				$link_persetujuan = "
					<a id=\"link_". $counter ."_0\" href=\"". __NAMA_SERVER__ ."diskon-approval.php?c=persetujuan_diskon&order_id=".$order_id."&dealer_id=". $dealer_id ."&diskon_id=". $diskon["diskon_id"] ."&nik=". $nik ."&mode=0\">TIDAK SETUJU</a> | 
					<a id=\"link_". $counter ."_1\" href=\"". __NAMA_SERVER__ ."diskon-pengajuan.php?c=persetujuan_diskon&order_id=".$order_id."&dealer_id=". $dealer_id ."&diskon_id=". $diskon["diskon_id"] ."&nik=". $nik ."&mode=1\">SETUJU</a>";
					
				if	( $order_diskon_approval["disetujui_oleh"] != $nik || 
						( $order_diskon_approval["disetujui_oleh"] == $nik && $order_diskon_approval["status_persetujuan"] != 1 ) ||
						$nik == ""
					) $link_persetujuan = "";
				
				$display_persetujuan = "none";
				if( in_array( $order_diskon_approval["status_persetujuan"], array( 1, 2, 3) ) ) // sudah mendapatkan persetujuan (disetujui || tidak disetujui)
					$display_persetujuan = "block";
				$arr_order_diskon_approval["#display-persetujuan#"] = $display_persetujuan;
				
				$status_persetujuan = $order_diskon_approval["status_persetujuan"] == 0 ? 4 : $order_diskon_approval["status_persetujuan"];
				//$arr_order_diskon_approval["#status-persetujuan#"] = !$readonly ? $arr_status_persetujuan[ $order_diskon_approval["status_persetujuan"] ] : $GLOBALS["arr_status_persetujuan"][ $status_persetujuan ] . " " . $link_persetujuan;
				$arr_order_diskon_approval["#status-persetujuan#"] = $GLOBALS["arr_status_persetujuan"][ $status_persetujuan ] . (!$readonly ? "" : " " . $link_persetujuan);
				$arr_order_diskon_approval["#tanggal-persetujuan#"] = is_object( $order_diskon_approval["disetujui_tanggal"] ) ? 
					$order_diskon_approval["disetujui_tanggal"]->format("d") . " " . $GLOBALS["arr_month"][ (int)$order_diskon_approval["disetujui_tanggal"]->format("m") ] . " " . $order_diskon_approval["disetujui_tanggal"]->format("Y")
					: "";
				$arr_order_diskon_approval["#keterangan-persetujuan#"] = $order_diskon_approval["disetujui_keterangan"];
				
				$string_order_diskon_approval .= str_replace( array_keys( $arr_order_diskon_approval ), array_values( $arr_order_diskon_approval ), file_get_contents("template/diskon-approval-urutan". ( $readonly ? "-readonly" : "" ) .".html") );
				
			}
			
			$arr["#persetujuan-melalui#"] = $string_order_diskon_approval != "" ? 
				$string_order_diskon_approval : 
				"Persetujuan melalui : " . ( count( $tambahan_diskon_persetujuan->posisi ) > 0 ? implode(", ", $tambahan_diskon_persetujuan->posisi ) : 'Tidak memerlukan persetujuan' ) ;
						
			// cek item diskon
			unset( $rs_diskon_item );
			
			$parameter = array();
			$parameter["b.order_id"] = array( "=", "'". $order_id ."'" );
			$parameter["a.diskon_id"] = array( "=", "'". $diskon["diskon_id"] ."'" );
			
			$template_diskon_item = file_get_contents( "template/diskon-item" . $tambahan_sufiks_template_item. ( $readonly ? "-readonly" : "" ) .".html" );
			
			if( $diskon["pilih_item"] == 1 )
				$rs_diskon_item = tambahan_diskon::daftar_order_diskon_item(  $parameter );
			elseif( $diskon["gift_diskon"] != 2 ){
				$rs_diskon_item = tambahan_diskon::daftar_order_diskon_itemfree(  $parameter );
				$template_diskon_item = file_get_contents( "template/diskon-itemfree" . $tambahan_sufiks_template_item . ( $readonly ? "-readonly" : "" ) .".html" );
			}

			$item_sudah_dipilih_utk_diskon_wajib_pilih_item = true;
			
			if( isset( $rs_diskon_item ) && sqlsrv_num_rows( $rs_diskon_item ) > 0 ){
				$s_diskon_item = "";			
				$counter_item = 1;
				
				while( $diskon_item = sqlsrv_fetch_array( $rs_diskon_item ) ){
											
					$nilai_nominal_diskon = tambahan_diskon::hitung_diskon
						( 
							@$diskon_item["item_subtotal"],
							@$diskon_item["nilai_diskon"] != "" ? @$diskon_item["nilai_diskon"] : @$diskon["default_nilai"]							
						);
				
					$nominal_order_setelah_diskon -= $nilai_nominal_diskon;
						
					$rasio_diskon = "";
					if( $diskon["pilih_item"] == 1 )
						$rasio_diskon = round($nilai_nominal_diskon * 100 / $nominal_order["nominal_order_gross"], 2) . "% / " . round($nilai_nominal_diskon * 100 / $nominal_order["nominal_order"], 2) . "%";
					elseif( $diskon["gift_diskon"] != 2 )
						$rasio_diskon = round($diskon_item["harga"] * $diskon_item["kuantitas"] * 100 / $nominal_order["nominal_order_gross"], 2) . "% / " . round($diskon_item["harga"] * $diskon_item["kuantitas"] * 100 / $nominal_order["nominal_order"], 2) . "%";
							
					$arr["#counter#"] = $counter_item;
					$arr["#kode_item#"] = $diskon_item["item_id"];
					$arr["#item_seq#"] = isset( $diskon_item["item_seq"] ) ? $diskon_item["item_seq"] : $diskon_item["item_id"];
					$arr["#nama_item#"] = $diskon_item["item_nama"];;
					$arr["#diskonid#"] = $diskon["diskon_id"];
					$arr["#kuantitas#"] = $diskon_item["kuantitas"];
					$arr["#item_subtotal#"] = main::number_format_dec( @$diskon_item["item_subtotal"] );
					$arr["#rasio#"] = $rasio_diskon;
					$arr["#subtotal-display#"] = @$diskon_item["item_subtotal"] != "" ? "block" : "none" ;
					
					 if( $item_stok[ $diskon_item["item_id"] ][ $diskon_item["gudang"] ] < 0 )	{
						 $item_stok_habis = true;		
						 $arr["#stok_habis#"] = "<sup class=\"peringatan\"><br />Mohon ubah kuantitas sesuai ketersediaan stok</sup>"; 
					 }else	$arr["#stok_habis#"] = ""; 
					 
					$arr["#stok#"] = $item_stok[ $diskon_item["item_id"] ][ $diskon_item["gudang"] ]; 
					$arr["#gudang#"] = $diskon_item["gudang"]; 
					$arr["#display-gudang-non-lokal#"] = trim(strtoupper($diskon_item["gudang"])) != trim(strtoupper($diskon_item["gudang_asal"])) ? "block" : "none" ; 
					
					$s_diskon_item .= str_replace( array_keys( $arr ), array_values( $arr ), $template_diskon_item );
					$counter_item++;
				}
				
				$arr["#daftar_item#"] = "<ol style=\"margin:0px;\">" . $s_diskon_item . "</ol>";
					
			} else {
				
				$item_sudah_dipilih_utk_diskon_wajib_pilih_item = false;
				$arr["#daftar_item#"] = "";
				if	( $diskon["gift_diskon"] != 1 && $diskon["wajib_pilih_item"] == 0 )
					$nominal_order_setelah_diskon -= tambahan_diskon::hitung_diskon
						( 
							$nominal_order_setelah_diskon, 
							$diskon["nilai_diskon"] != "" ? $diskon["nilai_diskon"] : $diskon["default_nilai"]
						);
			}						

			return array( 
						"content" => str_replace( array_keys( $arr ), array_values( $arr ), $template_diskon ),
						"item_sudah_dipilih_utk_diskon_wajib_pilih_item" => $item_sudah_dipilih_utk_diskon_wajib_pilih_item,
						"item_stok_habis" => isset( $item_stok_habis ) ? $item_stok_habis : false,
						"diskon_ada_yg_blm_dialokasikan" => $diskon_ada_yg_blm_dialokasikan,
						"arr_budget_diskon_tersedia_terkait" => $arr_budget_diskon_tersedia_terkait,
						"nominal_order_setelah_diskon" => $nominal_order_setelah_diskon,
						"saldo_tersedia_awal" => isset($saldo_tersedia_awal) ? $saldo_tersedia_awal :  0,
						"script_dikembalikan" => $script_dikembalikan
					);
			
	}
		
}

?>