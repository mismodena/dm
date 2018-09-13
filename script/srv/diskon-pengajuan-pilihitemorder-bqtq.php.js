var title = 'Pilih Item Order';

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
	ob = document.getElementById("b_selesai");
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

function ubah_warna(id){
	var cb = document.getElementById('b_cb_' + id);
	var div = document.getElementById( 'div_container_' + id);
	div.setAttribute('style', 'background-color:' + ( cb.checked ? '#ffff80' : 'transparent' ) );
}

function munculkan_isian_kuantitas(ob, id){
	var kontainer = document.getElementById('kontainer_q_' + id);
	var display = 'none';
	if( ob.checked ) display = 'block'
	kontainer.setAttribute('style', 'display:' + display);
}

function ubah_diskon_item (){
	return false;
}

function __validasi(ob){
	var cek = false;	
	for(var x = 1; x <= (x+1); x++){
		try{
			if ( document.getElementById('b_cb_' + x).checked ) cek = true;
		}catch(e){break}
	}
	if(!cek) alert('Pilih paling tidak 1 (satu) item order yang akan diterapkan diskon tambahan!');
	return true;
}

