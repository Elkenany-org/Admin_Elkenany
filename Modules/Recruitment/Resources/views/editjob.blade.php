@extends('layouts.app')

@section('style')
    <style type="text/css">
        #avatar{
            width: 100%;
            height: 300px;
        }
        #avatar:hover{
            width: 100%;
            height: 300px;
            cursor: pointer;
        }
        .marbo{
            margin-bottom: 10px
        }

        .img img{
            width:150px;
            height:150px;
            margin-right:20px;
            margin-top:20px;
        }

        #gallery-photo-add {
            display: inline-block;
            position: absolute;
            z-index: 1;
            width: 100%;
            height: 50px;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }
        .result_style{
            border: 1px solid #12a3b8;
            padding-top: 12px;
            border-radius: 22px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card card-primary card-outline">

            <div class="tab-content" id="custom-content-below-tabContent">

                {{-- edit --}}
                <div class="tab-pane fade show active" id="custom-content-below-show" role="tabpanel" aria-labelledby="custom-content-below-show-tab">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-body">
                                <form action="{{route('storeEditJob')}}" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        {{csrf_field()}}
                                        <input type="hidden" name="id" value="{{$job->id}}">
                                        <div class="col-sm-12" style="margin-top: 10px">
                                            <label class="text-primary">عنوان الوظيفة <span class="text-danger">*</span></label>
                                            <input type="text" disabled class="form-control" value="{{$job->title}}" name="name" placeholder=" عنوان الوظيفة" >
                                        </div>
                                        <div class="col-sm-12" style="margin-top: 10px">
                                            <label class="text-primary">المعلن<span class="text-danger">*</span></label>
                                            <input type="text" disabled class="form-control"  value="{{$recruiter->name}}" name="customer" placeholder=" اسم المعلن" >
                                        </div>
                                        <div class="col-sm-12" style="margin-top: 10px">
                                            <label class="text-primary">البريد الالكتروني<span class="text-danger">*</span></label>
                                            <input type="text" disabled class="form-control" value="{{$job->email}}" name="email" placeholder=" الايميل" >
                                        </div>
                                        <div class="col-sm-12" style="margin-top: 10px">
                                            <label class="text-primary">العنوان<span class="text-danger">*</span></label>
                                            <input type="text" disabled class="form-control" value="{{$job->address}}" name="address" placeholder=" العنوان" >
                                        </div>
                                        <div class="col-sm-12" style="margin-top: 10px">
                                            <label class="text-primary">الخبرة<span class="text-danger">*</span></label>
                                            <input type="text" disabled class="form-control" value="{{$job->experience}}" name="address" placeholder=" الخبرة" >
                                        </div>
                                        <div class="col-sm-12" style="margin-top: 10px">
                                            <label class="text-primary">القسم<span class="text-danger">*</span></label>
                                            <input type="text" disabled class="form-control" value="{{$job_Category->name}}" name="category" placeholder=" القسم" >
                                        </div>
                                        <div class="col-sm-12" style="margin-top: 10px">
                                            <label class="text-primary">تفاصيل الوظيفة <span class="text-danger">*</span></label>
                                            <input type="text" disabled class="form-control" value="{{$job->desc}}" name="desc" placeholder=" التفاصيل" >
                                        </div>

                                        {{-- approved or not--}}
                                        <div class="col-sm-6" style="margin-top: 20px;">

                                            <div class="from-group">
                                                <label class="text-primary">  الحالة <span class="text-danger">*</span></label>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio"  id="customRadio2" name="approved" value="0" @if($job->approved == '0') checked @endif class="custom-control-input">
                                                    <label class="custom-control-label" for="customRadio2"> غير مقبولة</label>
                                                </div>

                                                <div class="custom-control custom-radio" style="margin-top: 10px">
                                                    <input type="radio" id="customRadio1" name="approved" value="1" @if($job->approved == '1') checked @endif class="custom-control-input">
                                                    <label class="custom-control-label" for="customRadio1">مقبولة</label>
                                                </div>

                                            </div>
                                        </div>

                                    </div>


                                    <button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " type="submit" class="btn btn-outline-success btn-block">حفظ</button>
                                    </div>
                                </form>
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


