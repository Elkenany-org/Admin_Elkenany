//datatable
	$(document).ready(function(){
	    $("#example1").DataTable({
	      "paging": true,
	      "lengthChange": true,
	      "searching": true,
	      "ordering": true,
	      "info": true,
	      "autoWidth": true,
	    });
	})

$('.delete').on('click',function(e){
	var result = confirm('هل تريد استمرار عملية الحذف ؟ ')
	if(result == false)
	{
		e.preventDefault()
	}
})

//loading
$('.real_content').css('display','none')
$('.loading').css('display','block')
$(document).ready(function(){
	$('.real_content').css('display','block')
	$('.loading').css('display','none')
})

//toast
  // toastr.options.closeMethod = 'slideUp';
  // toastr.options.timeOut = 90000;
  // toastr.options.extendedTimeOut = 90000;
  // toastr.options.closeEasing = 'swing';