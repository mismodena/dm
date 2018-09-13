<?

$rs_nominal_diskon = self::nilai_tambahan_diskon( $order_id, $nominal_order["nominal_order"], array("a.diskon_id" => array( "=", $diskon_id )) );
$total_nilai_rupiah_diskon_tambahan = 0;

while( $nominal_diskon = sqlsrv_fetch_array( $rs_nominal_diskon ) )
	$total_nilai_rupiah_diskon_tambahan += $nominal_diskon["total_nilai_rupiah_diskon_tambahan"];

$this->reset_variabel();

if( $total_nilai_rupiah_diskon_tambahan >= 2500000 )
	$this->set_variabel_detail(  array(3, 7) );
	//$this->set_variabel_detail(  3 );

else
	$this->set_variabel_detail(  3 );


?>