@extends('layouts.app')

@section('style')
<style type="text/css">
	.canvasjs-chart-canvas{
		right: 0 !important;
		width: 100% !important;
		height: 400px !important;
	   }
	#avatar{
		width: 150px;
		height: 117px;
	}
	#avatar:hover{
		width: 150px;
		height: 117px;
		cursor: pointer;
	}

</style>


@endsection

@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="card card-primary card-outline">
			<div class="card-header">
				<h5 class="m-0" style="display: inline;">إحصائيات بعدد إستخدام الكلمات الدلالية </h5>
				<input type="date" name="date" id="date" class="form-control"  style="width: 15%;float:left">
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<div id="chartContainer" style="height: 400px; width: 100%;"></div>
			</div>
		<!-- /.card-body -->
		</div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
	window.onload = function () {
		var chart = new CanvasJS.Chart("chartContainer", {
		  animationEnabled: true,
		  exportEnabled: true,
		  theme: "light1", // "light1", "light2", "dark1", "dark2"
		  title:{
			text: ""
		  },
		  axisY:{
			includeZero: true
		  },
		  data: [{
			type: "bar", //change type to column, bar, line, area, pie, etc
			indexLabel: "{x}", //Shows y value on all Data Points
			indexLabelFontColor: "#fff",
		   // indexLabelPlacement: "inside",  // outside ,inside
			dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?> }]
		});
		chart.render();   
	}
	
		//get by date
		$(document).on('change','#date',function(){
		var date = $(this).val();
		var id = {{$customer->id}}
		$.ajax(
		{
			url: "{{route('dataajaxCustomer')}}",
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				"date": date,
				"id": id,
			},
			success: function (d){
				var chart = new CanvasJS.Chart("chartContainer", {
				animationEnabled: true,
				exportEnabled: true,
				theme: "light3", // "light1", "light2", "dark1", "dark2"
				title:{
					text: ""
				},
				axisY:{
					includeZero: true
				},
				data: [{
					type: "bar", //change type to column, bar, line, area, pie, etc
					indexLabel: "{y}", //Shows y value on all Data Points
					indexLabelFontColor: "#5A5757",
					indexLabelPlacement: "outside",   
					dataPoints: d
				}]
				});
				chart.render();  
			}
			});
		})


	
</script>
@endsection


