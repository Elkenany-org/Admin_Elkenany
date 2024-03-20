@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">قائمة الشركات ل {{$section->name}}  <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
              <a href="{{route('addstocks')}}" class="btn btn-primary" style="float: left;">
                    إضافة صنف لبورصة 
                    <i class="fas fa-plus"></i>
              </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>إسم الشركة</th>
                  <th>التاريخ</th>
                  
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody>
                @foreach($stocks as $key => $value)
                    <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$value->Company->name}}</td>
                      <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                      <td>
                
                      <a href="{{route('stockss',$value->Company->id)}}" class="btn btn-primary btn-sm " type="submit">  مشاهدة <i class="fas fa-eye"></i></a>
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
@endsection

@section('script')
<script type="text/javascript">

</script>
@endsection

