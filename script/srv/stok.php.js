var title = 'Data Persediaan';

function cari_item(){
	var i = document.getElementById('item').value;
	if( i == '' ) {alert('Mohon isikan item yang akan dicari!');return false;}
	location.href='stok.php?item=' + i;
}

function showmodal(item,gudang, x) {
	$("#gudangid").text(gudang);
	var item_name = $(x).parent().parent().children().first().text();
	$.get( "stok.php", { draft: 1, items: item, gudang: gudang} )
		.done(function( data ) {	
			if(data==""){
				$("#s_acc").text(0);
				$("#s_commit").text(0);
				$("#s_free").text(0);
				$("#item-desc").text(item_name);

		    	$("#myTable>tbody").html('');
			}else{
				data = JSON.parse(data);
				$("#s_acc").text(numberWithCommas(data["stock_acc"]));
				$("#s_commit").text(numberWithCommas(data["stock_commit"]));
				$("#s_free").text(numberWithCommas(data["stock_free"]));
				$("#item-desc").text(data["item-desc"]);

		    	$("#myTable>tbody").html(data["item"]);
			}
			
	    	$(".modal-content").css("overflow-y","scroll");
	    	$(".modal-content").css("max-height","600px");
	    	$("#modal-stok").css("display","block");
	});
 	    	    
}

$( document ).ready(function() {
	$('#myTable').DataTable({
    	"bLengthChange": false,
    	"bInfo" : false
    });	
	$(".close").click(function(){ 		
		$("#modal-stok").css("display","none");
	})
})

function numberWithCommas(n) {
    var parts=n.toString().split(".");
    return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
}
