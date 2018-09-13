<?

$rs_nominal_diskon = self::nilai_tambahan_diskon( $order_id, $nominal_order["nominal_order"], array("a.diskon_id" => array( "=", $diskon_id )) );
$total_nilai_rupiah_diskon_tambahan = 0;

$this->reset_variabel();

while( $nominal_diskon = sqlsrv_fetch_array( $rs_nominal_diskon ) )
	$total_nilai_rupiah_diskon_tambahan += $nominal_diskon["total_nilai_rupiah_diskon_tambahan"];

$total_nilai_rupiah_diskon_tambahan = 100 * $total_nilai_rupiah_diskon_tambahan / $nominal_order["nominal_order_gross"];

if( $total_nilai_rupiah_diskon_tambahan <= 2  )
	$this->set_variabel_detail(  13 );

elseif( $total_nilai_rupiah_diskon_tambahan > 2 && $total_nilai_rupiah_diskon_tambahan <= 3 )
	$this->set_variabel_detail(  8 );
	
else
	$this->set_variabel_detail(  3 );
	
?>