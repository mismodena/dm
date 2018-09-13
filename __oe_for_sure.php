<?

set_time_limit(60);

$_SESSION["sales_id"] = "ADMIN";

include "lib/mainclass.php";

error_reporting(E_ALL);

$arr_parameter_1["a.order_id"] = array( "=", "'" . main::formatting_query_string( $_REQUEST["order_id"] ) . "'");
$static_function_cek_order_accpac = "daftar_order";
if( isset( $_REQUEST["order_id_split"] ) ){
	unset( $arr_parameter_1 );
	$arr_parameter_1[" dbo.sambung_order_id(a.order_id, a.order_id_split, '-')"] = array( "=", "'" . main::formatting_query_string( $_REQUEST["order_id_split"] ) . "'");
	$static_function_cek_order_accpac = "daftar_order_split";
}	

// cek order apakah sudah ada di accpac ato belum, klo sudah ada exit script
$rs_cek_order = order::$static_function_cek_order_accpac( $arr_parameter_1 );
$data_order = sqlsrv_fetch_array( $rs_cek_order );

if( $data_order["ordnumber"] != "" )	die("sudah ada");

$_POST["sc"] = "cl";
include_once "dealer.php";
$rs_dealer = sql::execute( $sql . " and c.order_id = '". main::formatting_query_string( $data_order["order_id"] ) ."' " );
$sql = "select ltrim(rtrim(idcust)) idcust, ltrim(rtrim(namecust)) namecust, '['+ltrim(rtrim(textsnam))+'] '+ltrim(rtrim(textstre1))+' '+ltrim(rtrim(textstre2))+' '+ltrim(rtrim(textstre3)) addr, namecity, custtype, priclist, idgrp, upper(a.email1) email1,
		 sgtdat.dbo.ufnDiskonDealer(a.IDCUST) disc  , e.cabang, e.user_id, e.kode_sales, e.email, e.nama_lengkap, c.order_id, c.keterangan_order, f.user_id user_id_bm, f.kode_sales kode_sales_bm, f.email email_bm, f.nama_lengkap nama_lengkap_bm, c.pengajuan_diskon,
						c.nama_kirim, c.alamat_kirim, c.kota_kirim, c.propinsi_kirim, c.telp_kirim, c.hp_kirim, c.nama_tagih, c.alamat_tagih, c.kota_tagih, c.propinsi_tagih, c.telp_tagih, c.hp_tagih, c.po_referensi, c.gudang, 
						g.email_sales, g.email_finance, g.email_warehouse, e.email_finance email_finance_untuk_overlimit 
		from 
		sgtdat..ARCUS a left join sgtdat..ARSAP b on b.CODESLSP = a.CODESLSP1 		
		 						 
						 inner join [order] c on c.dealer_id = a.idcust  and c.order_id = '". main::formatting_query_string( $_REQUEST["order_id"] ) ."'
						 left outer join [user] e on (c.user_id = e.kode_sales or c.user_id = e.user_id )	
						 left outer join [user] f on e.bm = f.kode_sales
						 left outer join email_cabang g on c.gudang = g.cabang";

$rs_dealer = sql::execute($sql);
$data_dealer = sqlsrv_fetch_array( $rs_dealer ) or die("<script>alert('Gagal mendapatkan data dealer!');location.href='transaksi.php';</script>");
include_once "transaksi-3.php.command.ext.php";

$nominal_order = order::nominal_order( $data_order["dealer_id"], array( "b.order_id" => array("=",  "'". $data_order["order_id"] ."'" ) ) );

$status_order = 0;
$overlimit = order::check_overlimit( $data_order["dealer_id"], $nominal_order["nominal_order_net"] ) ;
if( $overlimit["is_overlimit"] ) $status_order = 1;

$data_input_accpac = array("idcust" => $data_order["dealer_id"], "order_id" => $data_order["order_id"] );

$sql = "exec dbo.usp_benerin_commit_pak_mus '". main::formatting_query_string( $data_order["order_id"] ) ."';";
sql::execute( $sql );
		
// cek stok
$arr_stok = order::cek_cek_stok_item_order( $data_order["order_id"] );
$arr_data_stok = array();
while( $stok = sqlsrv_fetch_array( $arr_stok ) ){
	$arr_data_stok[ $stok["item_id"] ][ $stok["gudang"] ] = $stok["kuantitas"];
	
	if( !isset( $_REQUEST["order_id_split"] ) )
		if( $stok["kuantitas"] < 0 ) die("stok kosong" . $stok["item_id"] . ", Gudang : " . $stok["gudang"]);
	
}

if( isset( $_REQUEST["order_id_split"] ) ){
	$rs_order_item = sql::execute("select b.gudang, a.item_id from order_split b, order_item_split a where 
		a.order_id = b.order_id and a.user_id = b.user_id and a.order_id_split = b.order_id_split and
		dbo.sambung_order_id(a.order_id, a.order_id_split, '-') = '". main::formatting_query_string( $_REQUEST["order_id_split"] ) ."' ");
	while( $data_order_item = sqlsrv_fetch_array($rs_order_item) )
		if( $arr_data_stok[ $data_order_item["item_id"] ][ $data_order_item["gudang"] ] < 0 )	die("stok kosong" . $data_order_item["item_id"] . ", Gudang : " . $data_order_item["gudang"] . ".." . $arr_data_stok[ $data_order_item["item_id"] ][ $data_order_item["gudang"] ]);
		
}

$order_split = order::kirim_data_ke_accpac( $data_input_accpac, $nominal_order, $status_order );
	

?>