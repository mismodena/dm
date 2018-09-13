// JavaScript Document

function __submit(url, par, tgt){
	replace_char()
	var sPar='';
	if(par!='')sPar='?'
	var parx=par.split('+')
	for(var x=0; x<parx.length; x++){
		try{var keyval=parx[x].split('=');document.getElementById(keyval[0]).value=keyval[1];}
		catch(e){sPar+=parx[x]+'&'}
	}
	document.forms[0].encoding='multipart/form-data';
	document.forms[0].method='post';
	if(tgt!=''&&typeof(tgt)!='undefined')document.forms[0].target=tgt;
	else document.forms[0].target='_self';
	document.forms[0].action=url+sPar.substr(0, sPar.length-1);
	DisablingInput('button', true)	;
	document.forms[0].submit();
}

function __submitAsync(method, url, data, callback, event){
	try{
	event.preventDefault();
	if(method=='POST'){
		try{/*var fd = new FormData(document.forms[0]); data = document.forms[0]*/var fd = new FormData(data);}
		catch(err){var o = __create_form_object_otfly('input', 'callback', callback, new Array('type'), new Array('hidden'));__submit(url, '', '');return false;}
		appending_file_elements(fd);
	}else{fd = data; /*json*/}
		$.ajax({
		type: 			method,
		url: 			url,
		async: 			true,
		data: 			fd,
		processData: 	false,
		contentType: 	false,
		beforeSend: 	function(){TINY.box.show({image:'images/loading.gif',boxid:'',width:33,height:33,fixed:true,maskid:'greymask',maskopacity:40,close:false});}
		})	
		.done(function(msg){		
			callback(msg);
			try{parent.TINY.box.hide();}catch(e){}
		});
	}catch(e){		
		data.submit_mode = "manual";
		if(method=='POST'){var o = __create_form_object_otfly('input', 'callback', callback, new Array('type'), new Array('hidden'));__submit(url, '', '');return false;}
		else{var o = __create_form_object_otfly('iframe', 'frm_callback', '', new Array('id'), new Array('frm_callback'));o.src=url+'?'+$.param(data);return false;}
	}	
}

function appending_file_elements(fd){
	var f = document.forms[0];
	for(var x=0; x<f.length; x++)
		if(f.elements[0].type=='file')
			fd.append('file', f.elements[0]);
}

function formatNumber(num,dec,thou,pnt,curr1,curr2,n1,n2) {
	num=String(num).replace(/,/gi,'')
	//var x = Math.round(num * Math.pow(10,dec));if (x >= 0) n1=n2='';var y = (''+Math.abs(x)).split('');var z = y.length - dec; if (z<0) z--; for(var i = z; i < 0; i++) y.unshift('0'); if (z<0) z = 1; y.splice(z, 0, pnt); if(y[0] == pnt) y.unshift('0'); while (z > 3) {z-=3; y.splice(z,0,thou);}var r = curr1+n1+y.join('')+n2+curr2;return r;
	var x = num * Math.pow(10,dec);if (x >= 0) n1=n2='';var y = (''+Math.abs(x)).split('');var z = y.length - dec; if (z<0) z--; for(var i = z; i < 0; i++) y.unshift('0'); if (z<0) z = 1; y.splice(z, 0, pnt); if(y[0] == pnt) y.unshift('0'); while (z > 3) {z-=3; y.splice(z,0,thou);}var r = curr1+n1+y.join('')+n2+curr2;return r;
}

function fokusinput(ob){
	ob.value=ob.value.replace(/,/gi, '')
	ob.select()
}

function unfokusinput(ob){
	if( isNaN( ob.value ) ) ob.value = 0;
	if( parseFloat(ob.value) > 100 ){ob.value=formatNumber(ob.value, 0,',','','','','-','');	return}	
	ob.value = ob.value;
	//ob.value=formatNumber(ob.value, 0,',','','','','-','');	
}

function replace_char(){
	for(var x=0; x<document.forms[0].length; x++)
		if(document.forms[0].elements[x].type=='text'||document.forms[0].elements[x].type=='textarea'||document.forms[0].elements[x].type=='hidden'){
			var arr_inp=document.forms[0].elements[x].id.split('_')
			var found_address=false
			for(var y=0; y<arr_inp.length; y++)		if(arr_inp[y]=='address')found_address=true;
			if(found_address)document.forms[0].elements[x].value=String(document.forms[0].elements[x].value).replace(/\n/gi, ' ')
			else document.forms[0].elements[x].value=String(document.forms[0].elements[x].value).replace(/\n/gi, '<br />')
		}
}

function DisablingInput(s_type, b){
	for(var x=0; x<document.forms[0].length; x++){
		try{if(document.forms[0].elements[x].type==s_type)document.forms[0].elements[x].disabled=b}catch(e){}
	}
}

function __create_iframe_otfly(){
	var ob_iframe=document.createElement('iframe')
	document.forms[0].appendChild(ob_iframe)
	ob_iframe.setAttribute('id', 'ob_iframe');	
	ob_iframe.setAttribute('name', 'ob_iframe');	
	ob_iframe.setAttribute('width', '0px')
	ob_iframe.setAttribute('height', '0px')
	ob_iframe.setAttribute('frameborder', '0px')
	ob_iframe.setAttribute('style', 'display:none')
	return ob_iframe;
}

function __create_form_object_otfly(i, n, v, atn, atv){
	var o;
	try{o = document.createElement('<'+ i +' name="'+ n +'">');} 
	catch (ex) {
		o = document.createElement(i);
		o.name=n;
	}
	document.forms[0].appendChild(o);
	for(var x=0; x<atn.length; x++) o.setAttribute(atn[x], atv[x]);
	o.setAttribute('value', v);
	return o;
}


function numbersonly(myfield, e, dec){
	var key;
	var keychar;	
	if (window.event)key = window.event.keyCode;
	else if (e)key = e.which;
	else return true;
	keychar = String.fromCharCode(key);	
	// control keys
	if ((key==null) || (key==0) || (key==8) || 
		(key==9) || (key==13) || (key==27) )
	   return true;	
	// numbers
	else if ((("0123456789").indexOf(keychar) > -1))
	   return true;	
	// decimal point jump
	else if (dec && (keychar == ".")){
	   myfield.form.elements[dec].focus();
	   return false;
	}else return false;
}


function fokusinput(ob){
	ob.value=ob.value.replace(/,/gi, '')
	ob.select()
}

function verifyEmail(ob){
	var status = true;     
	var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	 if(ob.value.search(emailRegEx)==-1)status=false
	 return status;
}

function __verified(/*a*/){	
	var r = true;
	//var at = a.href;	
	//a.href='javacript:void(0)';
	var f = document.forms[0];
	for(var x=0; x < f.elements.length; x++){
		/* div errors */
		try{
			var d = document.getElementById('err_' + f.elements[x].id);
			if( (f.elements[x].type == 'checkbox' || f.elements[x].type == 'radio') ){
				if( !f.elements[x].checked ){
					d.setAttribute('style', 'display:block !important');
					r = false;
				}else d.setAttribute('style', 'display:none !important');
			}else{
				if( f.elements[x].value == '' || f.elements[x].value == f.elements[x].title || ( String(f.elements[x].id).substr(0, 8) == 't_email' && !verifyEmail(f.elements[x]) ) ){
					d.setAttribute('style', 'display:block !important');
					r = false;
				}else d.setAttribute('style', 'display:none !important');
			}
		}catch(e){}
	}
	//if(!r) a.href=at;
	return r;
}

function greylayer(framex, W, H, CLOSE){
	if(typeof W === 'undefined') W = 250;
	if(typeof H === 'undefined') H = 100;
	if(typeof CLOSE === 'undefined') CLOSE = false;
	if(framex=='')TINY.box.show({iframe:'template/waiting.html',boxid:'frameless',width:W,height:H,fixed:true,maskid:'greymask',maskopacity:40,close:CLOSE})
	else TINY.box.show({iframe:framex,boxid:'frameless',width:W,height:H,fixed:true,maskid:'greymask',maskopacity:40,close:CLOSE})
}

function switch_control(ob){	
	if(ob.attr('type') == 'checkbox' || ob.attr('type') == 'radio'){
		var onclick = ob.attr('onclick') +';check_this(this);return false';
		var kelas = ob.attr('class');
		if(ob.is(':checked')){
			ob.after('<input type="image" src="images/'+ob.attr('type')+'-checked.png" id="img_'+ob.attr('id')+'" class="'+kelas+'" value="'+ob.val()+'" onclick="'+onclick+'"  />');
		}else 
			ob.after('<input type="image" src="images/'+ob.attr('type')+'-.png" id="img_'+ob.attr('id')+'" class="'+kelas+'" value="'+ob.val()+'" onclick="'+onclick+'" />');
		
	}
}

function string_cbox_id(ob){
	var ai = String(ob.id).split('_'), i = '';
	for(var x=1; x<ai.length; x++) i += ai[x] + '_';
	return String(i).substr(0, i.length-1);
}

function check_this(ob, m){	
	var cr = document.getElementById( string_cbox_id(ob) );
	if(typeof m === 'undefined'){
		if(cr.checked){
			cr.checked=false;
			ob.setAttribute('src', 'images/'+cr.type+'-.png');		
		}else {
			cr.checked=true;
			ob.setAttribute('src', 'images/'+cr.type+'-checked.png');
		}
	}else{
		var checked = m ? 'checked' : '';
		cr.checked=m;
		ob.setAttribute('src', 'images/'+cr.type+'-'+checked+'.png');	
	}
}

function css_append(css){
	var head = document.head || document.getElementsByTagName('head')[0],
		style = document.createElement('style');	
	style.type = 'text/css';
	if (style.styleSheet)
		style.styleSheet.cssText = css;
	else 
		style.appendChild(document.createTextNode(css));
	head.appendChild(style);
}


function waktu_sekarang(){
	var d = new Date();
	var arr_bulan = new Array('Jan', 'Feb' , 'Mar' , 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des');
	return d.getDate() + ' ' + arr_bulan[ d.getMonth() ] + ' ' + d.getFullYear() + ' ' + d.getHours() +':'+ d.getMinutes() + ':' + d.getSeconds();
}

function ubah_warna_tombol_disabled(b){
	b.style.backgroundColor='#CCC';
	b.style.color = '#000';
}

function disabled_tombol_transaksi(){
	var b = document.getElementsByTagName('input');
	for( var x=0; x < b.length; x++ )	
		if( b[x].className == 'tombol-harus-difilter' ) {						
			if( b[x].id == 'b_kembali' )
				b[x].setAttribute('onclick', 'history.back()');
			else{
				b[x].disabled = true; ubah_warna_tombol_disabled( b[x] );
			}
		}
}

function sembunyikan_kotak_loading(){
	var i = document.getElementsByTagName('img');
	for( var x=0; x < i.length; x++ )	if( i[x].className == 'kotak-loading' ) {i[x].setAttribute('style', 'display:none');}
}

function ubah_ke_tampilan_loading(i){	
	document.getElementById('kontainer_utama').setAttribute('style', 'display:none');
	
	var divx = document.createElement('div');
	divx.setAttribute('id', 'kontainer_status');
	divx.setAttribute('style', 'display:block; float:left; width:100%; font-size:10px; line-height:17px;');
	document.body.appendChild(divx);
	var isi = '<div style="float:right; width:277px; text-align:center"><img src="images/victor-loading.gif" style="width:100%" id="img_progress" />';
	isi += '<h2 id="label_progress" style="color:red; text-decoration:underline"></h2></div>';
	isi += '<strong style="font-size:12px">Rileks dulu bro sambil pantengin prosesnya, asal jangan usil refresh ato tutup halaman waktu masih proses ya..!</strong><br /><br />'
	isi += '['+ waktu_sekarang() +'] '+ i +'<img src="images/loading_box.gif" style="border:none; height:7px" class="kotak-loading" /><br />'
	divx.innerHTML = isi;	
}

function jalankan_proses_di_iframe(url){
	var frm = document.createElement('iframe');
	frm.setAttribute('name', 'frm_proses');
	frm.setAttribute('style', 'display:none; width:300px; height:300px');
	document.body.appendChild(frm);
	document.forms[0].action = url;
	document.forms[0].target = frm.name;
	document.forms[0].submit();		
}

function tunjukkan_semua_item_paket(){
	var div = document.getElementsByTagName('div');
	for( var x= 0; x < div.length; x++ ){
		var id = String(div[x].id).split('_');
		if( id[0] == 'daftarpaket' )	div[x].setAttribute('style', 'display: block');
	}	
}

function filter_item_paket(paketid){
	var div = document.getElementsByTagName('div');
	for( var x= 0; x < div.length; x++ ){
		var id = String(div[x].id).split('_');
		if( id[0] == 'daftarpaket' ){
			var display = id[2] == paketid ? 'block' : 'none';
			div[x].setAttribute('style', 'display:' + display);
		}
	}
}

function cek(ob){
	// semua item
	var b = document.getElementById('b_set_qty'), db = 'none'
	if( ob.checked ) db = 'block';
	b.setAttribute('style', 'display:' +db) 
	var mi = document.getElementById('jumlah_item_campaign_noncampaign').value, counter = 0;
	for(var x = 1; x < x+2; x++){
		try{					
			if( counter >= mi ) break;
			var cb = document.getElementById('b_cb_' + x)
			cb.checked = ob.checked
			ubah_warna( x )
			munculkan_isian_kuantitas( cb, x )
			if( !cb.checked ) document.getElementById('q_' + x).value = 1
			counter++;
		}catch(e){}
		
	}
}

function copy_qty(){
	if( !confirm('Akan mengeset semua kuantitas diskon yang diajukan sama dengan kuantitas order?') ) return false;
	var mi = document.getElementById('jumlah_item_campaign_noncampaign').value, counter = 0;
	for(var x = 1; x < x+2; x++){
		try{
			if( counter >= mi ) break;
			var cb = document.getElementById('b_cb_' + x)
			var q = document.getElementById('q_' + x)
			var qc = document.getElementById('q_copy_' + x)
			if( cb.checked ){
				q.value = qc.value
			}else q.value = 1;
			counter++;
		}catch(e){}
	}
}