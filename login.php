<?
include "includes/top_blank.php";

if( isset( $_REQUEST["err"] ) )
	$string_error = "<tr><td colspan=3><h4>Nama Pengguna / Password Salah!</h4></td></tr>";

?>
<script>
function login(){
	if( !__verified() ) return false;
	__submit('?c=login', '')
}

</script>
<style>
#table_login{
	width:100%;
	padding:17px;
}
#table_login tr:nth-child(1) td{
	text-align:left !important;
}
#table_login tr td:nth-child(1){
	text-align:left;
}
input{
	width:100% !important;
	max-width:277px  !important;
}
h4{
	border: solid 1px #CCC;
	background-color:#EEE;
	padding:5px;
}
</style>

<table id="table_login">
	<tr>
		<td colspan="2" style="padding-bottom:13px"><h2>Aplikasi Mobile Sales - PT. INDOMO MULIA</h2></td>
	</tr>	<?=@$string_error?>
	<tr>
		<td colspan="2" style="padding-bottom:13px">Silahkan isikan nama pengguna dan kata kunci di isian berikut ini :</td>
	</tr>
	<tr>
		<td>
			Nama Pengguna
			<div style="position:relative" class="input-container">
				<input type="text" name="t_username" id="t_username" value="<?=@$_REQUEST["t_username"]?>" />
				<div id="err_t_username" class="error-empty-input" title="Mohon isikan nama pengguna"></div>
			</div>
		</td>
	</tr>
	<tr>
		<td style="padding-top:7px">
			Kata Kunci
			<div style="position:relative" class="input-container">
				<input type="password" name="t_password" id="t_password" value="" />
				<div id="err_t_password" class="error-empty-input" title="Mohon isikan kata kunci"></div>
			</div>			
		</td>
	<tr>
		<td style="padding-top:13px"><input type="button" name="b_login" id="b_login" value="Login" onclick="javascript:login()" /></td>
	</tr>
	</tr>
</table>

<?
include "includes/bottom.php";
?>