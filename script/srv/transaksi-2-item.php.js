var title = 'Transaksi Penjualan - Cari item';

try{
	$(document).ready(function(){
		document.forms[0].method='get';
	})
}catch(e){document.forms[0].method='get';}

function cari_item(){
	var i = document.getElementById('item').value;
	var c = document.getElementById('cbx');
	location.href='transaksi-2-item.php?item=' + i + '&cbx=' + (cbx.checked ? cbx.value : '');
}

function beli(i, g, m){
	var paket = document.getElementsByTagName('input');
	var sel_paket = '';
	for( var x=0; x<paket.length; x++){
		if( paket[x].type == 'radio' && paket[x].name == 'r_' + i &&  paket[x].checked){
			sel_paket = paket[x].value;
			break;
		}
	}
	var qty = document.getElementById('q_' + i).value;
	if( parseInt(qty) > parseInt( document.getElementById(m).value ) ){alert('Kuantitas pembelian melebihi stok tersedia!'); return false;}
	if( qty == '' || qty <= 0 ) qty = 1;
	var harga = document.getElementById('harga_' + i).value;

	location.href='transaksi-2-item.php?c=tambah_item&item='+i+'&harga='+harga+'&qty='+qty+'&paket='+sel_paket+'&gudang='+g;
}

function gudang_lain(o, i){
	location.href='transaksi-2-item-gudanglain.php?order_id='+o+'&gudang=all&item='+i
}

function simpan_session( ob ){
	var frm = __create_iframe_otfly();
	frm.src = 'transaksi-2.php?c=simpan_session&'+ob.name+'='+ob.value
}