<?

include "include.php";

if( isset($_REQUEST["login"]) ){
	if( $_POST["t_user"] == "admin" && $_POST["t_password"] == "modena328" ){
		$_SESSION["sales_id"] = "ADMINMIS";
		header("location:?hallo+admin,+kerja+yg+bener+ya!");
	}
}

if( @$_SESSION["sales_id"] != "ADMINMIS" ) die( file_get_contents("login.html") );

if( @$_REQUEST["cari_pengguna"] != "" )
	$arr_parameter["/*cari pengguna*/"] = array("", "");

$rs_pengguna = pengguna::browse_pengguna( $arr_parameter );
$counter = 1;

while( $data_pengguna = sqlsrv_fetch_array($rs_pengguna) ){
	$s_temp = "<td>$counter</td>";
	
	$arr_data = array( "kode_sales", "user_id", "nama_lengkap", "status_aktifasi", "email", "cabang", "nama_lengkap_bm" );			
	foreach( $arr_data as $kolom )	$s_temp .= "<td>". $data_pengguna[ $kolom ] ."</td>";
		
	@$s_data_pengguna .= "<tr>$s_temp</tr>";
	
	$counter++;
}

?>
<h3>ATUR PENGGUNA!</h3>
<table cellpadding="1" cellspacing="1" border="1" width="100%" style="margin-top:17px">
	<tr>
		<td>No.</td>
		<td>Kode Sales</td>
		<td>User Login</td>
		<td>Nama Lengkap</td>
		<td>Status Aktifasi</td>
		<td>Email</td>
		<td>Konfig. Gudang</td>
		<td>Branch Mgr</td>
	</tr><?=$s_data_pengguna?>
</table>