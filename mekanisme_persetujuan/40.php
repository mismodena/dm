<?

// cari kuantitas order item di dalam $order_id, dicari dari daftar order_diskon_item dulu.. klo tidak ada baru dari daftar order_item (berarti diskon untuk satu faktur)
unset( $arr_daftar_order_item, $arr_daftar_order_item_harga );
$apakah_dari_order_item_diskon = false;
$rs_daftar_order_item_diskon = self::daftar_order_diskon_item( array("a.order_id" => array("=" , "'". main::formatting_query_string( $order_id ) ."'" ), "a.diskon_id" => array("=", "'". $diskon_id ."'" ) ) );
if( sqlsrv_num_rows($rs_daftar_order_item_diskon) > 0 ){

	while( $daftar_order_item_diskon = sqlsrv_fetch_array( $rs_daftar_order_item_diskon ) ){
		@$arr_daftar_order_item[ $daftar_order_item_diskon["item_id"] ] += $daftar_order_item_diskon["kuantitas"];
		@$arr_daftar_order_item_harga[ $daftar_order_item_diskon["item_id"] ] = $daftar_order_item_diskon["harga"] > 0 ? $daftar_order_item_diskon["harga"] : @$arr_daftar_order_item_harga[ $daftar_order_item_diskon["item_id"] ];
	}
	
	$apakah_dari_order_item_diskon = true;
	
}else{
	
	$rs_daftar_order_item = sql::execute(" select item_id itemno, kuantitas,  harga from order_item  where order_id='". main::formatting_query_string( $order_id ) ."' ");
	while( $daftar_order_item = sqlsrv_fetch_array( $rs_daftar_order_item ) ){
		@$arr_daftar_order_item[ $daftar_order_item["itemno"] ] += $daftar_order_item["kuantitas"];
		@$arr_daftar_order_item_harga[ $daftar_order_item["itemno"] ] = $daftar_order_item["harga"] > 0 ? $daftar_order_item["harga"] : @$arr_daftar_order_item_harga[ $daftar_order_item["itemno"] ];
	}
	
}

// cari harga price list item di dalam order $order_id
foreach( $arr_daftar_order_item as $itemno => $kuantitas )
	@$s_parameter .= "'". main::formatting_query_string( $itemno ) ."',";
$sql_price_list = self::sql_item_info( 0 ) . " and b.itemno in ( ". substr($s_parameter, 0, strlen($s_parameter)-1) ." ) " ;
$rs_price_list = sql::execute( $sql_price_list );
while( $price_list = sqlsrv_fetch_array( $rs_price_list ) )
	$arr_price_list[ $price_list["itemno"] ] = $price_list["unitprice"];

// reset harga price list menjadi net price lagi => request CM per 9/10 untuk mengubah basis perhitungan harga dari price list ke net
foreach( $arr_price_list as $itemno => $harga_pricelist )
	$arr_price_list[ $itemno ] = @$arr_daftar_order_item_harga[ $itemno ];

// cek nominal order dengan perhitungan dari harga price list
$nominal_order_dengan_harga_pricelist = 0;
foreach( $arr_daftar_order_item as $itemno => $kuantitas )
	$nominal_order_dengan_harga_pricelist += ( $kuantitas * $arr_price_list[ $itemno ] );

unset( $arr_parameter );
$arr_parameter["a.diskon_id"] = array( "=", $diskon_id );
if( $apakah_dari_order_item_diskon )	$arr_parameter["c.item_id"] = array( " in ", "( ".  substr($s_parameter, 0, strlen($s_parameter)-1) ." )" );
$rs_nominal_diskon = self::nilai_tambahan_diskon( $order_id, $nominal_order["nominal_order"], $arr_parameter );

$total_nilai_rupiah_diskon_sebelum_diskon_tambahan = $total_nilai_rupiah_diskon_tambahan = 0;

$this->reset_variabel();

while( $nominal_diskon = sqlsrv_fetch_array( $rs_nominal_diskon ) ){
	$total_nilai_rupiah_diskon_tambahan += $nominal_diskon["total_nilai_rupiah_diskon_tambahan"];
	$total_nilai_rupiah_diskon_sebelum_diskon_tambahan += ( $arr_price_list[ $nominal_diskon["item_id"] ] - $nominal_diskon["harga_per_item_setelah_diskon_campaign"] ) * $nominal_diskon["kuantitas_diskon_item"];
}

$persentase_diskon_tambahan_dari_pricelist = 100 * ( $total_nilai_rupiah_diskon_sebelum_diskon_tambahan + $total_nilai_rupiah_diskon_tambahan ) / $nominal_order_dengan_harga_pricelist;

echo "<!--";
echo $persentase_diskon_tambahan_dari_pricelist;
echo "-->";

if( $persentase_diskon_tambahan_dari_pricelist <= 30  )
	$this->set_variabel_detail(  1 );

elseif( $persentase_diskon_tambahan_dari_pricelist > 30 && $persentase_diskon_tambahan_dari_pricelist <= 50 )
	$this->set_variabel_detail(  3 );
	
else
	$this->set_variabel_detail(  array(3, 7) );
	//$this->set_variabel_detail(  7 );
	
?>