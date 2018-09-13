<?

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

	if( @$index_area_manager == "" ) $index_area_manager = 14;
	
	$this->set_variabel_detail(  $index_area_manager );

?>