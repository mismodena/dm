<?

$sql = "select b.*, c.IDCUST, c.IDNATACCT, c.NAMECUST from fpp..ms_tradingTerm a, fpp..ms_tradingTermDetail b, ". $GLOBALS["database_accpac"] ."..ARCUS c 
				where a.termNo = b.termID and (a.idCust = c.IDCUST or a.idCust = c.IDNATACCT) and
				b.tradCode = 'B31111' and GETDATE() between a.periodStart and a.periodEnd and c.IDCUST = '". main::formatting_query_string( $dealer_id ) ."'";
$rs = sql::execute( $sql );

if( sqlsrv_num_rows( $rs ) > 0 ){
	$diskon_default = sqlsrv_fetch_array( $rs );
	$diskon["nilai_diskon"]  = $diskon_default["tradPercentage"];				

	// cari diskon dari price list
	if( $diskon_default["isPL"] == 1 ){ 
		$basis_harga_net = 1;
		$diskon_dealer = sqlsrv_fetch_array( sql::execute( "select ".$GLOBALS["database_accpac"].".dbo.ufnDiskonDealer('". main::formatting_query_string( $dealer_id ) ."') diskon" ) );
		$simulasi_pl = $basis_harga_net / (1-($diskon_dealer["diskon"]/100));
		$simulasi = $simulasi_pl * $diskon_default["tradPercentage"] / 100;
		$nilai_diskon_dari_net = 100 * ($basis_harga_net - $simulasi) / $basis_harga_net;
		$diskon["nilai_diskon"] = $nilai_diskon_dari_net;
	}
	
}

?>