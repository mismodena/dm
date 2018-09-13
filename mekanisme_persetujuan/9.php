<?

$nominal_diskon_baru = self::nominal_order("", array( "b.order_id" => array("=",  "'". $order_id ."'" ), "/*a.diskon_id_all*/" => array("/*=*/", "'". $diskon_id ."' in (select * from dbo.ufn_split_string(a.diskon_id_all, ',')) ") ));

$rs_nominal_diskon = self::nilai_tambahan_diskon( $order_id, $nominal_diskon_baru["nominal_order"], array("a.diskon_id" => array( "=", $diskon_id )) );
$total_nilai_rupiah_diskon_tambahan = 0;

$this->reset_variabel();

while( $nominal_diskon = sqlsrv_fetch_array( $rs_nominal_diskon ) )
	$total_nilai_rupiah_diskon_tambahan += $nominal_diskon["total_nilai_rupiah_diskon_tambahan"];

$total_nilai_rupiah_diskon_tambahan = 100 * $total_nilai_rupiah_diskon_tambahan / $nominal_diskon_baru["nominal_order"];

if( $total_nilai_rupiah_diskon_tambahan <= 2  )
	$this->set_variabel_detail(  5 );

elseif( $total_nilai_rupiah_diskon_tambahan > 2 && $total_nilai_rupiah_diskon_tambahan <= 3 )
	$this->set_variabel_detail(  8 );
	
else
	$this->set_variabel_detail(  3 );
	
?>