<?

// variabel yg bisa digunakan .. lihat di lib/cls_diskon_persetujuan.php -> __construct()
// fungsi untuk meng-override mekanisme penentuan diskon awal, atau menggunakan mekanisme yg sama sekali beda dengan diskon awal
// pastikan untuk reset $posisi, $nik, $email, sebelum memulai override atau mekanisme baru

$rs_nominal_diskon = self::nilai_tambahan_diskon( $order_id, $nominal_order["nominal_order"], array("a.diskon_id" => array( "=", $diskon_id )) );
$nominal_persen_diskon_tambahan = 0;

$this->reset_variabel();

while( $nominal_diskon = sqlsrv_fetch_array( $rs_nominal_diskon ) )
	$nominal_persen_diskon_tambahan += $nominal_diskon["total_nilai_persen_diskon_tambahan"];

$nominal_persen_diskon_tambahan = $nominal_persen_diskon_tambahan / sqlsrv_num_rows( $rs_nominal_diskon );

if( $nominal_persen_diskon_tambahan <= 15 )		
	$this->set_variabel_detail(  1 );

elseif( $nominal_persen_diskon_tambahan > 15 && $nominal_persen_diskon_tambahan <= 30 )
	$this->set_variabel_detail(  8 );

elseif( $nominal_persen_diskon_tambahan > 30 && $nominal_persen_diskon_tambahan <= 50 )
	$this->set_variabel_detail(  3 );
	
else
	$this->set_variabel_detail(  array(3, 7) );
?>