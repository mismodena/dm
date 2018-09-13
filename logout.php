<?
include "includes/top_blank.php";

unset(
	$_SESSION["user_login"],
	$_SESSION["sales_id"],
	$_SESSION["sales_kode"],
	$_SESSION["nama_lengkap"],
	$_SESSION["cabang"],
	$_SESSION["t_po"],
	$_SESSION["alamat_kirim"],
	$_SESSION["sesi_login"]
);

echo "<script>location.href='login.php?". http_build_query( $_REQUEST ) ."'</script>";

include "includes/bottom.php";
?>