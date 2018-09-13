<?

$data_order = "";
$subtotal_noncampaign = $subtotal_campaign = $total_diskon = 0;

if( $dm->belum_ada_order)  goto SkipDM;
//if( @$page == "simulasi.php" ) goto Start_Simulasi;

// cek ketersediaan stok
$item_stok = $dm->item_stok;

$data_order = "<h3 class=\"sub-judul\" style=\"background-color:#fafc9f\">:: Daftar Item Non Campaign :: </h3>";
$template = file_get_contents("template/item-non-paket.html");
$item_non_paket = "";
$counter = 1;
$item_stok_habis = false;

if( is_array($dm->arr_item_non_paket) ){
	foreach( $dm->arr_item_non_paket as $arr_itemseq_item_harga_kuantitas_saran_paket ){
		list($itemseq, $item, $harga, $kuantitas, $saran_paket, $gudang, $gudang_asal, $diskon_id, $kuantitas_diskon_item) = array_values($arr_itemseq_item_harga_kuantitas_saran_paket);
		@$subtotal_noncampaign += $harga * $kuantitas;
		unset( $arr_paket );
		
		foreach( $saran_paket as $paket => $keterangan_paket )
			$arr_paket[] =  "<input type=\"radio\" name=\"r_". $itemseq ."\" id=\"r_". $itemseq ."_". $paket ."\" value=\"". $paket ."\" />&nbsp;&nbsp;&nbsp;
				<label for=\"r_". $itemseq ."_". $paket ."\"><strong><a href=\"paket-detail.php?paketid=". $paket ."\" style=\"color:blue;\">" . $paket . "</a></strong> :: " . $keterangan_paket . "</label>";

		$arr_data["#kelas#"] = $counter % 2 ==0 ? 1 : 2; 
		$arr_data["#item#"] = $item; 
		$arr_data["#item_seq#"] = $itemseq; 
		$arr_data["#itemdesc#"] = $dm->arr_item_nama[ $item ]; 
		$arr_data["#harga#"] = main::number_format_dec( $harga ); 
		
		 if( $item_stok[ $item ][ $gudang ] < 0 )	$item_stok_habis = true;		
		$arr_data["#stok#"] = $item_stok[ $item ][$gudang] < 0 ? 0 : $item_stok[ $item ][$gudang]; 
		$arr_data["#kuantitas#"] = $kuantitas;
		
		$arr_data["#gudang#"] = $gudang; 
		$arr_data["#display-gudang-non-lokal#"] = trim(strtoupper($gudang)) != trim(strtoupper($gudang_asal)) ? "block" : "none" ; 
		$arr_data["#subtotal#"] = main::number_format_dec( $harga * $kuantitas ) . ( $item_stok[ $item ][$gudang] < 0 ? "<sup class=\"peringatan\"><br />Mohon ubah kuantitas sesuai ketersediaan stok</sup>" : "" );
		$arr_data["#saranpaket#"] = @implode("<br />", $arr_paket); 
		$arr_data["#btn_display#"] = isset( $arr_paket ) ? "block" : "none"; 
		
		$arr_data["#data-tambahan-diskon#"] = ""; 
		$arr_data["#diskonid#"] = $itemseq; 
		$arr_data["#disabled#"] = $item_stok[ $item ][$gudang] < 0 ? "disabled" : ""; 

		// info tambahan diskon yg sudah diterapkan
		$string_tambahan_diskon_diterapkan = $item_seq_asal = "";
		$item_seq_exists_di_order_diskon_item = false;
		if( is_array($dm->arr_item_diterapkan_tambahan_diskon) ){
			foreach( $dm->arr_item_diterapkan_tambahan_diskon as $_diskon_id_diskon_id => $arr_data_item_seq ){
				if( array_key_exists( $itemseq, $arr_data_item_seq ) ){
					$string_tambahan_diskon_diterapkan .= "&raquo; ". $arr_data_item_seq[ $itemseq ]["diskon"] . "<br />";
					$kuantitas_diskon_item = $arr_data_item_seq[ $itemseq ]["kuantitas_diskon_item"];
					$item_seq_asal = $arr_data_item_seq[ $itemseq ]["item_seq_asal"];
					
					if( !$item_seq_exists_di_order_diskon_item && @$_REQUEST["diskonid"] != ""  )
						$item_seq_exists_di_order_diskon_item =  true;				
				}
			}
		}		

		if( $diskon_id == @$_REQUEST["diskonid"] ){
			$arr_data["#checked-cb#"] = "checked";
			$arr_data["#diskon-diterapkan#"] = "diskon-diterapkan";
			$arr_data["#display-kuantitas-diskon-item#"] = "block";
			$arr_data["#kuantitas_diskon_item#"] = $kuantitas_diskon_item;			
						
		}else { 			
			//if( $order["diskon_id"] != "" ) continue; // tidak bisa double tambahan diskon
			$arr_data["#checked-cb#"] = "";
			$arr_data["#diskon-diterapkan#"] = "";
			$arr_data["#display-kuantitas-diskon-item#"] = "none";
			$arr_data["#kuantitas_diskon_item#"] = $item_seq_asal != "" || $string_tambahan_diskon_diterapkan != "" ? $kuantitas_diskon_item : 1;

		}
		$arr_data["#readonly-kuantitas-diskon-item#"] = $item_seq_asal != "" || $item_seq_exists_di_order_diskon_item ? "readonly" : "";
		
		$arr_data["#display-tambahan-diskon-diterapkan#"] = $string_tambahan_diskon_diterapkan != "" ? "block" : "none";
		$arr_data["#tambahan-diskon-diterapkan#"] = $string_tambahan_diskon_diterapkan != "" ? "Diskon tambahan diterapkan untuk item ini :<br />" . $string_tambahan_diskon_diterapkan . "<br />" : "";
		
		$item_non_paket .= str_replace( array_keys( $arr_data ), array_values( $arr_data ),  $template);
		$counter++;
	}
	$data_order .= $item_non_paket  . "<h3>Total Order Non Campaign :: Rp" . main::number_format_dec($subtotal_noncampaign) . "</h3>" ;
}else $data_order .= "Tidak ada item non campaign!";

$data_order .= " <h3 class=\"sub-judul\">:: Daftar Item Campaign :: </h3>";

Start_Simulasi:

$total_diskon = 0;
$total_cn = 0;
$template = file_get_contents("template/item-paket.html");
$item_paket = "";
$counter = 1;
if( is_array( $dm->arr_item_dengan_paket ) ){
	
	// untuk sort item berdasarkan campaign
	$data_order .= "<div style=\"margin-bottom:13px\">Lihat item berdasarkan campaign : <br />
		<a href=\"javascript:tunjukkan_semua_item_paket()\">Semua Item</a>";	
	foreach( $dm->arr_keterangan_paket as $paketid=>$keterangan_paket )
		$data_order .= " | <a href=\"javascript:filter_item_paket('". $paketid ."')\">". $paketid ." (<span id=\"shortcut-paket-". $paketid ."\"></span>)</a>";
	$data_order .= "</div>";
		
	foreach( $dm->arr_item_dengan_paket as $arr_itemseq_item_harga_kuantitas_paketid){
		list($itemseq, $item, $harga, $kuantitas, $paketid, $gudang, $gudang_asal, $diskon_id, $kuantitas_diskon_item) = array_values( $arr_itemseq_item_harga_kuantitas_paketid);
		//$total_diskon += @$dm->arr_item_diskon[ $item ][ $paketid ];
		$total_diskon += @$dm->arr_item_diskon[ $itemseq ][ $paketid ];	
		$total_cn += @$dm->arr_item_cn[ $itemseq ][ $paketid ];	
		//$subtotal_diskon = ( $harga * $kuantitas ) - @$dm->arr_item_diskon[ $item ][ $paketid ];
		$subtotal_diskon = ( $harga * $kuantitas ) - @$dm->arr_item_diskon[ $itemseq ][ $paketid ];
		@$subtotal_campaign += $subtotal_diskon;
		
		$shortcut[$paketid]["item"][]=$item;
		$shortcut[$paketid]["kuantitas"][]=$kuantitas;
			
		$arr_data["#kelas#"] = $counter % 2 ==0 ? 1 : 2; 
		$arr_data["#item#"] = $item; 
		$arr_data["#item_seq#"] = $itemseq; 
		$arr_data["#order_id#"] = $order_id; 
		$arr_data["#paketid#"] = $paketid; 
		$arr_data["#itemdesc#"] = $dm->arr_item_nama[ $item ]; 
		$arr_data["#harga#"] = main::number_format_dec( $harga ); 
		
		 if( $item_stok[ $item ][ $gudang ] < 0 )	$item_stok_habis = true;		
		$arr_data["#stok#"] = $item_stok[ $item ][$gudang] < 0 ? 0 : $item_stok[ $item ][$gudang]; 
		$arr_data["#kuantitas#"] = $kuantitas; 
		$arr_data["#gudang#"] = $gudang; 
		$arr_data["#display-gudang-non-lokal#"] = trim(strtoupper($gudang)) != trim(strtoupper($gudang_asal)) ? "block" : "none" ; 
		$arr_data["#diskon#"] = main::number_format_dec( @$dm->arr_item_diskon[ $itemseq ][ $paketid ] ); 
		$arr_data["#rewardcn#"] = main::number_format_dec( @$dm->arr_item_cn[ $itemseq ][ $paketid ] ); 
		$arr_data["#subtotal#"] = main::number_format_dec( $harga * $kuantitas ) . ( $item_stok[ $item ][$gudang] < 0 ? "<sup class=\"peringatan\"><br />Mohon ubah kuantitas sesuai ketersediaan stok</sup>" : "" );
		$arr_data["#subtotal_diskon#"] = main::number_format_dec( $subtotal_diskon ); 
		$arr_data["#display-reward-non-diskon#"] = @$dm->arr_item_reward_non_diskon[ $itemseq ][ $paketid ] != "" ? "line-height:27px" : "display:none"; 
		$arr_data["#reward-non-diskon#"] = strtoupper( @$dm->arr_item_reward_non_diskon[ $itemseq ][ $paketid ] ); 
		$arr_data["#paket#"] = "<strong><a href=\"paket-detail.php?paketid=". $paketid ."\" style=\"color:blue;\">" . $paketid . "</a></strong> - " . $dm->arr_keterangan_paket_parameter[ $paketid ]; 
		
		$arr_data["#data-tambahan-diskon#"] = ""; 
		$arr_data["#diskonid#"] = $itemseq; 
		$arr_data["#disabled#"] = $item_stok[ $item ][$gudang] < 0 ? "disabled" : ""; 
		
		// info tambahan diskon yg sudah diterapkan
		$string_tambahan_diskon_diterapkan = $item_seq_asal = "";
		$item_seq_exists_di_order_diskon_item = false;
		if( is_array($dm->arr_item_diterapkan_tambahan_diskon) ){
			foreach( $dm->arr_item_diterapkan_tambahan_diskon as $_diskon_id_diskon_id => $arr_data_item_seq ){
				if( array_key_exists( $itemseq, $arr_data_item_seq ) ){
					$string_tambahan_diskon_diterapkan .= "&raquo; ". $arr_data_item_seq[ $itemseq ]["diskon"] . "<br />";
					$kuantitas_diskon_item = $arr_data_item_seq[ $itemseq ]["kuantitas_diskon_item"];
					$item_seq_asal = $arr_data_item_seq[ $itemseq ]["item_seq_asal"];
					
					if( !$item_seq_exists_di_order_diskon_item && @$_REQUEST["diskonid"] != "" )
						$item_seq_exists_di_order_diskon_item =  true;				
				}
			}
		}
		
		if( $diskon_id == @$_REQUEST["diskonid"] ){
			$arr_data["#checked-cb#"] = "checked";
			$arr_data["#diskon-diterapkan#"] = "diskon-diterapkan";
			$arr_data["#display-kuantitas-diskon-item#"] = "block";
			$arr_data["#kuantitas_diskon_item#"] = $kuantitas_diskon_item;		

		}else {
			//if( $order["diskon_id"] != "" ) continue; // tidak bisa double tambahan diskon
			$arr_data["#checked-cb#"] = "";
			$arr_data["#diskon-diterapkan#"] = "";
			$arr_data["#display-kuantitas-diskon-item#"] = "none";
			$arr_data["#kuantitas_diskon_item#"] = $item_seq_asal != "" || $string_tambahan_diskon_diterapkan != ""  ? $kuantitas_diskon_item : 1;
		
		}
		$arr_data["#readonly-kuantitas-diskon-item#"] = $item_seq_asal != "" || $item_seq_exists_di_order_diskon_item ? "readonly" : "";
		
		$arr_data["#display-tambahan-diskon-diterapkan#"] = $string_tambahan_diskon_diterapkan != "" ? "block" : "none";
		$arr_data["#tambahan-diskon-diterapkan#"] = $string_tambahan_diskon_diterapkan != "" ? "Diskon tambahan diterapkan untuk item ini :<br />" . $string_tambahan_diskon_diterapkan . "<br />" : "";
		
		$item_paket .= str_replace( array_keys( $arr_data ), array_values( $arr_data ),  $template);
		$counter++;
	}
	@$data_order .=  $item_paket  . "<h3> Total Order Campaign :: Rp" . main::number_format_dec($subtotal_campaign) . "</h3>";
}else  $data_order .= "Tidak ada item campaign!";

$item_free = "";
$counter = 1;
if( is_array( $dm->arr_free_item_paket ) && count( array_keys($dm->arr_free_item_paket) ) > 0  ){
	$data_order .=  "<h3 class=\"sub-judul\">:: Daftar Free Item ::</h3>";
	$template = file_get_contents("template/item-free.html");
	
	foreach( $dm->arr_free_item_paket as $paketid => $arr_item_kuantitas ){
		foreach( $arr_item_kuantitas as $item => $kuantitas ){
			$arr_data["#kelas#"] = $counter % 2 ==0 ? 1 : 2; 
			$arr_data["#item#"] = $item; 
			$arr_data["#itemdesc#"] = $dm->arr_item_nama[ $item ]; 
			
			 if( $item_stok[ $item ][ $gudang ] < 0 )	$item_stok_habis = true;		
			$arr_data["#stok#"] = $item_stok[ $item ][$gudang] < 0 ? 0 : $item_stok[ $item ][$gudang]; 
			$arr_data["#kuantitas#"] = $kuantitas; 
			$gudang_free_item =array_keys($dm->arr_free_item_paket_gudang[$paketid][$item]);

			$arr_data["#gudang#"] = $gudang_free_item[0]; 
			$arr_data["#display-gudang-non-lokal#"] = trim(strtoupper($gudang_free_item[0])) != trim(strtoupper($_SESSION["cabang"])) ? "block" : "block" ; 
			$arr_data["#keterangan_kuantitas_tidak_tersedia#"] = $item_stok[ $item ][$gudang] < 0 ? "<sup class=\"peringatan\">Kuantitas free item akan disesuaikan sesuai ketersediaan stok</sup><br />" : "" ; 
			$arr_data["#paket#"] = "<strong><a href=\"paket-detail.php?paketid=". $paketid ."\" style=\"color:blue;\">" . $paketid . "</a></strong>"; 
			$item_free .= str_replace( array_keys( $arr_data ), array_values( $arr_data ),  $template);
			$counter++;
		}
	}
}

// cek data diskon Front margin / DC charge
include "front_margin_dc_charge.php";

$data_order .=  $item_free  . "<br /> <div class=\"total-harga\" id=\"total-order-bawah\"><h3>Total Order<br />Rp" .  main::number_format_dec($subtotal_noncampaign + $subtotal_campaign + $total_diskon);
$data_order .=  "<br />Total Diskon Campaign<br />Rp" . main::number_format_dec(  $total_diskon );
if( $total_cn > 0 ){
	$data_order .=  '<br /><a href="javascript:void(0)" onclick="log_cn(\''. $paketid .'\',\''. @$_SESSION["kode_dealer"] .'\')" style="color:blue"><span class="tot_cn">* Total Reward CN *</span></a><br />Rp' . main::number_format_dec(  $total_cn );
}
if( $diskon_front_margin_dc_charge > 0 ){
	$data_order .=  "<br />Total Diskon Tambahan<br />Rp" . main::number_format_dec(  $diskon_front_margin_dc_charge );
	$tambahan_script_front_margin_dc_charge = "document.getElementById('kontainer-total-diskon-tambahan').style.display='block'; document.getElementById('total-diskon-tambahan').innerHTML = '". main::number_format_dec(  $diskon_front_margin_dc_charge ) ."';";
}
$total_order_net = $subtotal_noncampaign + $subtotal_campaign - $diskon_front_margin_dc_charge;
$data_order .=  "<br /> Total Order Net<br />Rp" . main::number_format_dec($subtotal_noncampaign + $subtotal_campaign - $diskon_front_margin_dc_charge - ((($total_order_net-$total_cn)>0) ? $total_cn : 0) ) . "</h3></div>";

// cek overlimit kredit
$s_script_overlimit = "";
$overlimit = order::check_overlimit( @$_SESSION["kode_dealer"], ( $subtotal_noncampaign + $subtotal_campaign ) ) ;
if( $overlimit["is_overlimit"] ) 
	$s_script_overlimit = "
		try{
			document.getElementById('overlimit-note').style.display = 'block';
			document.getElementById('limit-kredit').innerHTML = '". main::number_format_dec( $overlimit["limit_kredit"] ) ."';
			document.getElementById('piutang+order').innerHTML = '". main::number_format_dec( $overlimit["piutang_plus_order_baru"] ) ."';
		}catch(e){}
		";

$script = "
	try{
		document.getElementById('total-order').innerHTML = '". main::number_format_dec($subtotal_noncampaign + $subtotal_campaign + $total_diskon) ."';
		document.getElementById('total-diskon-campaign').innerHTML = '". main::number_format_dec($total_diskon) ."';
		document.getElementById('total-reward-cn').innerHTML = '". main::number_format_dec($total_cn) ."';
		document.getElementById('total-order-net').innerHTML = '". main::number_format_dec($subtotal_noncampaign + $subtotal_campaign - $diskon_front_margin_dc_charge - ((($total_order_net-$total_cn)>0) ? $total_cn : 0) ) ."';
		". $s_script_overlimit . $tambahan_script_front_margin_dc_charge ."
	}catch(e){}
";

// update shortcut paket
if( isset($shortcut) && count($shortcut) > 0 )
foreach( $shortcut as $paketid=>$item_kuantitas)
	$script .= "
		try{
			document.getElementById('shortcut-paket-". $paketid ."').innerHTML='". count( $item_kuantitas["item"] ) ." item - ". array_sum( $item_kuantitas["kuantitas"] ) ." unit';
		}catch(e){}";

if( is_array( $dm->arr_keterangan_paket ) ){
	$data_order .=  "Keterangan Campaign :: </strong><br />";
	foreach( $dm->arr_keterangan_paket as $paket => $keterangan )
	$data_order .=  "<strong><a href=\"paket-detail.php?paketid=". $paket ."\" style=\"color:blue;\">" . $paket . "</a></strong> - " . $keterangan . "<br />";
	if( $total_cn > 0 )
		$data_order .= "* <small><b>Jika Nilai Reward CN lebih besar dari total maka akan di masukkan di saldo CN, jika tidak maka otomatis memotong nilai Invoice saat ini.</b></small><br /><a href='javascript::void(0)' style='font-weight:bold' onclick='ShowModal()'>.:: Terapkan CN Sekarang ::.</a>";
}

$data_order .=  ($data_dealer["keterangan_order"] != "" ? "<h3 style=\"margin-bottom:1px\">Keterangan Tambahan</h3>" . $data_dealer["keterangan_order"] : "");
$data_order .= "<input type=\"hidden\" name=\"jumlah_item_campaign_noncampaign\" id=\"jumlah_item_campaign_noncampaign\" value=\"". (count($dm->arr_item_non_paket) + count($dm->arr_item_dengan_paket)) ."\" />";
	
SkipDM:

$arr_url_par = array();
foreach( $_REQUEST as $key=>$value )	
	if( $key != "item" ) $arr_url_par[ $key ] = $value;
$arr_rpl_cari_item["#url#"] = $page . "?" . http_build_query($arr_url_par);
$arr_rpl_cari_item["#item#"] = @$_REQUEST["item"];
$data_order = str_replace( array_keys( $arr_rpl_cari_item ), array_values( $arr_rpl_cari_item ), file_get_contents("template/transaksi-2-cari_item.html") ) . "<div style=\"float:left; width:100%\">" . $data_order . "</div>";	

?>