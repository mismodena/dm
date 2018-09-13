/* Indonesian initialisation for the jQuery UI date picker plugin. */
/* Written by Deden Fathurahman (dedenf@gmail.com). */
jQuery(function($){
	$.datepicker.regional['id'] = {
		closeText: 'Tutup',
		prevText: '&#x3c;sebelumnya',
		nextText: 'berikutnya&#x3e;',
		currentText: 'hari ini',
		monthNames: ['Januari','Februari','Maret','April','Mei','Juni',
		'Juli','Agustus','September','Oktober','November','Desember'],
		monthNamesShort: ['Jan','Feb','Mar','Apr','Mei','Jun',
		'Jul','Agu','Sep','Okt','Nov','Des'],
		dayNames: ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'],
		dayNamesShort: ['Min','Sen','Sel','Rab','Kam','Jum','Sab'],
		dayNamesMin: ['Mg','Sn','Sl','Rb','Km','Jm','Sb'],
		dateFormat: 'dd/mm/yy', firstDay: 0,
		isRTL: false};
	$.datepicker.setDefaults($.datepicker.regional['id']);
});