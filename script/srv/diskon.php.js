var title = 'Pengajuan Tambahan Diskon';

function lanjut_proses(d, o){
	location.href= 'diskon-pengajuan.php?dealer_id='+ d 
}

function cari_dealer(ob){
	location.href='diskon.php?t_dealer='+ob.value
}