@extends('layouts.app')

@section('content')

<div class="container-fluid">
<div class="card card-primary card-outline">

<div class="card-body">
<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">

	{{-- show --}}
	<li class="nav-item">
	<a class="nav-link active" id="custom-content-below-show-tab" data-toggle="pill" href="#custom-content-below-show" role="tab" aria-controls="custom-content-below-show" aria-selected="true">بيانات الطلب</a>
	</li>

	{{-- car --}}
	<li class="nav-item">
	<a class="nav-link" id="custom-content-below-car-tab" data-toggle="pill" href="#custom-content-below-car" role="tab" aria-controls="custom-content-below-car" aria-selected="false"> سيارات الطلب</a>
	</li>


</ul>
</div>

<div class="tab-content" id="custom-content-below-tabContent">
{{-- show --}}
<div class="tab-pane fade show active" style="padding-top: 20px;"  id="custom-content-below-show" role="tabpanel" aria-labelledby="custom-content-below-show-tab">
<div class="row">
      <div class="col-sm-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;"> بيانات الطلب </h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <div class="row">
                  <div class="col-md-6">
                  <div class="card card-outline">
                  <div class="card-body">
                    <div class="panel panel-flat">
                      <div class="panel-body">
                        <table class="table table-striped">
                          <tbody>
                            <tr>
                              <td> التاجر</td>
                              <td><a href="{{route('eidtcustomer',$order->Customer->id)}}">{{$order->Customer->name}}</a></td>
                            </tr>

                            <tr>
                              <td>  البريد</td>
                              <td>{{$order->Customer->email}}</td>
                            </tr>

                            <tr>
                              <td>التلفون</td>
                              <td>{{$order->Customer->phone}}</td>
                            </tr>

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  </div>
                  </div>

                  <div class="col-md-6">
                  <div class="card card-outline">
                  <div class="card-body">
                    <div class="panel panel-flat">
                      <div class="panel-body">
                        <table class="table table-striped">
                          <tbody>
                            <tr>
                              <td> اسم المزرعة</td>
                              <td><a href="{{route('Editfarmer',$order->WaferFarmer->id)}}">{{$order->WaferFarmer->farm_name}}</a></td>
                            </tr>

                            <tr>
                              <td>  عمولة التطبيق</td>
                              <td>{{$order->app_commission}}</td>
                            </tr>

                            <tr>
                              <td>الدفع</td>
                              <td>{{$order->payment}}</td>
                            </tr>
                            <tr>
                              <td>الحالة</td>
                              <td>
                              @if($order->status === 0)
                              <span class="badge bg-primary">انتظار</span>
                              @endif

                              @if($order->status === 1)
                              <span class=" badge bg-success">مقبول</span>
                              @endif

                              @if($order->status === 2)
                              <span class="badge bg-danger">مرفوض</span>
                              @endif
                              </td>
                            </tr>
                            
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  </div>
                  </div>

                </div>
      </div>
    </div>
  </div>
</div>
</div>

{{-- car --}}
<div class="tab-pane fade" style="padding-top: 20px;" id="custom-content-below-car" role="tabpanel" aria-labelledby="custom-content-below-car-tab">
<div class="row">
		<div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">قائمة الطلبات</h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>اسم السائق</th>
                  <th> رقم السائق</th>
                  <th> رقم السيارة</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->WaferCars as $key => $value)
	                <tr>
	                  <td>{{$key+1}}</td>
                    <td>{{$value->name}}</td>
	                  <td>{{$value->phone}}</td>
                    <td>{{$value->car_id}}</td>
	                </tr>
                @endforeach
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
		</div>
	</div>

</div>

</div>
</div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">

</script>
@endsection