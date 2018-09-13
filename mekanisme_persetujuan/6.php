<?

$rs_nominal_diskon = self::nilai_tambahan_diskon( $order_id, $nominal_order["nominal_order"], array("a.diskon_id" => array( "=", $diskon_id )) );
$total_nilai_persen_diskon_tambahan = 0;

$this->reset_variabel();

while( $nominal_diskon = sqlsrv_fetch_array( $rs_nominal_diskon ) )
	$total_nilai_persen_diskon_tambahan += $nominal_diskon["total_nilai_persen_diskon_tambahan"];

$total_nilai_persen_diskon_tambahan = $total_nilai_persen_diskon_tambahan / sqlsrv_num_rows( $rs_nominal_diskon );

if( $total_nilai_persen_diskon_tambahan <= 3  )
	$this->set_variabel_detail(  1 );

else
	$this->set_variabel_detail(  3 );
	
?>