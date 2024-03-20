@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">قائمة طلبات العرض </h5>
               
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th> الاسم</th>
                  <th> اسم المعرض</th>
                  <th>التاريخ</th>
                  
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody>
                @foreach($places as $key => $value)
                    <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$value->name}}</td>
                      <td>{{$value->Show->name}}</td>
                      <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                      <td>
                        <a href="" 
                        class="btn btn-info btn-sm edit"
                        data-toggle="modal"
                        data-target="#modal-update"
                        data-id    = "{{$value->id}}"
                        data-name  = "{{$value->name}}"
                        data-company    = "{{$value->company}}"
                        data-email  = "{{$value->email}}"
                        data-phone  = "{{$value->phone}}"
                        data-desc  = "{{$value->desc}}"
                        data-shows  = "{{$value->Show->name}}"
                       
                        >  عرض <i class="fas fa-eye"></i></a>
                       <form action="{{route('deletplaces')}}" method="post" style="display: inline-block;">
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
        {{--warning--}}
       
       
        {{-- edit section modal --}}
      <div class="modal fade" id="modal-update">
        <div class="modal-dialog">
          <div class="modal-content bg-info">
            <div class="modal-header">
              <h4 class="modal-title"> عرض طلب العرض : <span class="item_name"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
         
                    <input type="hidden" name="edit_id" value="">
                    <label>الاسم </label>
                    <input type="text" name="name" class="form-control" readonly style="margin-bottom: 10px">
                    <label> البريد</label>
                    <input type="text" name="email" class="form-control" readonly style="margin-bottom: 10px">
                    <label> التلفون</label>
                    <input type="text" name="phone" class="form-control" readonly style="margin-bottom: 10px">
                    <label> الشركة</label>
                    <input type="text" name="company" class="form-control" readonly style="margin-bottom: 10px">
                    <label>إسم المعرض</label>
                    <input type="text" name="shows" class="form-control" readonly style="margin-bottom: 10px">
                    <label> نص الإشعار </label>
	            			<textarea class="form-control" rows="8" name="desc" readonly style="margin-bottom: 10px"></textarea>

 
            </div>
            <div class="modal-footer justify-content-between">
  
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
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

  


    //edit section
    $('.edit').on('click',function(){
        var email      = $(this).data('email')
        var name       = $(this).data('name')
        var company         = $(this).data('company')
        var shows       = $(this).data('shows')
        var phone         = $(this).data('phone')
        var desc       = $(this).data('desc')
     

     

        
        $('.item_name').text(name)
        $("input[name='email']").val(email)
        $("input[name='name']").val(name)
        $("input[name='company']").val(company)
        $("input[name='shows']").val(shows)
        $("input[name='company']").val(company)
        $("input[name='phone']").val(phone)
        $("textarea[name='desc']").html(desc)
      

       

       
    })

    // update section
    $('.update').on('click',function(){
        $('#update').click();
    })
</script>
@endsection

