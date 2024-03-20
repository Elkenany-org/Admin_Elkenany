@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">قائمة الكلمات الدلالية </h5>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">
                     إضافة كلمة دلالية 
                     <i class="fas fa-plus"></i>
                </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>الإسم</th>
                  <th>keyword</th>
                  <th>type</th>
                  <th>عدد الإستخدام</th>
                  <th>التاريخ</th>
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody>
                @foreach($keywords as $key => $value)
                    <tr>
                      <td>{{$value->id}}</td>
                      <td>{{$value->name}}</td>
                      <td>{{$value->keyword}}</td>
                        @if($value->keyword == 'guide')
                            @foreach($sections_guide as $sec)
                                @if($sec->id == $value->type)
                                    <td>{{$sec->name }}</td>
                                @endif
                            @endforeach

                        @elseif($value->keyword == 'tenders')
                            @foreach($sections_tenders as $sec)
                                @if($sec->id == $value->type)
                                    <td>{{$sec->name }}</td>
                                @endif
                            @endforeach



                        @elseif($value->keyword == 'news')
                            @foreach($sections_news as $sec)
                                @if($sec->id == $value->type)
                                    <td>{{$sec->name }}</td>
                                @endif
                            @endforeach


                        @elseif($value->keyword == 'jobs')
                            @foreach($sections_jobs as $sec)
                                @if($sec->id == $value->type)
                                    <td>{{$sec->name }}</td>
                                @endif
                            @endforeach
                        @elseif($value->keyword == 'magazine')
                            @foreach($sections_magazines as $sec)
                                @if($sec->id == $value->type)
                                    <td>{{$sec->name }}</td>
                                @endif
                            @endforeach
                        @elseif($value->keyword == 'stock')
                            @foreach($sections_localstock as $sec)
                                @if($sec->id == $value->type)
                                    <td>{{$sec->name }}</td>
                                @endif
                            @endforeach
                        @elseif($value->keyword == 'store')
                            @foreach($sections_store as $sec)
                                @if($sec->id == $value->type)
                                    <td>{{$sec->name }}</td>
                                @endif
                            @endforeach

                        @elseif($value->keyword == 'show')
                            @foreach($sections_shows as $sec)
                                @if($sec->id == $value->type)
                                    <td>{{$sec->name }}</td>
                                @endif
                            @endforeach
                        @elseif($value->keyword == 'shows')
                            @foreach($sections_shows as $sec)
                                @if($sec->id == $value->type)
                                    <td>{{$sec->name }}</td>
                                @endif
                            @endforeach
                        @else
                            <td>{{$value->type }}</td>
                        @endif
                      <td>{{$value->use_count}}</td>
                      <td> <span class="badge badge-success">{{Date::parse($value->created_at)->format('h:m / Y-m-d')}}</span></td>
                      <td>
                        <!-- <a href="" 
                        class="btn btn-primary btn-sm edit"
                        data-toggle="modal"
                        data-target="#modal-update"
                        data-id      = "{{$value->id}}"
                        data-name    = "{{$value->name}}"
                        data-keyword = "{{$value->keyword}}"
                        >  تعديل <i class="fas fa-eye"></i></a> -->
                        <a href="{{route('currentkeywordstatistics',$value->id)}}" class="btn btn-info btn-sm ">إحصائيات <i class="fas fa-chart-bar"></i></a>
                        <form action="{{route('deletekeyword')}}" method="post" style="display: inline-block;">
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

          <div class="row text-center">
            <div class="col-sm-12 text-center">
              {{$keywords->links()}}
            </div>
          </div>
          
        </div>

        {{-- add modal --}}
      <div class="modal fade" id="modal-primary">
        <div class="modal-dialog">
          <div class="modal-content bg-primary">
                <div class="modal-header">
                <h4 class="modal-title">إضافة كلمة دلالية</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                <form action="{{route('storekeyword')}}" method="post" enctype="multipart/form-data">
                    <div class="row">
                        {{csrf_field()}}
                        <div class="col-sm-6">
                            <label> الإسم</label> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control" placeholder="الإسم" required="" style="margin-bottom: 10px">
                        </div>
{{--                        <div class="col-sm-6">--}}
{{--                            <label> keyword</label> <span class="text-danger">*</span>--}}
{{--                            <input type="text" name="keywords" class="form-control" placeholder="keywords" required="" style="margin-bottom: 10px">--}}
{{--                        </div>--}}
                        <div class="col-sm-6">
                            <label>اختر الكلمة الدلالية</label> <span class="text-danger">*</span>
                            <select name="keywords" class="form-control keywords" required="" >
                                <option value="" disabled selected>إختيار </option>
                                @foreach($keywords_of_services as $value)
                                    <option value="{{$value->type}}">{{$value->type}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 type guide"  >
                          <label> اختر القسم في الدليل</label> <span class="text-danger">*</span>
                          <select name="type" class="form-control">
                              <option value="" disabled selected>إختيار </option>
                              @foreach($sections_guide as $value)
                                <option value="{{$value->id}}">{{$value->name}}</option>
                              @endforeach
                          </select>
                        </div >
                        <div class="col-sm-12 type shows" >
                            <label> اختر القسم في المعارض</label> <span class="text-danger">*</span>
                            <select name="type" class="form-control">
                                <option value="" disabled selected>إختيار </option>
                                @foreach($sections_shows as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 type store"  >
                            <label> اختر القسم للسوق</label> <span class="text-danger">*</span>
                            <select name="type" class="form-control">
                                <option value="" disabled selected>إختيار </option>
                                @foreach($sections_store as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 type news"  >
                            <label> اختر القسم في الاخبار</label> <span class="text-danger">*</span>
                            <select name="type" class="form-control">
                                <option value="" disabled selected>إختيار </option>
                                @foreach($sections_news as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 type stock"  >
                            <label> اختر القسم في البورصة المحلية</label> <span class="text-danger">*</span>
                            <select name="type" class="form-control">
                                <option value="" disabled selected>إختيار </option>
                                @foreach($sections_localstock as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 type stock" >
                            <label> اختر القسم في بورصة الاعلاف</label> <span class="text-danger">*</span>
                            <select name="type" class="form-control">
                                <option value="" disabled selected>إختيار </option>
                                @foreach($sections_fodderstock as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 type magazine"  >
                            <label> اختر القسم في المجلات </label> <span class="text-danger">*</span>
                            <select name="type" class="form-control">
                                <option value="" disabled selected>إختيار </option>
                                @foreach($sections_magazines as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 type tenders"  >
                            <label> اختر القسم في المناقصات </label> <span class="text-danger">*</span>
                            <select name="type" class="form-control">
                                <option value="" disabled selected>إختيار </option>
                                @foreach($sections_tenders as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 type jobs"  >
                            <label> اختر القسم في الوظائف </label> <span class="text-danger">*</span>
                            <select name="type" class="form-control">
                                <option value="" disabled selected>إختيار </option>
                                @foreach($sections_jobs as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" id="submit" style="display: none;"></button>
                    </div>
                </form>
                </div>
                <div class="modal-footer justify-content-between">

                <button type="button" class="btn btn-outline-light save">حفظ</button>
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
                </div>
          </div>
        </div>
      </div>


        {{-- edit modal --}}
      <div class="modal fade" id="modal-update">
        <div class="modal-dialog">
          <div class="modal-content bg-info">
            <div class="modal-header">
              <h4 class="modal-title"> تعديل : <span class="item_name"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
               <form action="{{route('updatekeyword')}}" method="post"> 
                     {{csrf_field()}} 
                     <input type="hidden" name="edit_id" >
                    <div class="row">
                        <div class="col-sm-6">
                            <label> الإسم</label> <span class="text-danger">*</span>
                            <input type="text" name="edit_name" id="edit_name" class="form-control" required="" style="margin-bottom: 10px" required>
                        </div>
                        <div class="col-sm-6">
                            <label> keyword</label> <span class="text-danger">*</span>
                            <input type="text" name="edit_keyword" id="edit_keyword" class="form-control" required="" style="margin-bottom: 10px" required>
                        </div>
                    </div>
                    <button type="submit" id="update" style="display: none;"></button>
               </form> 
            </div>
            <div class="modal-footer justify-content-between">
               <button type="button" class="btn btn-outline-light update">تحديث</button> 
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
            </div>
          </div>
        </div>
      </div>

@endsection

@section('script')
<script type="text/javascript">

    $(document).ready(function(){
        $(".keywords").change(function(){
            $(this).find("option:selected").each(function(){
                var optionValue = $(this).attr("value");
                if(optionValue){
                    $(".type").not("." + optionValue).hide();
                    $("." + optionValue).show();
                } else{
                    $(".type").hide();
                }
            });
        }).change();
    });
    // add
    $('.save').on('click',function(){
        $('#submit').click();
    })

    function ChooseAvatar(){$("input[name='image']").click()}
	var loadAvatar = function(event) {
		var output = document.getElementById('avatar');
		output.src = URL.createObjectURL(event.target.files[0]);
	};


    //edit 
    $('.edit').on('click',function(){
        var id            = $(this).data('id')
        var name          = $(this).data('name')
        var keyword       = $(this).data('keyword')
        
        $('.item_name').text(name)
        $("input[name='edit_id']").val(id)
        $("input[name='edit_name']").val(name)
        $("input[name='edit_keyword']").val(keyword)


    })

    // update section
    $('.update').on('click',function(){
        $('#update').click();
    })
</script>
@endsection

