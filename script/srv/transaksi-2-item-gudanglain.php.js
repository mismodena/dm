var title = 'Transaksi Penjualan - Cari item - Pilih Gudang';

try{
	$(document).ready(function(){
		document.forms[0].method='get';
	})
}catch(e){document.forms[0].method='get';}

function beli(g, m){
	var i = document.getElementById('item').value;
	var paket = document.getElementsByTagName('input');
	var sel_paket = '';
	for( var x=0; x<paket.length; x++){
		if( paket[x].type == 'radio' && paket[x].name == 'r_' + i &&  paket[x].checked){
			sel_paket = paket[x].value;
			break;
		}
	}
	var qty = document.getElementById('q_' + g).value;
	if( parseInt(qty) > parseInt( document.getElementById(m).value ) ){alert('Kuantitas pembelian melebihi stok tersedia!'); return false;}
	if( qty == '' || qty <= 0 ) qty = 1;
	var harga = document.getElementById('harga').value;

	location.href='transaksi-2-item.php?c=tambah_item&item='+i+'&harga='+harga+'&qty='+qty+'&paket='+sel_paket+'&gudang='+g;
}

function beli_itemfree(g, m){
	var i = document.getElementById('item').value;
	var dealer_id = document.getElementById('dealer_id').value;
	var order_id = document.getElementById('order_id').value;
	var diskon = document.getElementById('diskonid').value;	
	var qty = document.getElementById('q_' + g).value;
	if( parseInt(qty) > parseInt( document.getElementById(m).value ) ){alert('Kuantitas pembelian melebihi stok tersedia!'); return false;}
	if( qty == '' || qty <= 0 ) qty = 1;
	var harga = document.getElementById('harga' ).value;
	location.href='diskon-pengajuan<?= @$_REQUEST["src"] ?>.php?c=pilih_itemfree&dealer_id='+dealer_id+'&order_id='+ order_id +'&diskonid='+diskon+'&item_id='+ i +'&qty=' + qty +'&harga='+ harga+'&gudang='+g;	
}

function konfirmasi_beli(g, m){
	if( confirm('Pastikan Anda telah melakukan konfirmasi pengambilan stok ke cabang bersangkutan!\nLanjutkan proses?') )
		beli(g, m);
	return false;
}
	
function konfirmasi_beli_itemfree(g, m){
	if( confirm('Pastikan Anda telah melakukan konfirmasi pengambilan stok ke cabang bersangkutan!\nLanjutkan proses?') )
		beli_itemfree(g, m);
	return false;	
}