var title = 'Diskon Persetujuan';

try{
	$(document).ready(function(){ubah_link()});
}catch(e){}

function ubah_link(){
	for( var x=1; x<=(x+1); x++){
		try{
			var ob0 = document.getElementById('link_'+ x +'_0');
			var ob1 = document.getElementById('link_'+ x +'_1');
			ob0.href = "javascript:window.open('"+ ob0.href +"');void(0);";
			ob1.href = "javascript:window.open('"+ ob1.href +"');void(0);";
		}catch(e){break;}		
	}
}