<?
//error_reporting(1); 
error_reporting(0); 

session_start();

$string="ptindomomulia";

// koneksi database sqlserver dbsindomo
$server="192.168.1.21";
$username="sa";
$password="ptim*328";
$database="dm";

$connectionInfo = array( "UID"=>$username,
                         "PWD"=>$password,
                         "Database"=>$database);

$conn = sqlsrv_connect($server, $connectionInfo) or die("gagal konek : ".print_r(sqlsrv_errors()));

@define("INCLUDE_PATH",__DIR__ . "/pear");

define("SINGLE_ACCESS", false);

//koneksi smtp email
define("EMAIL_DIAKTIFKAN", true);

define("SMTP_HOST","192.168.1.20");
define("SMTP_PORT","587");
define("SMTP_AUTH",true);
define("SMTP_USERNAME","support@modena.co.id");
define("SMTP_PASSWORD","sp_328_indomo");
/*
define("SMTP_HOST","192.168.1.5");
define("SMTP_PORT","587");
define("SMTP_AUTH",true);
define("SMTP_USERNAME","customersurvey@modena.co.id");
define("SMTP_PASSWORD","ptim*328");
*/
define("CRLF","\n");
define("EMAIL_TEMPLATE", "template/email.html");

// koneksi web servis
$web_servis_url="http://localhost/modena_accpac/trx/index.php";
$curl_connection_timeout = 5;
$curl_timeout = 10;
// web servis otentikasi
$ftp_address="202.158.114.230";
$ftp_username="modenaim";
$random=rand(0, 9999);

// data tujuan email
define("SUPPORT_EMAIL", "support@modena.co.id");

$arr_month=array(1=>"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

define("MEKANISME_PARAMETER", "mekanisme_parameter/");
define("MEKANISME_REWARD", "mekanisme_reward/");

$database_accpac = "sgtdat";
$database_mobilesales = $database;
define("__SERVER_DISKON_KHUSUS__", "fpp");

/* PERSETUJUAN DISKON */
$arr_pic=array(
	/*
	kosongkan nik+email apabila posisi tersebut kosong
	*/
		/*1=>array("posisi"=>"Bizdev. Group Head","nik"=>"1701.2010","email"=>"zaenal.fanani@modena.co.id, cicilia.sri@modena.co.id"),
		2=>array("posisi"=>"Trade Marketing Dept. Head","nik"=>"1610.1990", "email"=>"zaenal.fanani@modena.co.id, cicilia.sri@modena.co.id"),
		3=>array("posisi"=>"Customer Management Div. Head","nik"=>"0207.0087", "email"=>"zaenal.fanani@modena.co.id, cicilia.sri@modena.co.id"), 
		4=>array("posisi"=>"Product Marketing (1-3)","nik"=>"1611.2002", "email"=>"zaenal.fanani@modena.co.id, cicilia.sri@modena.co.id"), 
		5=>array("posisi"=>"Product Marketing (2-4)","nik"=>"0907.0942", "email"=>"zaenal.fanani@modena.co.id, cicilia.sri@modena.co.id"), 
		6=>array("posisi"=>"Product Marketing (5-6)","nik"=>"1508.1879", "email"=>"zaenal.fanani@modena.co.id, cicilia.sri@modena.co.id"), 
		7=>array("posisi"=>"Direksi","nik"=>"99999999", "email"=>"zaenal.fanani@modena.co.id, cicilia.sri@modena.co.id"), 
		8=>array("posisi"=>"Marketing Group Head","nik"=>"0803.0655", "email"=>"zaenal.fanani@modena.co.id, cicilia.sri@modena.co.id"), 
		9=>array("posisi"=>"Area Manager Barat","nik"=>"1410.1810", "email"=>"zaenal.fanani@modena.co.id, cicilia.sri@modena.co.id"), 
		10=>array("posisi"=>"Area Manager Timur","nik"=>"1311.1730", "email"=>"zaenal.fanani@modena.co.id, cicilia.sri@modena.co.id"), */
		1=>array("posisi"=>"Bizdev. Group Head","nik"=>"1701.2010","email"=>"miftahur.rohman@modena.co.id,benny.tan@modena.co.id"),
		2=>array("posisi"=>"Trade Marketing Dept. Head","nik"=>"1610.1990", "email"=>"bambang.murdhono@modena.co.id"),
		3=>array("posisi"=>"Customer Management Div. Head","nik"=>"0207.0087", "email"=>"robert.widjaja@modena.co.id"), 
		4=>array("posisi"=>"Product Marketing (1-3)","nik"=>"1706.2035", "email"=>"paul.daniel@modena.co.id"), 
		5=>array("posisi"=>"Product Marketing (2-4)","nik"=>"0209.0105", "email"=>"munarko@modena.id"), 
		//5=>array("posisi"=>"Product Marketing (2-4)","nik"=>"0907.0942", "email"=>"fommy.fendy@modena.co.id"), ganti dari pak fommy ke pak munarko
		6=>array("posisi"=>"Product Marketing (5-6)","nik"=>"1508.1879", "email"=>"hendrik.senjaya@modena.co.id"), 
		7=>array("posisi"=>"Direksi","nik"=>"99999999", "email"=>"djizhar@modena.co.id"), 
		8=>array("posisi"=>"Marketing Group Head","nik"=>"0803.0655", "email"=>"novi.hariyanti@modena.co.id,robert.widjaja@modena.co.id"), 
		9=>array("posisi"=>"Area Manager Barat","nik"=>"1805.2101", "email"=>"dedi.muljana@modena.co.id"), 
		10=>array("posisi"=>"Area Manager Timur","nik"=>"1410.1810", "email"=>"benny.tan@modena.co.id"), 
		//10=>array("posisi"=>"Area Manager Timur","nik"=>"1311.1730", "email"=>"ferry.burlian@modena.co.id"), pak ferry pindah ke vietnam sementara area barat dan timur ke pak benny.
		11=>array("posisi"=>"Project Dept. Head","nik"=>"1608.1976", "email"=>"jeremia.indra@modena.co.id"), 
		12=>array("posisi"=>"Franchise Dept. Head","nik"=>"1603.1947", "email"=>"ong.andre@modena.co.id"), 
		13=>array("posisi"=>"Product Marketing Professional","nik"=>"1710.2058", "email"=>"hanly.setiawan@modena.co.id"), 
		14=>array("posisi"=>"KAM Dept. Head","nik"=>"1410.1811", "email"=>"joe.jordan@modena.co.id"), 
		);

		
$arr_moa=array(
	/*
	1. rule : diskon_id=>pic
	*/
		1=>array(1=>"1"),
		//2=>array(1=>"1"),
		3=>array(1=>"1"),
		5=>array(1=>"2"),
		13=>array(1=>"1"),
		14=>array(1=>"1"),
		16=>array(1=>"1"),
		22=>array(1=>"4"),
		23=>array(1=>"5"),
		24=>array(1=>"6")
		);

$arr_status_persetujuan = array(0 => "Belum dikirimkan", "Belum mendapatkan persetujuan", "<span class=\"span-hijau\">Disetujui</span>", "<span class=\"span-merah\">Tidak disetujui</span>", "Tidak memerlukan persetujuan");
		
// nama server aplikasi
define("__NAMA_SERVER__", "http://air.modena.co.id/dm/");

// path untuk upload lampiran		
define("__UPLOAD_PATH__", "upload/");
		
// pembulatan nilai order, dengan nilai > 0		
define("__KONSTANTA_PEMBULATAN__", 1);

// TEMP
/*$_SESSION["sales_id"] = "MBSD25";
$_SESSION["sales_kode"] = "MBSD25";
$_SESSION["nama_lengkap"] = "M. ZF";
$_SESSION["cabang"] = "GDGPST";

keterkaitan database ::
sgtdat / trdat (ujicoba)
mesdb
fpp

*/
?>