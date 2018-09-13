<?
$diskon["nilai_diskon"] = $diskon["default_nilai"];
goto Skip_Perhitungan_Dibawah_ini;

$arr_harga_order_item = $arr_item_professional = $arr_item_nonprofessional = array();

// cek diskon per faktur atau diskon per item
$sql = "select 1 from order_diskon_item where order_id = '". main::formatting_query_string( $order_id ) ."' 
		and diskon_id = '". main::formatting_query_string( $diskon["diskon_id"] ) ."'";
$diskon_per_faktur_or_diskon_per_item = sql::execute( $sql );

$string_sql_join = " left outer ";
if( sqlsrv_num_rows( $diskon_per_faktur_or_diskon_per_item ) > 0 ) $string_sql_join = " inner ";

$sql = "select case when isnull(c.item_id, '') = '' then 0 else 1 end item_professional, b.item_seq item_order_dipilih, a.item_seq, a.item_id, a.harga, a.kuantitas, c.tambahan_net
				from [order] a1 
				inner join order_item a on a1.order_id = a.order_id and a1.user_id = a.user_id
				$string_sql_join join order_diskon_item b on a.order_id = b.order_id and a.user_id = b.user_id and a.item_seq = b.item_seq and b.diskon_id = '". main::formatting_query_string( $diskon["diskon_id"] ) ."'
				left outer join penambahan_net_dealer c on a1.dealer_id = c.dealer and a.item_id = c.item_id
				where a.order_id = '". main::formatting_query_string( $order_id ) ."' ";
$rs_item_order_diskon = sql::execute( $sql );

while( $item_order_diskon = sqlsrv_fetch_array( $rs_item_order_diskon ) ){
	
	$arr_harga_order_item[ $item_order_diskon["item_id"] ] = $item_order_diskon["harga"];
	@$arr_item_nonprofessional[ $item_order_diskon["item_id"] ] += $item_order_diskon["kuantitas"];
	
	if( $item_order_diskon["item_professional"] == 1 ){
		@$arr_item_professional[ $item_order_diskon["item_id"] ] += $item_order_diskon["kuantitas"];
		$arr_harga_order_item[ $item_order_diskon["item_id"] ] = $item_order_diskon["harga"] / ( (100 - $item_order_diskon["tambahan_net"]) / 100 );
	}
}

$total_order = 0;
foreach( ($arr_item_professional + $arr_item_nonprofessional) as $var_item_id => $var_kuantitas )
	$total_order += $arr_harga_order_item[ $var_item_id ] * $var_kuantitas;

$jumlah_kuantitas_order = array_sum( ($arr_item_professional + $arr_item_nonprofessional) );

if( $diskon["default_nilai"] <= 100 )
	$diskon["nilai_diskon"] = $total_order * ( ( $diskon["default_nilai"]) / 100 ) / $jumlah_kuantitas_order;
else
	$diskon["nilai_diskon"] = $jumlah_kuantitas_order * $diskon["default_nilai"];


goto Skip_Perhitungan_Dibawah_ini;

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

Skip_Perhitungan_Dibawah_ini:
?>