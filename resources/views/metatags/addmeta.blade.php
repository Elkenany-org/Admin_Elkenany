@extends('layouts.app')

@section('style')
    <style type="text/css">
        #avatar{
            width: 100%;
        }
        #avatar:hover{
            width: 100%;
            cursor: pointer;
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

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="m-0" style="display: inline;">إضافة META TAG<i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('selectMetaTag') }}">
                        @csrf

                        <div class="row">

                        <div class="col-sm-6 offset-2" style="margin-top: 10px">
                        <div class="from-group">
                            <label class="text-primary">  تحديد القسم <span class="text-danger">*</span></label>
                        <select name="selection" class="form-control">
                            <option value="" disabled selected>إختيار قسم</option>
                            <option value="news">الأخبار</option>
                            <option value="news_sections">اقسام الأخبار</option>
                            <option value="shows">المعارض</option>
                            <option value="shows_sections">اقسام المعارض</option>
                            <option value="local_stock_section">اقسام البورصة المحلية</option>
                            <option value="local_stock_subsection">اقسام فرعية البورصة المحلية</option>
                            <option value="fodder_stock_section">اقسام بورصة الاعلاف</option>
                            <option value="fodder_stock_subsection">اقسام فرعية بورصة الاعلاف</option>
                            <option value="tenders">المناقصات</option>
                            <option value="tenders_sections">اقسام المناقصات</option>
                            <option value="companies">الشركات</option>
                            <option value="companies_sections">اقسام الشركات الرئيسية</option>
                            <option value="companies_sub_sections">اقسام الشركات الفرعية</option>
                        </select>
                          </div>
                          </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 offset-2" style="margin-top: 10px">
                                <div class="from-group">
                                    <label class="text-primary"> بحث </label>
                                    <input type="text" class="form-control " name="search" placeholder="Search..." autocomplete="off">
                                </div>
                            </div>
                        </div>


                    </form>


                    <form action="{{route('storeMetaTag')}}" method="post" enctype="multipart/form-data" novalidate>
                        {{csrf_field()}}

                        <input type="hidden" name="selection" value="" id="selectionValue">

                        <div class="row">
                                <div class="col-sm-6 offset-2" style="margin-top: 10px">
                                    <div class="from-group">
                                        <select name="result" class="form-control section_id" required >
                                            <option value="" disabled selected> نتائج البحث</option>
                                        </select>
                                    </div>
                                </div>
                        </div>


                        <div class="row">

                            {{-- title --}}
                            <div class="col-sm-6 offset-2" style="margin-top: 10px">
                                <div class="from-group">
                                    <label class="text-primary">العنوان: <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" value="" placeholder="العنوان" required="">
                                </div>
                            </div>
                        </div>


                        <div class="row">

                            {{-- title --}}
                            <div class="col-sm-6 offset-2" style="margin-top: 10px">
                                <div class="from-group">
                                    <label class="text-primary">عنوان السوشيال: <span class="text-danger">*</span></label>
                                    <input type="text" name="social_title" class="form-control" value="" placeholder="عنوان السوشيال" required="">
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            {{-- title --}}
                            <div class="col-sm-6 offset-2" style="margin-top: 10px">
                                <div class="from-group">
                                    <label class="text-primary">الوصف: <span class="text-danger">*</span></label>
                                    <input type="text" name="desc" class="form-control" value="" placeholder="الوصف" required="">
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            {{-- title --}}
                            <div class="col-sm-6 offset-2" style="margin-top: 10px">
                                <div class="from-group">
                                    <label class="text-primary">وصف السوشيال: <span class="text-danger">*</span></label>
                                    <input type="text" name="social_desc" class="form-control" value="" placeholder="وصف السوشيال" required="">
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            {{-- title --}}
                            <div class="col-sm-6 offset-2" style="margin-top: 10px">
                                <div class="from-group">
                                    <label class="text-primary">اللينك: <span class="text-danger">*</span></label>
                                    <input type="text" name="link" class="form-control" value="" placeholder="اللينك" required="">
                                </div>
                            </div>
                        </div>


                                    <div class="col-sm-6 offset-2 marbo" style="margin-top: 10px">
                                        <label class="text-primary">إختيار صورة <span class="text-danger"> * </span></label><br>
                                        <input type="file" name="image" accept="image/*" onchange="loadAvatar(event)" style="display: none;">
                                        <img src="{{asset('dist/img/placeholder2.png')}}" onclick="ChooseAvatar()" id="avatar">
                                    </div>

                            {{-- title --}}
                            <div class="col-sm-6 offset-2" style="margin-top: 10px">
                                <div class="from-group">
                                    <label class="text-primary">alt: <span class="text-danger">*</span></label>
                                    <input type="text" name="alt" class="form-control" value="" placeholder="alt" required="">
                                </div>
                            </div>


                            {{-- submit --}}
                            <button style="width: 50%; margin-left: auto;margin-top:20px; margin-right: auto; " type="submit" class="btn btn-outline-primary btn-block">إضافة</button>
                    </form>
                </div>
            </div>
        </div>
        {{--warning--}}
        <div class="modal fade" id="modal-secondary">
            <div class="modal-dialog">
                <div class="modal-content bg-secondary">
                    <div class="modal-body">
                        <p>هذه الصفحة خاصة  ب اضافة مناقصة جديد</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script type="text/javascript">
        function ChooseAvatar1(){$("input[name='images[]']").click()}
        function ChooseAvatar(){$("input[name='image']").click()}
        var loadAvatar = function(event) {
            var output = document.getElementById('avatar');
            output.src = URL.createObjectURL(event.target.files[0]);
        };

        $(function() {
            // Multiple images preview in browser
            var imagesPreview = function(input, placeToInsertImagePreview) {

                if (input.files) {
                    var filesAmount = input.files.length;

                    for (i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function(event) {
                            $($.parseHTML('<img class="img-fluid mb-2  bounceIn">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                        }

                        reader.readAsDataURL(input.files[i]);
                    }
                }

            };

            $('#gallery1').on('change', function() {
                imagesPreview(this, 'div.gallery1');
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('select[name="selection"]').on('change', function () {
                $('select[name="result"]').empty();
                var selection = $(this).val();
                $('#selectionValue').val(selection);
            });

            $('input[name="search"]').on('input', function () {
                var selection = $('select[name="selection"]').val();
                var searchTerm = $(this).val();

                $.ajax({
                        url: 'select-meta-tag',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            search: searchTerm,
                            selection: selection
                        },
                        success: function (response) {
                            console.log(5)
                            $('select[name="result"]').empty();
                            if (response.length > 0) {
                                response.forEach(function (result) {
                                    var optionText = result.title ? result.title.substring(0, 50) : result.name;
                                    $('select[name="result"]').append('<option value="' + result.id + '">' + optionText + '</option>');
                                });
                            } else {
                                $('select[name="result"]').append('<option value="">No results found.</option>');
                            }
                        }
                    });
            });
        });


    </script>



@endsection
