var title = 'Campaign Berlaku';

try{
	document.forms[0].method='get';
}catch(e){}

function cari_item(){
	location.href='campaign.php?item=' + document.getElementById('item').value;
}