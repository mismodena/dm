<?
include "includes/top_blank.php";

// parameter dibutuhkan : paketid, userid, c=reset
?>
<style>
.link{
	color:blue;
}
.link:hover{
	color:orange;
	cursor:pointer;
}
.display-none{
	display:none
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function(){    
    if(document.getElementsByClassName("tot_cn")[0]==undefined) {
    	$(".cn").css("display","none");
    }
});
function ganti_kontainer(ob){
	var lnk = new Array('link-daftar-item-campaign', 'link-daftar-item-non-campaign');
	
	for( var x = 0; x<lnk.length; x++ ){
		
		var class_kontainer_display = 'display-none', class_link_kontainer = 'link', onclick_link_kontainer = 'ganti_kontainer(this)'
		
		if( lnk[x] == ob.id ){
			class_kontainer_display = ''
			class_link_kontainer = ''
			onclick_link_kontainer = ''
		}
		document.getElementById( 'kontainer-' + lnk[x] ).setAttribute('class', class_kontainer_display);
		document.getElementById( lnk[x] ).setAttribute('class', class_link_kontainer);
		document.getElementById( lnk[x] ).setAttribute('onclick', onclick_link_kontainer);
		
	}
	
}
function func_cari_item(item){
	document.getElementById('frmx').src = 'simulasi.php?paketid=<?=@$simulasi_paket["paketid"]?>&c=item_non_campaign&item='+item.value
}
function munculkan_campaign(){alert('Pilih campaign tidak diperbolehkan di mode simulasi!');}

</script>
<iframe id="frmx" style="display:none; width:200px; height:200px"></iframe>
<div style="width:100%">
	<div style="float:left; width:100%; border-bottom:1px solid #CCC; line-height:37px; padding-bottom:7px">
		<span style="font-size:21px;">Simulasi Campaign <a href="paket-detail.php?paketid=<?=@$simulasi_paket["paketid"]?>" style="color:blue"><?=@$simulasi_paket["paketid"]?></a></span><br />
		<span style="line-height:17px"><?=@$simulasi_paket["keterangan_paket"]?></span>
	</div>
	<div id="daftar-item">
		<div>
			<div>
				<span class="title"><span id="link-daftar-item-campaign">Daftar Item Campaign</span> | <span id="link-daftar-item-non-campaign" class="link" onclick="ganti_kontainer(this)">Daftar Item Non-Campaign</span></span>
				<div id="kontainer-link-daftar-item-campaign">
					<div class="bg-putih" style="margin-top:7px; border:solid #CCC 1px !important; padding:0px">
						<div  style="margin-right:7px">
						<?=@$item_campaign?>
						</div>
					</div>
				</div>
				<div id="kontainer-link-daftar-item-non-campaign" class="display-none">
					<div class="bg-putih" style="margin-top:7px; border:solid #CCC 1px !important; padding:0px">
						<div style="padding:7px">
							Cari item : <input type="text" name="cari_item" id="cari_item" value="" /><input type="button" name="b_cari_item" id="b_cari_item" value="Cari" onclick="func_cari_item(document.getElementById('cari_item'))" />
						</div>
						<div  style="margin-right:7px" id="list-daftar-item-non-campaign">
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="simulasi-pembelian">
		<div style="float:right">
			<div style="float:left">
				<span class="title">Simulasi Pembelian Item</span>
				<div style="margin-left:-7px; margin-top:7px; float:left">
					<div  style="margin-right:7px">
					<?=$data_order?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" name="userid" id="userid" value="<?=$sales_id?>" />
<input type="hidden" name="paketid" id="paketid" value="<?=$simulasi_paket["paketid"]?>" />
<?
include "includes/bottom.php";
?>