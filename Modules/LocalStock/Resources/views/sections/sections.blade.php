@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;"> قائمة الأقسام الفرعية <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
              <a href="{{route('addsection')}}" class="btn btn-primary" style="float: left;">
                     إضافة قسم فرعي جديد 
                     <i class="fas fa-plus"></i>
                </a>
                <a href="{{route('addsections')}}" class="btn btn-primary" style="float: left; margin-left:20px">
                     إضافة قسم فرعي لاكثر من قطاع 
                     <i class="fas fa-plus"></i>
                </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>إسم القسم</th>
                  <th>عدد المنتجات</th>
                  <th>الترتيب</th>
                  <th>التاريخ</th>
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody>
                @if(!is_null($sections))
                  @foreach($sections as $key => $value)
                      <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$value->name}}</td>
                        <td>{{count($value->LocalStockMembers)}}</td>
                        <td> 
                        <form action="{{route('localsubnumsort')}}" class="sort{{$value->id}}" method="post" style="display: inline-block;">
                            {{csrf_field()}}
                            <input type="hidden" name="id" value="{{$value->id}}">
                            <input type="number" name="sort" value="{{$value->sort}}" data-id= "{{$value->id}}" style="height: 30px;">
                            <button class="btn btn-success sort" type="submit"  data-id= "{{$value->id}}"  style="height: 30px;"> <i class="fas fa-check"></i></button>
                        </form>
                        </td>
                        <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                        <td>
                        <a href="{{route('Editsection',$value->id)}}" class="btn btn-primary btn-sm " type="submit">  تعديل <i class="fas fa-edit"></i></a>
                        <a href="{{route('showMember',$value->id)}}" class="btn btn-primary btn-sm " type="submit">   المشتركين فالقسم <i class="fas fa-eye"></i></a>
                          <form action="{{route('deletelocalsection')}}" method="post" style="display: inline-block;">
                              {{csrf_field()}}
                              <input type="hidden" name="id" value="{{$value->id}}">
                              <button class="btn btn-danger btn-sm delete" type="submit">  حذف <i class="fas fa-trash"></i></button>
                          </form>
                        </td>
                      </tr>
                  @endforeach
                @endif
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
        {{--warning--}}
        <div class="modal fade" id="modal-secondary">
          <div class="modal-dialog">
          <div class="modal-content bg-secondary">
            <div class="modal-body">
            <p>هذه الصفحة خاصة  بجميع الاقسام الفرعية</p>
            </div>
          </div>
          </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
    // add section
   

    $('.save').on('click',function(){
        $('#submit').click();
    })

  


</script>
@endsection

