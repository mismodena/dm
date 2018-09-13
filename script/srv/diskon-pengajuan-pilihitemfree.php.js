var title = 'Pilih Item Free';

try{
	$(document).ready(function(){
		document.forms[0].method='get';
	})
}catch(e){document.forms[0].method='get';}

function cari_item(){
	var cb = document.getElementById('cbx');
	var cbp = ''
	if( !cb.checked ) {
		cb.setAttribute('style', 'display:none');
		cb.value=0
		cb.checked=true		
	}else cb.value=1

	document.forms[0].action = 'diskon-pengajuan-pilihitemfree.php'
	document.forms[0].submit()
}

function ubah_warna(id){
	var cb = document.getElementById('b_cb_' + id);
	var div = document.getElementById( 'div_container_' + id);
	div.setAttribute('style', 'background-color:' + ( cb.checked ? '#ffff80' : 'transparent' ) );
}

function beli(i, g, m){
	var dealer_id = document.getElementById('dealer_id').value;
	var order_id = document.getElementById('order_id').value;
	var diskon = document.getElementById('diskonid').value;	
	var qty = document.getElementById('q_' + i).value;
	if( parseInt(qty) > parseInt( document.getElementById(m).value ) ){alert('Kuantitas pembelian melebihi stok tersedia!'); return false;}
	if( qty == '' || qty <= 0 ) qty = 1;
	var harga = document.getElementById('harga_' + i).value;
	location.href='diskon-pengajuan.php?c=pilih_itemfree&dealer_id='+dealer_id+'&order_id='+ order_id +'&diskonid='+diskon+'&item_id='+ i +'&qty=' + qty +'&harga='+ harga+'&gudang='+g;
}

function gudang_lain(o, i){
	location.href='transaksi-2-item-gudanglain.php?dealer_id=<?=$_REQUEST["dealer_id"]?>&diskonid=<?=$_REQUEST["diskonid"]?>&order_id='+o+'&gudang=all&sp=&item='+i
}