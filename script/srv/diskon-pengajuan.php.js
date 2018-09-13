var title = 'Pengajuan Tambahan Diskon';

try{
	if (window.addEventListener) {
		window.addEventListener("scroll", function () {fix_sidemenu(); });
		window.addEventListener("resize", function () {fix_sidemenu(); });  
		window.addEventListener("touchmove", function () {fix_sidemenu(); });  
		window.addEventListener("load", function () {fix_sidemenu(); });
	} else if (window.attachEvent) {
		window.attachEvent("onscroll", function () {fix_sidemenu(); });
		window.attachEvent("onresize", function () {fix_sidemenu(); });  
		window.attachEvent("ontouchmove", function () {fix_sidemenu(); });
		window.attachEvent("onload", function () {fix_sidemenu(); });
	}
}catch(e){}

function fix_sidemenu() {
	var w, top, ob;
	w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;  
	top = scrolltop();
	ob = document.getElementById("b_hitung");
	if (top > 279)
		ob.setAttribute('style', 'position:fixed; top:0px; left:0px;margin-top:0px;');   
	else 
		ob.setAttribute('style', 'position:relative; ');   
}
  
function scrolltop() {
	var top = 0;
	if (typeof(window.pageYOffset) == "number") {
		top = window.pageYOffset;
	} else if (document.body && document.body.scrollTop) {
		top = document.body.scrollTop;
	} else if (document.documentElement && document.documentElement.scrollTop) {
		top = document.documentElement.scrollTop;
	}
	return top;
}

function detail_note( mode ){
	var link_munculkan = document.getElementById('link_munculkan_detail_note');
	var detail_note = document.getElementById('detail-note');
	var display_1 = mode==1 ? 'block' : 'none';
	var display_2 = mode==1 ? 'none' : 'block';
	link_munculkan.setAttribute('style', 'display:' + display_2);
	detail_note.setAttribute('style', 'display:' + display_1);
}

function pilih_diskon(){
	__submit('diskon-pengajuan.php', 'c=hitung_order&sc=pilih_diskon')
}

function pilih_item(diskon){
	/*var od = document.getElementById('t_disc_' + diskon);
	var d = parseFloat( String( od.value ).replace(/,/gi, '') );
	if( d >= 100 || d <= 0 || isNaN(d) ) {alert('Pemilihan item order hanya berlaku untuk tambahan diskon dalam satuan persen!\nMohon isikan diskon persen dalam rentang 1 - 99!'); return false;}*/
	//__submit('diskon-pengajuan-pilihitemorder.php', 'diskonid='+ diskon);
	__submit('diskon-pengajuan.php', 'c=hitung_order&sc=pilih_item_order&diskonid='+ diskon);
}

function pilih_item_bqtq(diskon){
	/*var od = document.getElementById('t_disc_' + diskon);
	var d = parseFloat( String( od.value ).replace(/,/gi, '') );
	if( d >= 100 || d <= 0 || isNaN(d) ) {alert('Pemilihan item order hanya berlaku untuk tambahan diskon dalam satuan persen!\nMohon isikan diskon persen dalam rentang 1 - 99!'); return false;}*/
	//__submit('diskon-pengajuan-pilihitemorder.php', 'diskonid='+ diskon);
	__submit('diskon-pengajuan.php', 'c=hitung_order&sc=pilih_item_order_bqtq&diskonid='+ diskon);
}

function pilih_item_free(diskon){
	//__submit('diskon-pengajuan-pilihitemfree.php', 'diskonid='+ diskon);
	__submit('diskon-pengajuan.php', 'c=hitung_order&sc=pilih_item_free&diskonid='+ diskon);
}

function pilih_item_free_bqtq(diskon){
	//__submit('diskon-pengajuan-pilihitemfree.php', 'diskonid='+ diskon);
	__submit('diskon-pengajuan.php', 'c=hitung_order&sc=pilih_item_free_bqtq&diskonid='+ diskon);
}

function hapus_item(diskon, item){
	__submit('diskon-pengajuan.php', 'c=hitung_order&sc=hapus_itemorder&diskonid='+ diskon +'&item_seq=' + item );
}

function hapus_item_bqtq(diskon, item){
	__submit('diskon-pengajuan.php', 'c=hitung_order&sc=hapus_itemorder_bqtq&diskonid='+ diskon +'&item_seq=' + item );
}

function hapus_itemfree(diskon, item){
	__submit('diskon-pengajuan.php', 'c=hitung_order&sc=hapus_itemfree&diskonid='+ diskon +'&item_id=' + item );
}

function hapus_itemfree_bqtq(diskon, item){
	__submit('diskon-pengajuan.php', 'c=hitung_order&sc=hapus_itemfree_bqtq&diskonid='+ diskon +'&item_id=' + item );
}

function kirim_pengajuan(){	
	var arr_tambahan_diskon = new Array( <?=@implode(",", @$arr_tambahan_diskon)?> );
	for(var x = 0; x < arr_tambahan_diskon.length; x++){
		try{
			var td = document.getElementById('t_disc_' + arr_tambahan_diskon[x]); 
			if( td.value == '' || parseFloat(td.value) == 0 ) {
				alert('Periksa kembali pengajuan tambahan diskon.\nPastikan nilai pengajuan diskon lebih dari nol (0)!');
				return false;
			}
		}catch(e){}
	}
	if( !confirm('Kirimkan pengajuan tambahan diskon ini?') ) return false;
	__submit('diskon-pengajuan.php', 'c=hitung_order&sc=kirim_pengajuan')
}