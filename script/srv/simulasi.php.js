var title = 'Simulasi Campaign';

function dapatkan_nilai(){
	var paketid = parent.document.getElementById('paketid').value;
	var userid = parent.document.getElementById('userid').value;
	return new Array(paketid, userid);
}

function pilih_item(o){
	o.setAttribute('style', 'display:none');
	var frm = document.getElementById('f_' + o.id);
	frm.setAttribute('style', 'display:block');
	var nilai = dapatkan_nilai();
	frm.src = 'simulasi-item.php?paketid='+nilai[0]+'&userid='+nilai[1]+'&sub_kategoriid=' + o.id;
}

function simulasi(o){
	var nilai = dapatkan_nilai();
	var qty = document.getElementById( 't_' + o.id).value;
	var harga = String(document.getElementById('harga_' + o.id).value).replace(/,/gi, '');
	location.href='simulasi.php?paketid='+nilai[0]+'&userid='+nilai[1]+'&c=tambah_item&item='+o.id +'&qty=' + qty + '&harga=' + harga;
}

function simulasi_non_paket(o){
	var nilai = dapatkan_nilai();
	var qty = document.getElementById( 't_' + o.id).value;
	var harga = String(document.getElementById('harga_' + o.id).value).replace(/,/gi, '');
	location.href='simulasi.php?sc=non_paket&paketid='+nilai[0]+'&userid='+nilai[1]+'&c=tambah_item&item='+o.id +'&qty=' + qty + '&harga=' + harga;	
}

function hapus_item(i){
	var nilai = dapatkan_nilai();
	if(  confirm('Hapus item ?') )
		location.href='simulasi.php?paketid='+nilai[0]+'&userid='+nilai[1]+'&c=hapus_item&item_seq=' + i
	else return false
}

function hapus_campaign(i){
	var nilai = dapatkan_nilai();
	if(  confirm('Hapus campaign untuk item tersebut ?') )
		location.href='simulasi.php?paketid='+nilai[0]+'&userid='+nilai[1]+'&c=hapus_campaign&item_seq=' + i
	else return false
}

function ubah_kuantitas(i, q){
	var nilai = dapatkan_nilai();
	var nq = prompt('Masukkan kuantitas item!', q);
	if( nq == null)
		return false
	else
		location.href='simulasi.php?paketid='+nilai[0]+'&userid='+nilai[1]+'&c=ubah_kuantitas&item_seq=' + i + '&qty=' + ( isNaN( parseInt(nq) ) ? q : nq );	
}

function hilangkan_tombol_campaign(){
	var btn = document.getElementsByTagName('input');
	for(var x = 0; x < btn.length; x++){
		if( btn[x].type == 'button' && btn[x].value == 'Hapus Campaign' )
			//btn[x].disabled=true;
			btn[x].setAttribute('style', 'display:none');
	}
}

function ganti_a_href(){
	var a = document.getElementsByTagName('a');
	for(var x = 0; x < a.length; x++)
		a[x].href = String(a[x].href).replace(/paket-detail/gi, 'paket-detail-simulasi');
}

function lihat_campaign(i){
	var ob = "#detail_campaign_" + i
	var img = "down";
	if( $( ob ).is( ":hidden" ))	img = "up";
	$( ob  ).toggle( "blind", "slow", function(){ $("#img_detail_campaign_" + i).attr("src", "images/"+img+".png"); } );
}

try{
	$(document).ready(function(){
		hilangkan_tombol_campaign();
		ganti_a_href();
	})
}catch(e){ hilangkan_tombol_campaign(); ganti_a_href(); }