<?
foreach( $arr_diskon_display_tidak_diperbolehkan as $item_id =>$arr_item_tanggal_invnumber ){
	list( $item_nama, $tanggal, $invnumber ) = $arr_item_tanggal_invnumber;
	$arr_item_nama[ $item_nama ] = array( $tanggal, $invnumber );
	$arr_parameter_item_id[] = "'". $item_id ."'";
}

$order_id = $_REQUEST["order_id"];
$dm = new order( $order_id, $_REQUEST["diskonid"],  $arr_parameter_item_id);
include_once "transaksi-2-order.php";

foreach( $arr_item_nama as $item_nama => $arr_tanggal_invnumber ){
	list( $tanggal, $invnumber ) = $arr_tanggal_invnumber;
	$data_order = str_replace( $item_nama, $item_nama . "<br />" . $invnumber . " (". $tanggal .")<br /> ", $data_order );
}
?>