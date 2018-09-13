<?
include "includes/top_blank.php";

// simpan semua query string
foreach( $_REQUEST as $index=>$nilai )
	echo "<input type=\"hidden\" name=\"". $index ."\" value=\"". $nilai ."\" />";

// load dealer
$_POST["sc"] = "cl";

include_once "dealer.php";
$rs_dealer = sql::execute( $sql . " and c.order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."' "  );
$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("<script>location.href='diskon.php'</script>");

//if( $data_dealer["order_id"] == "" ||  $data_dealer["kode_sales"] != $_SESSION["sales_kode"] )
//	$data_dealer["order_id"] = order::orderid( $data_dealer["idcust"], 0 /*$data_dealer["disc"]*/ );
$data_dealer["order_id"] = $_REQUEST["order_id"];

$nominal_order = array("nominal_order" => 0);
if( $data_dealer["order_id"] != "" )
	$nominal_order = order::nominal_order( $data_dealer["idcust"], array( "b.order_id" => array("=",  "'". $data_dealer["order_id"] ."'" ), "b.kirim" => array("=",  "'0'" ) ) );

$nominal_order_setelah_diskon = $nominal_order["nominal_order"];

$item_stok_habis = false;

$item_stok = array();
$arr_stok = order::cek_cek_stok_item_order( $data_dealer["order_id"] );
while( $stok = sqlsrv_fetch_array( $arr_stok ) )
	$item_stok[ $stok["item_id"] ][ $stok["gudang"] ] = $stok["kuantitas"];

$parameter["b.order_id"] = array( "=", "'". main::formatting_query_string( $data_dealer["order_id"] ) ."'" );
$rs_daftar_diskon = tambahan_diskon::daftar_tambahan_diskon( $parameter, $data_dealer["order_id"] );
while( $diskon = sqlsrv_fetch_array( $rs_daftar_diskon ) ){

	// cek item diskon
	unset( $rs_diskon_item );
	
	$parameter = array();
	$parameter["b.order_id"] = array( "=", "'". $data_dealer["order_id"] ."'" );
	$parameter["a.diskon_id"] = array( "=", "'". $diskon["diskon_id"] ."'" );
				
	if( $diskon["pilih_item"] == 1 )
		$rs_diskon_item = tambahan_diskon::daftar_order_diskon_item(  $parameter );
	elseif( $diskon["gift_diskon"] != 2 )
		$rs_diskon_item = tambahan_diskon::daftar_order_diskon_itemfree(  $parameter );

	if( isset( $rs_diskon_item ) && sqlsrv_num_rows( $rs_diskon_item ) > 0 ){
		$s_diskon_item = "";			
		
		while( $diskon_item = sqlsrv_fetch_array( $rs_diskon_item ) ){
			
			$nominal_order_setelah_diskon -= tambahan_diskon::hitung_diskon
					( 
						@$diskon_item["item_subtotal"],
						@$diskon_item["nilai_diskon"] != "" ? @$diskon_item["nilai_diskon"] : @$diskon["default_nilai"]							
					);
			
		}
			
	} else {
		if	( $diskon["gift_diskon"] != 1 && $diskon["wajib_pilih_item"] == 0 )
			$nominal_order_setelah_diskon -= tambahan_diskon::hitung_diskon
				( 
					$nominal_order_setelah_diskon, 
					$diskon["nilai_diskon"] != "" ? $diskon["nilai_diskon"] : $diskon["default_nilai"]
				);
	}
		
	if( $diskon["diskon_id"] == $_REQUEST["diskon_id"] ){
				
			$template_diskon = file_get_contents("template/diskon-readonly.html");
			
			$readonly = "-readonly";
			$file_mekanisme_diskon = "mekanisme_prosedur_diskon/". $diskon["diskon_id"] .".php";
			
			if( file_exists( $file_mekanisme_diskon ) ){
				include_once $file_mekanisme_diskon;
				unset( $arr_parameter );
				$arr_parameter["a3.dealer_id"] = array( "=", "'" . main::formatting_query_string( $data_dealer["dealer_id"] ) . "'" );				
				$arr_parameter["a.order_id"] = array( "=", "'" . main::formatting_query_string( $data_dealer["order_id"] ) . "'" );				
				$mekanisme_prosedur_diskon = "mekanisme_prosedur_diskon_" . $diskon["diskon_id"];
				$obyek_diskon =  ( new $mekanisme_prosedur_diskon($arr_parameter, $readonly, "", $arr_budget_diskon_tersedia_terkait ) );
				$pengganti_template_diskon =$obyek_diskon->mekanisme_prosedur_diskon();
				$template_diskon = $pengganti_template_diskon != "" ? $pengganti_template_diskon : $template_diskon;

				$arr_budget_diskon_tersedia_terkait[ $obyek_diskon->prefiks_identifikasi_bqtq() ] = $obyek_diskon->saldo_tersedia_akhir();

				$diskon_ada_yg_blm_dialokasikan = $obyek_diskon->ada_yg_blm_dialokasikan();
				$tambahan_sufiks_template_item = $obyek_diskon->sufiks_identifikasi_bqtq();
				$saldo_tersedia_awal = $obyek_diskon->saldo_tersedia_awal();

			}
				
			$arr["#kelas#"] = 2;
			$arr["#counter#"] = 1;
			$arr["#diskon-label#"] = $diskon["diskon"];
			$arr["#diskonid#"] = $diskon["diskon_id"];
			$arr["#diskon#"] = main::number_format_dec
				( 
					$diskon["nilai_diskon"] != "" ? $diskon["nilai_diskon"] : $diskon["default_nilai"]
				);
			
			$arr["#diskon#"] .= " - SATUAN " . ( ( $diskon["nilai_diskon"] != "" ? $diskon["nilai_diskon"] : $diskon["default_nilai"] ) > 100 ? "RUPIAH (RP)" : "PERSEN (%)" );
			
			$arr["#diskon-label-keterangan#"] = $diskon["keterangan_diskon"] != "" ? "<br />" . $diskon["keterangan_diskon"] : "";		
			$arr["#diskon-keterangan#"] =  $diskon["keterangan_order_diskon"];
			
			$arr["#data_lampiran#"] = $diskon["lampiran_order_diskon"];
			$arr["#url_lampiran#"] = __NAMA_SERVER__ . __UPLOAD_PATH__ . $diskon["lampiran_order_diskon"];
			
			// persetujuan
			/*
			$tambahan_diskon_persetujuan = new tambahan_diskon_persetujuan( $data_dealer["order_id"], $diskon["diskon_id"] );			
			$arr["#persetujuan-melalui#"] = count( $tambahan_diskon_persetujuan->posisi ) > 0 ? implode(", ", $tambahan_diskon_persetujuan->posisi ) : 'Tidak memerlukan persetujuan';
			$status_persetujuan = $diskon["status_persetujuan"] == 0 ? 4 : $diskon["status_persetujuan"];
			// stop persetujuan klo sudah disetujui / tidak disetujui sebelumnya 
			if( in_array( $status_persetujuan, array( 2, 3 ) ) && $diskon["disetujui_oleh"] == $_REQUEST["nik"] )
				die("<script>alert('Pengajuan tambahan diskon ini sudah mendapatkan persetujuan!');window.close()</script>");
			$arr["#status-persetujuan#"] = $GLOBALS["arr_status_persetujuan"][ $status_persetujuan ];
			$arr["#tanggal-persetujuan#"] = "";
			$arr["#keterangan-persetujuan#"] = "";
			*/
			if( in_array( $status_persetujuan, array( 2, 3 ) ) && $diskon["disetujui_oleh"] == $_REQUEST["nik"] )
				die("<script>alert('Pengajuan tambahan diskon ini sudah mendapatkan persetujuan!');window.close()</script>");
			
			$order_id = $data_dealer["order_id"];
			unset($arr_parameter); $string_order_diskon_approval = "";
			$arr_parameter["order_id"] = array("=", "'". main::formatting_query_string($order_id) ."'");
			$arr_parameter["diskon_id"] = array("=", "'". main::formatting_query_string($diskon["diskon_id"]) ."'");
			$rs_order_diskon_approval = tambahan_diskon_persetujuan::order_diskon_persetujuan( $arr_parameter );
			while( $order_diskon_approval = sqlsrv_fetch_array( $rs_order_diskon_approval ) ){
				
				unset($arr_order_diskon_approval);
				//$arr_order_diskon_approval["#persetujuan-melalui#"] = count( $tambahan_diskon_persetujuan->posisi ) > 0 ? implode(", ", $tambahan_diskon_persetujuan->posisi ) : 'Tidak memerlukan persetujuan';
				$arr_order_diskon_approval["#persetujuan-melalui#"] = $order_diskon_approval["disetujui_posisi"] != "" ? $order_diskon_approval["disetujui_posisi"] : 'Tidak memerlukan persetujuan';
									
				$display_persetujuan = "none";
				if( in_array( $order_diskon_approval["status_persetujuan"], array( 1, 2, 3) ) ) // sudah mendapatkan persetujuan (disetujui || tidak disetujui)
					$display_persetujuan = "block";
				$arr_order_diskon_approval["#display-persetujuan#"] = $display_persetujuan;
				
				$status_persetujuan = $order_diskon_approval["status_persetujuan"] == 0 ? 4 : $order_diskon_approval["status_persetujuan"];
				//$arr_order_diskon_approval["#status-persetujuan#"] = !$readonly ? $arr_status_persetujuan[ $order_diskon_approval["status_persetujuan"] ] : $GLOBALS["arr_status_persetujuan"][ $status_persetujuan ] . " " . $link_persetujuan;
				$arr_order_diskon_approval["#status-persetujuan#"] = $GLOBALS["arr_status_persetujuan"][ $status_persetujuan ];
				$arr_order_diskon_approval["#tanggal-persetujuan#"] = is_object( $order_diskon_approval["disetujui_tanggal"] ) ? 
					$order_diskon_approval["disetujui_tanggal"]->format("d") . " " . $GLOBALS["arr_month"][ (int)$order_diskon_approval["disetujui_tanggal"]->format("m") ] . " " . $order_diskon_approval["disetujui_tanggal"]->format("Y")
					: "";
				$arr_order_diskon_approval["#keterangan-persetujuan#"] = $order_diskon_approval["disetujui_keterangan"];
				
				$string_order_diskon_approval .= str_replace( array_keys( $arr_order_diskon_approval ), array_values( $arr_order_diskon_approval ), file_get_contents("template/diskon-approval-urutan-readonly.html") );
				
			}
			
			$arr["#persetujuan-melalui#"] = $string_order_diskon_approval != "" ? 
				$string_order_diskon_approval : 
				"Persetujuan melalui : " . ( count( $tambahan_diskon_persetujuan->posisi ) > 0 ? implode(", ", $tambahan_diskon_persetujuan->posisi ) : 'Tidak memerlukan persetujuan' ) ;
			
			
			// cek item diskon
			unset( $rs_diskon_item );
			
			$parameter = array();
			$parameter["b.order_id"] = array( "=", "'". $data_dealer["order_id"] ."'" );
			$parameter["a.diskon_id"] = array( "=", "'". $diskon["diskon_id"] ."'" );
			
			$template_diskon_item = file_get_contents( "template/diskon-item-readonly.html" );
			
			if( $diskon["pilih_item"] == 1 )
				$rs_diskon_item = tambahan_diskon::daftar_order_diskon_item(  $parameter );
			elseif( $diskon["gift_diskon"] != 2 ){
				$rs_diskon_item = tambahan_diskon::daftar_order_diskon_itemfree(  $parameter );
				$template_diskon_item = file_get_contents( "template/diskon-itemfree-readonly.html" );
			}

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
				$arr["#daftar_item#"] =  "<ul class=\"list\">" . $s_diskon_item . "</ul>";
					
			} else {
				$arr["#daftar_item#"] = "";
			}						
			
			$data_diskon = str_replace( array_keys( $arr ), array_values( $arr ), $template_diskon );			
							
	}

}	
			
$kontainer = file_get_contents("template/diskon-approval-header.html");
$arr["#idcust#"] = $data_dealer["idcust"];
$arr["#namecust#"] = $data_dealer["namecust"];
$arr["#nomor_order#"] = $data_dealer["order_id"];
$arr["#nominal_order#"] = main::number_format_dec( $nominal_order["nominal_order"] );
$arr["#diajukan_oleh#"] = $data_dealer["nama_lengkap"];
$arr["#nominal_order_setelah_diskon#"] = main::number_format_dec( $nominal_order_setelah_diskon );
$arr["#daftar-diskon#"] = "";
$kontainer = str_replace( array_keys( $arr ), array_values( $arr ), $kontainer );

echo $kontainer;
echo "<div><h4>Proses persetujuan untuk tambahan diskon</h4></div>";
echo $data_diskon;

if( isset( $_REQUEST["sc"] ) )
	$tambahan_kueri_string = "&sc=". sha1(rand(0, 1000));

?>
<script>
function kirim_persetujuan(){
	var ket = document.getElementById('keterangan');
	if( ket.value == '' ){alert('Isikan keterangan ketidaksetujuan!');return false}
	__submit('diskon-pengajuan.php', 'c=persetujuan_diskon<?=@$tambahan_kueri_string?>');
}
</script>
<div style="margin:11px 0px 7px 0px">
	Isikan keterangan <strong>ketidaksetujuan</strong> pengajuan diskon tambahan :<br />
	<textarea name="keterangan" id="keterangan" style="height:47px; width:100%; margin:7px 0px 7px 0px"></textarea><br />
	<input type="button" name="kirim" id="kirim" value="Kirim Persetujuan" onclick="javascript:kirim_persetujuan()" />
</div>
<?
include "includes/bottom.php";
?>