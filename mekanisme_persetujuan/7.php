<?

// variabel yg bisa digunakan .. lihat di lib/cls_diskon_persetujuan.php -> __construct()
// fungsi untuk meng-override mekanisme penentuan diskon awal, atau menggunakan mekanisme yg sama sekali beda dengan diskon awal
// pastikan untuk reset $posisi, $nik, $email, sebelum memulai override atau mekanisme baru

// cek dealer modern atau tradisional
$dealer_grup = sqlsrv_fetch_array( sql::execute("select ltrim(rtrim(idgrp)) idgrp, upper(rtrim(ltrim(email1))) email1 from ". $GLOBALS["database_accpac"] ."..arcus a, [order] b where a.idcust = b.dealer_id and b.order_id = '". main::formatting_query_string( $order_id ) ."';") );
if( 
	in_array( $dealer_grup["idgrp"], explode( ",", str_replace( "'", "", $GLOBALS["arr_dealer_modern"] ) ) ) ||
	in_array( $dealer_grup["email1"],  explode( ",", str_replace( "'", "", $GLOBALS["arr_dealer_professional"] ) ) )
	
){ // dealer modern TT/Non-TT

	// dealer modern TT : tidak membutuhkan persetujuan
	if( in_array( $dealer_grup["idgrp"], array('Y','Y01','Y02') ) || in_array( $dealer_grup["email1"],  explode( ",", str_replace( "'", "", $GLOBALS["arr_dealer_professional"] ) ) ) ) {}
	
	else{
		$rs_nominal_diskon = self::nilai_tambahan_diskon( $order_id, $nominal_order["nominal_order"], array("a.diskon_id" => array( "=", $diskon_id )) );
		$nominal_persen_diskon_tambahan = 0;

		$this->reset_variabel();

		while( $nominal_diskon = sqlsrv_fetch_array( $rs_nominal_diskon ) )
			$nominal_persen_diskon_tambahan += $nominal_diskon["total_nilai_persen_diskon_tambahan"];

		$nominal_persen_diskon_tambahan = $nominal_persen_diskon_tambahan / sqlsrv_num_rows( $rs_nominal_diskon );
		
		if( $nominal_persen_diskon_tambahan <= 15 )		
			$this->set_variabel_detail(  1 );
		
		else
			$this->set_variabel_detail(  8 );
		
	}

}else{ // dealer tradisional 

	// cari area manager
	$sql = "select a.IDGRP, b.*,
				case 
					when b.branchareahead = '". $GLOBALS["arr_pic"][9]["nik"] ."' then 9
					else 10
				end arr_pic_index
				from ". $GLOBALS["database_accpac"] ."..ARGRO a inner join ". $GLOBALS["database_accpac"] ."..ARCUS c
				on a.IDGRP = c.IDGRP inner join [order] d on c.IDCUST = d.dealer_id
				left outer join FPP..ms_branch b on a.TEXTDESC = b.branchName 
				where b.branchName is not null and 
				d.order_id = '". main::formatting_query_string( $order_id ) ."'";

	$rs_grup_cabang_dealer = sql::execute( $sql );
	$grup_cabang_dealer = sqlsrv_fetch_array( $rs_grup_cabang_dealer );
	$index_area_manager = $grup_cabang_dealer["arr_pic_index"];

	$rs_nominal_diskon = self::nilai_tambahan_diskon( $order_id, $nominal_order["nominal_order"], array("a.diskon_id" => array( "=", $diskon_id )) );
	$nominal_persen_diskon_tambahan = 0;

	$this->reset_variabel();

	while( $nominal_diskon = sqlsrv_fetch_array( $rs_nominal_diskon ) )
		$nominal_persen_diskon_tambahan += $nominal_diskon["total_nilai_persen_diskon_tambahan"];

	$nominal_persen_diskon_tambahan = $nominal_persen_diskon_tambahan / sqlsrv_num_rows( $rs_nominal_diskon );

	if( $nominal_persen_diskon_tambahan <= 15 )
		$this->set_variabel_detail(  $index_area_manager );

	elseif( $nominal_persen_diskon_tambahan > 15 && $nominal_persen_diskon_tambahan <= 30 )
		$this->set_variabel_detail(  8 );

	elseif( $nominal_persen_diskon_tambahan > 30 && $nominal_persen_diskon_tambahan <= 50 )
		$this->set_variabel_detail(  3 );

	elseif( $nominal_persen_diskon_tambahan > 50 )
		$this->set_variabel_detail(  array(3, 7) );
	
}	
?>