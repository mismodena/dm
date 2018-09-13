<?
$arr_dealer_modern = "'Y','Y01','Y02','Y1','Y11','Y12'";
$arr_dealer_pameran = "'WG', 'PR', 'KK'";
$arr_dealer_project = "'R','B'";
$arr_dealer_project_ecommerce = "'SH'";
$arr_dealer_professional = "'PROFESSIONAL'";

$arr_dealer_wajib_project = "'RX01A11021B0'";
$arr_dealer_wajib_professional = "'KJ27A11029B0'";

if($_POST["sc"]=="cl"){ //customer list
	$arr_grup_dealer = array(
								0 => array("'B'", "'C'", "'M'")
							);	
	
	$arr_sales_pengecualian = array("MBSD40", "MBSD40A");
			
	$kirim = @$_POST["kirim"] ? $_POST["kirim"] : 0;
	$pengajuan_diskon = isset( $_POST["pengajuan_diskon"] ) ? " and pengajuan_diskon = '". $_POST["pengajuan_diskon"] ."' " : "";
	
	if(@$_POST["kode_sales"] != ""){
		// cek grup id sales dealer di arcus
		$key_grup_dealer = get_key_grup_dealer
							(
								$arr_grup_dealer, 
								get_sales_grup_dealer($_POST["kode_sales"])
							);
	}
Mulai_Kueri_Dealer:	
$kolom_dealer = $select_dealer = "";
if( $page != "transaksi.php" ){
	
	$arr_halaman_transaksi = array( 
										"diskon-pengajuan.php", "diskon-pengajuan-pilihdiskon.php", "diskon-pengajuan-pilihitemfree.php", "diskon-pengajuan-pilihitemorder.php", 
										"diskon-approval.php",
										"transaksi-3.php",
										"histori-detail.php" 
										);
	
	if( in_array( $page, $arr_halaman_transaksi ) || @$_REQUEST["order_id"] != "" ){

		$select_dealer = " 						 
						 inner join [order] c on c.dealer_id = a.idcust ". ( @$_REQUEST["order_id"] != "" ? " and c.order_id = '" . main::formatting_query_string( $_REQUEST["order_id"] ) . "'" : "" ) ."
						 left outer join [user] e on (c.user_id = e.kode_sales or c.user_id = e.user_id )	
						 left outer join [user] f on e.bm = f.kode_sales
						 left outer join email_cabang g on c.gudang = g.cabang
						 ";
		
	}else{
		
		// untuk halaman drafting order		
		$select_dealer = " 						 
						 inner join (select max(tanggal) tanggal, user_id, dealer_id from [order] where kirim = '". $kirim ."' ". $pengajuan_diskon ." group by user_id, dealer_id) d on a.idcust = d.dealer_id /*and d.user_id = b.codeslsp */
						 inner join [order] c on c.dealer_id = d.dealer_id and c.tanggal = d.tanggal and c.user_id = d.user_id ". ( @$_REQUEST["order_id"] != "" ? " and c.order_id = '" . main::formatting_query_string( $_REQUEST["order_id"] ) . "'" : "" ) ."
						 left outer join [user] e on (c.user_id = e.kode_sales or c.user_id = e.user_id )	
						 left outer join [user] f on e.bm = f.kode_sales
						 left outer join email_cabang g on c.gudang = g.cabang
						 ";
						 
	}
	
	$kolom_dealer = " , e.cabang, e.user_id, e.kode_sales, e.email, e.nama_lengkap, c.order_id, c.keterangan_order, f.user_id user_id_bm, f.kode_sales kode_sales_bm, f.email email_bm, f.nama_lengkap nama_lengkap_bm, c.pengajuan_diskon,
						c.nama_kirim, c.alamat_kirim, c.kota_kirim, c.propinsi_kirim, c.telp_kirim, c.hp_kirim, c.nama_tagih, c.alamat_tagih, c.kota_tagih, c.propinsi_tagih, c.telp_tagih, c.hp_tagih, c.po_referensi, c.gudang, 
						g.email_sales, g.email_finance, g.email_warehouse, e.email_finance email_finance_untuk_overlimit ";
	
}

	$sql_template="select ltrim(rtrim(idcust)) idcust, ltrim(rtrim(namecust)) namecust, '['+ltrim(rtrim(textsnam))+'] '+ltrim(rtrim(textstre1))+' '+ltrim(rtrim(textstre2))+' '+ltrim(rtrim(textstre3)) addr, namecity, custtype, priclist, idgrp, upper(a.email1) email1,
		 ".$GLOBALS["database_accpac"].".dbo.ufnDiskonDealer(a.IDCUST) disc ". $kolom_dealer ."
		from 
		".$GLOBALS["database_accpac"]."..ARCUS a left join ".$GLOBALS["database_accpac"]."..ARSAP b on b.CODESLSP = a.CODESLSP1 		
		". $select_dealer ."
		
		where a.SWACTV = 1 ";
		
		$sql = $sql_template;
		
		//$sql .= "and ( LEFT(idcust,1) = 'D' or left(idcust, 2) = 'PR' ) and LEN(idcust) > 4  ";
		
	$ap=array("namecust"=>"namecust");
	foreach($ap as $col=>$par){
		if(@$_POST[$par]!="")$sql.=" and $col like '%".main::formatting_query_string(@$_POST[$par])."%' ";
	}
	
	if(@$key_grup_dealer >= 0 && !in_array(trim(@$_POST["kode_sales"]), $arr_sales_pengecualian)){
		//$sql.=" and a.codeterr in (201, 202, 216) "; //" and a.idgrp in (". implode(",", $arr_grup_dealer[$key_grup_dealer]) .") ";
		if( @$_POST["kode_sales"] != "" )
			$parameter_codeterr = " and a.codeterr in (201, 202, 216, 221) ";
	}else{
		if( @$_POST["kode_sales"] != "" )	//$sql.=" and b.codeslsp = '".main::formatting_query_string(@$_POST["kode_sales"])."' ";
			$parameter_kodesales =" and b.codeslsp = '".main::formatting_query_string(@$_POST["kode_sales"])."' ";
	}
	$tambahan_dealer_project_professional = " or left(idcust, 1) in ($arr_dealer_project) or left(idcust, 2) in ($arr_dealer_project_ecommerce) or upper(ltrim(rtrim(email1))) in ($arr_dealer_professional) ";
	$sql .= "and ( ( a.idgrp in (". $arr_dealer_modern .") ) or ( LEFT(idcust,1) = 'D' ". @$parameter_codeterr ." ) or left(idcust, 2) in ($arr_dealer_pameran)  $tambahan_dealer_project_professional ) and LEN(idcust) > 4  " . @$parameter_kodesales ;
	
	if( @$_POST["kode_dealer"] != "" )
		$sql.=" and a.idcust = '".main::formatting_query_string(@$_POST["kode_dealer"])."' ";
	
	if( @$_POST["cari_dealer"] != "" )
		$sql.=" and ( a.idcust like '".main::formatting_query_string(@$_POST["cari_dealer"])."%' 
					or a.namecust like '%".main::formatting_query_string(@$_POST["cari_dealer"])."%' 
					or ( a.textsnam like '%".main::formatting_query_string(@$_POST["cari_dealer"])."%' and  a.idgrp in (". $arr_dealer_modern .") ) ) ";
					
	
	$sql_union = $sql_template . " and  idcust in ($arr_dealer_wajib_project, $arr_dealer_wajib_professional) " . ( @$_POST["kode_dealer"] != "" ? " and a.idcust = '".main::formatting_query_string(@$_POST["kode_dealer"])."' " : "" );
	$sql .= " union all " . $sql_union;
	
	
}

//echo "<!--";
//echo $sql;
//print_r($_SESSION);
//echo " -->";
?>