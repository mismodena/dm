var title = 'Pilih Item Order'

function pastikan_cek(){
	var ret = false;
	var mi = document.getElementById('jumlah_item_campaign_noncampaign').value, counter = 0;
	for(var x = 1; x < x+2; x++){
		try{
			if( counter >= mi ) break;
			var cb = document.getElementById('b_cb_' + x)
			if (cb.checked) ret = true;
			counter++;
			}catch(e){}		
	}
	return ret;
}

function execute( mode ){
	var sc = 'hapus_item_diskon', tx = 'Yakin menghapus data item terpilih?';
	if( mode == 1 ) {sc = 'register_item_diskon', tx = 'Yakin melanjutkan entri data pengajuan diskon display?'}
	if( !pastikan_cek() ){alert('Pastikan sudah dipilih minimal 1 item untuk diproses!'); return false}
	
	if( confirm(tx) )
	__submit('<?=$page?>', 'c=pilih_itemorder&sc='+ sc);
}
