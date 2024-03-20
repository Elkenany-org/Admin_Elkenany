
////image
// Multiple images preview in browser
var imagesPreview = function(input, placeToInsertImagePreview) {

	if (input.files) {
		var filesAmount = input.files.length;

		for (i = 0; i < filesAmount; i++) {
			var reader = new FileReader();

			reader.onload = function(event) {
				$($.parseHTML('<img class="img-fluid mb-2  bounceIn">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
			}

			reader.readAsDataURL(input.files[i]);
		}
	}

};

$('#gallery1').on('change', function() {
	imagesPreview(this, 'div.gallery');
});



  //////


  function ChooseAvatar(){$("input[name='image']").click()}
var loadAvatar = function(event) {
	var output = document.getElementById('avatar');
	output.src = URL.createObjectURL(event.target.files[0]);
};

$(document).on('click','.add_mobiles',function(){
	$('.mobiles').append(
		`
		<div class="col-sm-12 father${Date.now()}" style="margin-top:10px">
			<div class="row">
				<div class="col-sm-8" style="padding-left: 5px ">
					<input type="text" name="mobiles[]" class="form-control" placeholder="الموبايل" >
				</div>
				<div class="col-sm-1" style="padding: 0 ">
					<button type="button" class="btn btn-danger btn-block remove_mobiles" data-code="${Date.now()}">
						<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
					</button>
				</div>
			</div>
		</div>
		`
	);
})

$(document).on('click','.remove_mobiles',function(){
	var cla = '.father'+$(this).data('code');
	$(cla).remove();
})

$(document).on('click','.add_faxs',function(){
	$('.faxs').append(
		`
		<div class="col-sm-12 father${Date.now()}" style="margin-top:10px">
			<div class="row">
				<div class="col-sm-8" style="padding-left: 5px ">
					<input type="text" name="faxs[]" class="form-control" placeholder="الفاكس" >
				</div>
				<div class="col-sm-1" style="padding: 0 ">
					<button type="button" class="btn btn-danger btn-block remove_faxs" data-code="${Date.now()}">
						<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
					</button>
				</div>
			</div>
		</div>
		`
	);
})

$(document).on('click','.remove_faxs',function(){
	var cla = '.father'+$(this).data('code');
	$(cla).remove();
})

$(document).on('click','.add_phones',function(){
	$('.phones').append(
		`
		<div class="col-sm-12 father${Date.now()}" style="margin-top:10px">
			<div class="row">
				<div class="col-sm-8" style="padding-left: 5px ">
					<input type="text" name="phones[]" class="form-control" placeholder="الجوال" >
				</div>
				<div class="col-sm-1" style="padding: 0 ">
					<button type="button" class="btn btn-danger btn-block remove_phones" data-code="${Date.now()}">
						<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
					</button>
				</div>
			</div>
		</div>
		`
	);
})

$(document).on('click','.remove_phones',function(){
	var cla = '.father'+$(this).data('code');
	$(cla).remove();
})

$(document).on('click','.add_emails',function(){
	$('.emails').append(
		`
		<div class="col-sm-12 father${Date.now()}" style="margin-top:10px">
			<div class="row">
				<div class="col-sm-8" style="padding-left: 5px ">
					<input type="text" name="emails[]" class="form-control" placeholder=" البريد" >
				</div>
				<div class="col-sm-1" style="padding: 0 ">
					<button type="button" class="btn btn-danger btn-block remove_emails" data-code="${Date.now()}">
						<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
					</button>
				</div>
			</div>
		</div>
		`
	);
})

$(document).on('click','.remove_emails',function(){
	var cla = '.father'+$(this).data('code');
	$(cla).remove();
})

$(document).on('click','.store_food',function(){
	$('.add_real').click();
})


