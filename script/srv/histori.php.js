var title = 'Data Penjualan';

$(document).ready(function(){
	
	$(function() {
		$("#awal").datepicker({"dateFormat":"d MM yy",  "altField":"#hd_awal", "altFormat": "mm/d/yy"});		
		$("#awal").datepicker($.datepicker.regional['id']);		
		
		$("#akhir").datepicker({"dateFormat":"d MM yy",  "altField":"#hd_akhir", "altFormat": "mm/d/yy"});		
		$("#akhir").datepicker($.datepicker.regional['id']);		
	});	
});	

function cari_order(){
	var awal = document.getElementById('hd_awal').value;
	var akhir = document.getElementById('hd_akhir').value;
	location.href='histori.php?c=cari_order&awal='+ awal +'&akhir' + akhir;
}