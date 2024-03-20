@extends('layouts.app')

@section('content')

<div class="container-fluid">
<div class="card card-primary card-outline">

<div class="card-body">
<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">

	{{-- show --}}
	<li class="nav-item">
	<a class="nav-link active" id="custom-content-below-show-tab" data-toggle="pill" href="#custom-content-below-show" role="tab" aria-controls="custom-content-below-show" aria-selected="true">بيانات المنشور</a>
	</li>

	{{-- order --}}
	<li class="nav-item">
	<a class="nav-link" id="custom-content-below-order-tab" data-toggle="pill" href="#custom-content-below-order" role="tab" aria-controls="custom-content-below-order" aria-selected="false">طلبات المنشور</a>
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
              <h5 class="m-0" style="display: inline;"> بيانات المنشور </h5>
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
                              <td> المُزارع</td>
                              <td><a href="{{route('Editfarmer',$post->WaferFarmer->id)}}">{{$post->WaferFarmer->name}}</a></td>
                            </tr>

                            <tr>
                              <td>  البريد</td>
                              <td>{{$post->WaferFarmer->email}}</td>
                            </tr>

                            <tr>
                              <td>التلفون</td>
                              <td>{{$post->WaferFarmer->phone}}</td>
                            </tr>

                            <tr>
                              <td>  العنوان</td>
                              <td>{{$post->WaferFarmer->address}}</td>
                            </tr>

                            <tr>
                              <td>  اسم المزرعة</td>
                              <td>{{$post->WaferFarmer->farm_name}}</td>
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
                              <td> القسم</td>
                              <td>{{$post->Section->name}}</td>
                            </tr>

                            <tr>
                              <td>  النوع</td>
                              <td>{{$post->item_type}}</td>
                            </tr>

                            <tr>
                              <td>العمر</td>
                              <td>{{$post->item_age}}</td>
                            </tr>

                            <tr>
                              <td>  وزن الحمولة</td>
                              <td>{{$post->average_weight}}كجم</td>
                            </tr>

                            <tr>
                              <td>   السعر</td>
                              <td>{{$post->price}}جنيه</td>
                            </tr>

                            <tr>
                              <td>   العنوان</td>
                              <td>{{$post->address}}</td>
                            </tr>

                            <tr>
                              <td>   الحالة</td>
                              <td> @if($post->status === 0)
                              <span class="badge bg-primary">انتظار</span>
                              @endif

                              @if($post->status === 1)
                              <span class="badge bg-info">جاري</span>
                              @endif

                              @if($post->status === 2)
                              <span class="badge bg-success">منتهي</span>
                              @endif
                              </td>
                            </tr>

                            <tr>
                              <td>   التاريخ</td>
                              <td>{{$post->date_of_sale}}</td>
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

{{-- order --}}
<div class="tab-pane fade" style="padding-top: 20px;" id="custom-content-below-order" role="tabpanel" aria-labelledby="custom-content-below-order-tab">
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
                  <th>الاسم</th>
                  <th>اسم المزرعة</th>
                  <th> الحالة</th>
                  <th> التاريخ</th>
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody>
                @foreach($post->WaferOrders as $key => $value)
	                <tr>
	                  <td>{{$key+1}}</td>
                    <td>{{$value->Customer->name}}</td>
	                  <td>{{$value->WaferFarmer->name}}</td>
                    <td>
                    @if($value->status === 0)
                        <span class="badge bg-primary">انتظار</span>
                        @endif

                        @if($value->status === 1)
                        <span class="badge bg-success">جاري</span>
                        @endif

                        @if($value->status === 2)
                        <span class="badge bg-danger">مرفوض</span>
                        @endif
                    </td>
                    <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                    <td>
                    <a href="{{route('Showorder',$value->id)}}" class="btn btn-primary btn-sm " type="submit">  بيانات الطلب <i class="fas fa-eye"></i></a>
	                  	<form action="{{route('Deleteorder')}}" method="post" style="display: inline-block;">
	                  		{{csrf_field()}}
	                  		<input type="hidden" name="id" value="{{$value->id}}">
	                  		<button class="btn btn-danger btn-sm delete" type="submit">  حذف <i class="fas fa-trash"></i></button>
	                  	</form>
	                  </td>
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