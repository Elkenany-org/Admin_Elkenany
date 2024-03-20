@extends('layouts.app')

@section('content')
    <div class="row">
      <div class="col-sm-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;"> بيانات الطلب <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

            <div class="card card-widget widget-user-2">
            
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-info">
                  <div class="widget-user-image">
                    <img class="img-circle elevation-2" src="{{asset('uploads/customers/avatar/'.$order->Customer->avatar)}}" alt="User Avatar">
                  </div>
                  <!-- /.widget-user-image -->
                  <h3 class="widget-user-username" style="color: #fff">{{$order->Customer->name}}</h3>
                  <p>الاستشاري : <span class="badge bg-danger">{{$order->Doctor->name}}</span></p>
                </div>
                <div class="card-footer p-0">
                  <ul class="nav flex-column">
                    <li class="nav-item" style="margin: 12px">
                        الوقت من : <span class="float-right badge bg-primary">{{$order->DoctorServices->time_from}}</span>
                    </li>
                    <li class="nav-item" style="margin: 12px">
                        الوقت الي: <span class="float-right badge bg-primary">{{$order->DoctorServices->time_to}}</span>
                    </li>
                    <li class="nav-item" style="margin: 12px">
                        التاريخ <span class="float-right badge bg-info">{{$order->DoctorServices->date}}</span>
                    </li>
                    <li class="nav-item" style="margin: 12px">
                        النوع <span class="float-right badge bg-info">{{$order->DoctorServices->services_type}}</span>
                    </li>
                    <li class="nav-item" style="margin: 12px">
                        الحالة 
                        @if($order->status === 0)
                        <span class="float-right badge bg-primary">جاري</span>
                        @endif

                        @if($order->status === 1)
                        <span class="float-right badge bg-success">مقبول</span>
                        @endif

                        @if($order->status === 2)
                        <span class="float-right badge bg-danger">مرفوض</span>
                        @endif
                    </li>
                  </ul>
                </div>
            </div>
            {{--warning--}}
            <div class="modal fade" id="modal-secondary">
              <div class="modal-dialog">
              <div class="modal-content bg-secondary">
                <div class="modal-body">
                <p>هذه الصفحة خاصة بببيانات الطلب</p>
                </div>
              </div>
              </div>
            </div>
      </div>
    </div>
  </div>
</div>
        
@endsection

@section('script')
<script type="text/javascript">

</script>
@endsection

