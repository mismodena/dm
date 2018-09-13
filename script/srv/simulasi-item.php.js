var title = 'Simulasi Item';

function simulasi(o){
	var paketid = parent.document.getElementById('paketid').value;
	var userid = parent.document.getElementById('userid').value;
	var qty = document.getElementById( 't_' + o.id).value;
	var harga = String(document.getElementById('harga_' + o.id).value).replace(/,/gi, '');
	parent.location.href='simulasi.php?paketid='+paketid+'&userid='+userid+'&c=tambah_item&item='+o.id +'&qty=' + qty + '&harga=' + harga;
}

function simulasi_non_paket(o){
	var userid = parent.document.getElementById('userid').value;
	var qty = document.getElementById( 't_' + o.id).value;
	var harga = String(document.getElementById('harga_' + o.id).value).replace(/,/gi, '');
	parent.location.href='simulasi.php?sc=non_paket&paketid='+paketid+'&userid='+userid+'&c=tambah_item&item='+o.id +'&qty=' + qty + '&harga=' + harga;

}