var title = 'Data Persediaan';

function cari_item(){
	var i = document.getElementById('item').value;
	if( i == '' ) {alert('Mohon isikan item yang akan dicari!');return false;}
	location.href='stok.php?item=' + i;
}