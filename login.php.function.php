<?

if( @$_REQUEST["c"] =="login" ){
	
	$rs = otorisasi::pengguna( 
		array(
			"user_id" =>array("=", "'". main::formatting_query_string($_POST["t_username"]) . "'"), 
			"password" => array("=", "'". main::formatting_query_string(sha1( $_POST["t_password"] )) . "'" ),
			"aktif" => array("=", "'1'")
			)
		);
	if( sqlsrv_num_rows( $rs ) > 0 ){
		$data = sqlsrv_fetch_array( $rs );
		$_SESSION["sales_id"] = $data["user_id"];
		$_SESSION["nama_lengkap"] = $data["nama_lengkap"];
		$_SESSION["sales_kode"] = $data["kode_sales"];
		$_SESSION["sales_nik"] = $data["nik"];
		$_SESSION["cabang"] = $data["cabang"];
		// update data dbo.[user] kolom sesi_login, tanggal_login, ip_login
		if( SINGLE_ACCESS )
			otorisasi::update_sesi_login();
		header("location:index.php");

	} else
		header("location:login.php?err=" . sha1( $_POST["t_username"] . $_POST["t_password"] ));
		
}elseif( @$_REQUEST["c"] == "kebetot" ){
	$string_error = "<tr><td><div style=\"padding:3px; border:solid 1px #CCC; background-color:#EEE; margin:7px 0px 17px 0px\"><h3><span class=\"tanda-seru\">!</span>Silahkan login ulang<br />Terdapat pengguna lain masuk ke aplikasi dengan menggunakan akun login yang sama.</h3></div></td></tr>";
}		

?>