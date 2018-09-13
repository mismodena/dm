<?
set_time_limit(120);

include "lib/var.php";
include "lib/cls_main.php";
include "lib/sql.php";

error_reporting(E_ALL);

define("__AKTIFKAN_EMAIL__", true);

$template = file_get_contents("template/konten_email_info_order.html");
$template_data = "<td>#value#</td>";
$arr_fetch_data = array("order_id", "nama_lengkap", "namecust", "tanggal");
$arr_cabang_order = array();

$sql = "select a.order_id, upper(d.nama_lengkap) nama_lengkap, e.namecust, convert(varchar, a.tanggal, 105) tanggal, f.cabang, d.email from [order] a inner join
		(
		select a.order_id from [order] a outer apply (select * from SGTDAT..OEORDH where ORDNUMBER = a.order_id or ORDNUMBER = a.order_id + '-0' ) x
		where /*a.kirim = 1 and*/ x.ORDNUMBER is null
		) b on a.order_id = b.order_id
		left outer join order_email c on a.order_id = c.order_id and a.user_id = c.user_id 
		inner join [user] d on a.user_id = d.user_id
		inner join SGTDAT..ARCUS e on a.dealer_id = e.IDCUST
		/*left outer join 
		email_cabang f 
				on exists( select 1 from dbo.ufn_split_string(f.email_sales, ',') where 
					upper(ltrim(rtrim(splitdata))) in (select upper(ltrim(rtrim(splitdata))) from dbo.ufn_split_string(upper(ltrim(rtrim(d.email))), ',') ) 
					and upper(splitdata) like upper(f.cabang) + '%'
					)*/
		outer apply ( select * from email_cabang where exists( select 1 from dbo.ufn_split_string(email_sales, ',') where 
					upper(ltrim(rtrim(splitdata))) in (select upper(ltrim(rtrim(splitdata))) from dbo.ufn_split_string(upper(ltrim(rtrim(d.email))), ',') ) 
					and upper(ltrim(rtrim(splitdata))) like upper(ltrim(rtrim(cabang))) + '%'
					) ) f
		outer apply (select count(1) jumlah_diskon, order_id from order_diskon where order_id = a.order_id group by order_id ) g 
		outer apply  (select COUNT(1) jumlah_diskon_blm_approve, order_id from order_diskon where disetujui = 0 and order_id = a.order_id group by order_id) h 
		left outer join SGTDAT..OEINVH i on a.order_id = i.ORDNUMBER
		where --a.order_id = 'BLP-MHSD40-013956'
		c.order_id is null and ( a.kirim = 1 or 
			( case when g.jumlah_diskon > 0 then isnull(h.jumlah_diskon_blm_approve, 0) end = 0 and left(a.order_id, 5) <> 'DRAFT' )  
		)
		and i.ORDNUMBER is null
		and /*start per april tanggal 23 april 2018*/ a.tanggal > '4/22/2018'
		";
$rs = sql::execute( $sql );
while( $data = sqlsrv_fetch_array( $rs ) )
	$arr_cabang_order[ strtoupper(trim($data["cabang"])) ][ $data["order_id"] ] = $data;

if( count( $arr_cabang_order ) <= 0 ) die("<h2>Aman terkendali!</h2>");

$arr_dealer["bandung"] = array("BDG", "bandung.sales@modena.co.id");
$arr_dealer["banjarmasin"] = array("BJM", "banjarmasin.sales@modena.co.id");
$arr_dealer["bali"] = array("DPS", "bali.sales@modena.co.id");
$arr_dealer["kediri"] = array("KDR", "kediri.sales@modena.co.id");
$arr_dealer["lampung"] = array("LPG", "lampung.sales@modena.co.id");
$arr_dealer["makassar"] = array("MKS", "makassar.sales@modena.co.id");
$arr_dealer["malang"] = array("MLG", "malang.sales@modena.co.id");
$arr_dealer["manado"] = array("MND", "menado.sales@modena.co.id");
$arr_dealer["pekanbaru"] = array("PKB", "pekanbaru.sales@modena.co.id");
$arr_dealer["palembang"] = array("PLB", "palembang.sales@modena.co.id");
$arr_dealer["pontianak"] = array("PTK", "pontianak.sales@modena.co.id");
$arr_dealer["purwokerto"] = array("PWK", "purwokerto.sales@modena.co.id");
$arr_dealer["surabaya"] = array("SBY", array("'surabaya.sales1@modena.co.id'", "'surabaya.sales2@modena.co.id'"));
$arr_dealer["semarang"] = array("SMG", "semarang.sales@modena.co.id");
$arr_dealer["samarinda"] = array("SMR", "samarinda.sales@modena.co.id");
$arr_dealer["yogyakarta"] = array("YGY", "yogyakarta.sales@modena.co.id");
$arr_dealer["medan"] = array("MDN", "medan.sales@modena.co.id");

if( array_key_exists( @$_REQUEST["cabang"], $arr_dealer ) ){
	$arr_dealer_temp = $arr_dealer[ $_REQUEST["cabang"] ];
	unset($arr_dealer);
	$arr_dealer[ $_REQUEST["cabang"] ] = $arr_dealer_temp;
}

$arr_dealer_pusat[] = "jkt2.sales@modena.co.id";
$arr_dealer_pusat[] = "putri.anggraini@modena.co.id";
$arr_dealer_pusat[] = "anastasia.fabiola@modena.co.id";
$arr_dealer_pusat[] = "dian.wulandari@modena.co.id";
$arr_dealer_pusat[] = "juwita.femina@modena.co.id";
$arr_dealer_pusat[] = "jkt3.sales@modena.co.id";
$arr_dealer_pusat[] = "diah.aryati@modena.co.id";

if( array_key_exists( @$_REQUEST["email"], $arr_dealer_pusat ) ){
	$arr_dealer_pusat_temp = $arr_dealer_pusat[ @$_REQUEST["email"] ];
	unset( $arr_dealer_pusat );
	$arr_dealer_pusat[ $_REQUEST["email"] ] = $arr_dealer_pusat_temp;
}

if( @$_REQUEST["email"]!="" ) goto Untuk_pusat;

// INFO UTK CABANG
foreach( $arr_dealer as $cabang => $arr_inisial_email_admin ){
	
	list( $inisial_cabang, $email_admin ) = $arr_inisial_email_admin;
	
	$string_konten = $string_data = "";
	$counter = 1;
	
	if( !array_key_exists( strtoupper(trim($cabang)), $arr_cabang_order ) ) continue;
	
	$arr_data = $arr_cabang_order[ strtoupper(trim($cabang)) ];
	
	foreach( $arr_data as $data ){
	
		list( $order_inisial_cabang, $sales_kode, $urutan_order ) = explode("-", $data["order_id"]);
		//if( $order_inisial_cabang != $inisial_cabang ) continue;
			
		$string_data .= "<tr>" . str_replace( "#value#", $counter,  $template_data);
		foreach( $arr_fetch_data as $fetch_data )	$string_data .= str_replace( "#value#", $data[ $fetch_data ],  $template_data);
		$string_data .= str_replace( "#value#", "<a href=\"http://air.modena.co.id/dm/__email.php?order_id=". $data["order_id"] ."\" target=\"_blank\">Kirim Email Ulang</a>",  $template_data) . "</tr>";
		
		$counter++;
	}
	
	if( $string_data == "" ) continue;
	
	$arr_rpl["#konten#"] = $string_data;
	$arr_rpl["#URL#"] = "http://air.modena.co.id/dm/__scan_email.php?cabang=" . $cabang;
	$string_konten = str_replace( array_keys( $arr_rpl ), array_values( $arr_rpl ), $template);
	
	// utk ujicoba dulu
	//if( strtoupper(trim($cabang)) != "BANDUNG" ) goto Skip_Email;
	
	// send email
	//if( in_array( $cabang ) )
	if( is_array($email_admin) ){
		foreach ( $email_admin as $email ){
			$email = str_replace("'", "", $email);
			if( __AKTIFKAN_EMAIL__ )
				main::send_email( $email, "Rangkuman order DM baru", $string_konten );
		}
	}
	else{
		if( __AKTIFKAN_EMAIL__ )
			main::send_email( $email_admin, "Rangkuman order DM baru", $string_konten );
	}
	
	Skip_Email:
	print_r($email_admin);
	echo $string_konten;
	
}

echo "<h1>MULAI PUSAT</h1>";

if( @$_REQUEST["cabang"]!="" ) exit;

Untuk_pusat:
if( count( @$arr_cabang_order[""] ) <= 0 ) goto Akhir; 
// INFO UNTUK PUSAT
foreach( $arr_dealer_pusat as $email_admin ){
	
	$string_konten = $string_data = "";
	$counter = 1;
	
	foreach( $arr_cabang_order[""] as $data ){
		
		if( strpos( strtolower($data["email"]), strtolower(trim($email_admin)) ) === false ) continue;
	
		$string_data .= "<tr>" . str_replace( "#value#", $counter,  $template_data);
		foreach( $arr_fetch_data as $fetch_data )	$string_data .= str_replace( "#value#", $data[ $fetch_data ],  $template_data);
		$string_data .= str_replace( "#value#", "<a href=\"http://air.modena.co.id/dm/__email.php?order_id=". $data["order_id"] ."\" target=\"_blank\">Kirim Email Ulang</a>",  $template_data) . "</tr>";
		
		$counter++;
		
	}
	
	if( $string_data == "" ) continue;
	
	$arr_rpl["#konten#"] = $string_data;
	$arr_rpl["#URL#"] = "http://air.modena.co.id/dm/__scan_email.php?email=" . $email_admin;
	$string_konten = str_replace( array_keys( $arr_rpl ), array_values( $arr_rpl ), $template);
	
	// send email
	if( is_array($email_admin) ){
		foreach ( $email_admin as $email ){
			$email = str_replace("'", "", $email);
			if( __AKTIFKAN_EMAIL__ )
				main::send_email( $email, "Rangkuman order DM baru", $string_konten );
		}
	}
	else{
		if( __AKTIFKAN_EMAIL__ )
			main::send_email( $email_admin, "Rangkuman order DM baru", $string_konten );
	}
	
	print_r($email_admin);
	echo $string_konten;
	
}
Akhir:
//die("<script>window.close()</script>");
?>