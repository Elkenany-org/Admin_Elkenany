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
                                <form action="{{route('storeEditCategory')}}" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        {{csrf_field()}}
                                        <input type="hidden" name="id" value="{{$category->id}}">
                                        <div class="col-sm-12" style="margin-top: 10px">
                                            <label class="text-primary">إسم القسم <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" value="{{$category->name}}" name="name" placeholder=" الاسم" required>
                                        </div>
                                        <div class="col-sm-12" style="margin-top: 10px">
                                            <label class="text-primary">إسم القسم بالانجليزي<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" value="{{$category->type}}" name="type" placeholder=" الاسم" required>
                                        </div>
                                        <div class="col-sm-12" style="margin-top: 10px">
                                            <label class="text-primary">تفاصيل القسم <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" value="{{$category->desc}}" name="desc" placeholder=" التفاصيل" required>
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


