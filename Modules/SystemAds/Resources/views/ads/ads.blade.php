@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">قائمة الاعلانات <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
              <!-- <a href="{{route('Addsystemads')}}" class="btn btn-primary" style="float: left;">
                     إضافة اعلان 
                     <i class="fas fa-plus"></i>
                </a> -->
            
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>الإسم</th>
                  <th>النوع</th>
                  <th>الحالة</th>
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody>
                @foreach($ads as $key => $value)
	                <tr>
	                  <td>{{$key+1}}</td>
	                 
	                  <td>{{$value->AdsUser->name}}</td>
	                  <td>{{$value->type}}</td>
                    <td>
											@if($value->status === '0')
											جاري المراجعة
											@elseif($value->status === '1')
                       موافقة
											@elseif($value->status === '2')
                      غير موافقة 

                      @elseif($value->status === '3')
                      غير نشط 
											@endif
											
										</td>
                    <td>
		                <a href="{{route('Editadss',$value->id)}}" class="btn btn-primary btn-sm " type="submit">  تعديل <i class="fas fa-edit"></i></a>
	                 
	                  </td>
	                </tr>
                @endforeach
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          {{--warning--}}
        <div class="modal fade" id="modal-secondary">
        <div class="modal-dialog">
        <div class="modal-content bg-secondary">
          <div class="modal-body">
          <p>هذه الصفحة خاصة   بالاعلانات</p>
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