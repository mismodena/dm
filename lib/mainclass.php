<?

set_time_limit(120);

include "var.php";
include "cls_main.php";
include "sql.php";

include "cls_otorisasi.php";

include "cls_operator.php";
include "cls_dm_sql.php";
include "cls_dm.php";
include "cls_order.php";
include "cls_order_split.php";
include "cls_diskon.php";
include "cls_diskon_persetujuan.php";
include "cls_diskon_persetujuan_split.php";

$arr_page=preg_split("/\//", $_SERVER['SCRIPT_FILENAME']);
$page=$arr_page[count($arr_page)-1];
$arr_halaman_free_akses = array("login.php", "__oe_for_sure.php", "__email.php", "__email_bm.php", "logout.php", "simulasi.php", "simulasi-item.php", "__register_email.php", "__email_persetujuan.php","histori-detail-lk.php","index.php");
$arr_command_free_akses = array( "persetujuan_diskon", "kirim_order_daripersetujuan", "reset_simulasi", "kebetot" );

// filter user session
if( 
	@$_SESSION["sales_id"] == "" && 
	!in_array( $page, $arr_halaman_free_akses ) && 
	!in_array( @$_REQUEST["c"],  $arr_command_free_akses) 
) 
	header("location:login.php");

if( SINGLE_ACCESS ){
	if(
		@$_SESSION["sales_id"] != "" &&
		!in_array( $page, $arr_halaman_free_akses ) && 
		!in_array( @$_REQUEST["c"],  $arr_command_free_akses) &&
		!otorisasi::cek_sesi_login()
		)
		header("location:logout.php?c=kebetot");
}

// filter user menu
$rs_daftar_menu_user = otorisasi::daftar_akses_halaman( array("a.user_id" => array("=", "'". main::formatting_query_string( @$_SESSION["sales_id"] ) ."'") ) );
while( $daftar_menu_user = sqlsrv_fetch_array( $rs_daftar_menu_user ) )
	@$string_daftar_menu_user .= "<li><a href=\"". $daftar_menu_user["menu"] ."\">". $daftar_menu_user["nama_file"] ."</a></li>";
@$string_daftar_menu_user .= "<li><a href=\"logout.php\">". strtoupper( @$_SESSION["sales_id"] ) ." - Logout</a></li>";

// set diskon tambahan persetujuan default, untuk diskon tambahan yg belum ada persetujuannya.
/*$rs_persetujuan_diskon_default = sql::execute("select * from diskon");
while( $persetujuan_diskon_default = sqlsrv_fetch_array( $rs_persetujuan_diskon_default ) )
	$arr_moa[ $persetujuan_diskon_default["diskon_id"] ] = @$arr_moa[ $persetujuan_diskon_default["diskon_id"] ] != "" ? $arr_moa[ $persetujuan_diskon_default["diskon_id"] ] : array(1=>"1");*/

// include file fungsi
if( file_exists( $page . ".function.php" ) )
	include_once $page . ".function.php";

// filter hak akses
//$data_akses_pengguna = otorisasi::otorisasi_akses_pengguna( $page, @$_SESSION["sales_id"] );
//if( !$data_akses_pengguna[0] ) 	
//	header("location:terlarang.php");
//@$script .= $data_akses_pengguna[1];

// UNTUK di halaman dealer.php
function get_sales_grup_dealer($kode_sales){
	$return = array();
	$sql = "	select distinct idgrp from ".$GLOBALS["database_accpac"]."..ARCUS where 
				swactv='1' 
				and LEN(idcust) > 4 
				and codeslsp1='". main::formatting_query_string($kode_sales) ."'";
	$rs=sql::execute($sql, true);		
	while($grup = sqlsrv_fetch_array($rs))
		$return[] = trim($grup["idgrp"]);
	return $return;
}

function get_key_grup_dealer($arr_grup_dealer, $arr_sales_grup_dealer){
	$return = -1;
	foreach($arr_grup_dealer as $key=>$grup_dealer){
		foreach($arr_sales_grup_dealer as $sales_grup_dealer){
			if(in_array("'". $sales_grup_dealer ."'", $grup_dealer)){
				$return = $key;
				break;
			}
		}
	}
	return $return;
}


?>