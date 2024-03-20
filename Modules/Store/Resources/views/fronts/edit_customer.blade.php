
@extends('layouts.front')
@section('style')
<link rel="stylesheet" href="{{asset('Front_End/css/settings.css')}}">
<title>  البيانات الأساسية</title>
<style>

.left-content .basics form .submitj {
    width: 100%;
    height: 45px;
    margin-top: 10px;
    background-color: #FFAA00;
    color: #1f6b00;
    font-size: 20px;
    font-weight: bold;
    border: none;
    box-shadow: 0px 4px 5px #ddd;
    border-radius: 5px;
}
</style>
@endsection
@section('content')


   <!-- Start Settings  -->
   <article class="settings">
        <div class="container-fluid holder">
            <!-- Start Titles -->
            <section class="titles row">
                <section class="title-left col-lg-10 col-11">
                    <h3>البيانات الأساسية</h3>
                </section>
                <section class="title-right col-lg-2 col-1">
                    <h3 class="d-lg-block d-none">الإعدادات</h3>
                    <i class="fas fa-cog d-lg-none d-block"></i>
                </section>
            </section>
            <!-- End Titles -->
            <!-- Start Content -->
            <section class="content row">
                <!-- Start Left Content -->
                <section class="left-content col-10">
                    <div class="tab-content" id="v-pills-tabContent">
                        <!-- Start Basics Content -->
                        <div class="basics tab-pane fade show active" id="v-pills-basics" role="tabpanel"
                            aria-labelledby="v-pills-basics-tab">
                            <form action="{{route('updateprofile')}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <input type="hidden" name="id" value="{{Auth::guard('customer')->user()->id}}">
                                <input type="file" name="avatar" id="imgupload" style="display:none" />
                                <a id="OpenImgUpload" class="change-img">
                                    <div class="overlay">
                                        <i class="fas fa-pencil-alt"></i>
                                    </div>
                                    <img src="{{asset('uploads/customers/avatar/'.Auth::guard('customer')->user()->avatar)}}" alt="profile photo">
                                </a>
                                <h3>تعديل الصورة الشخصية</h3>
                                
                                    <h4>الاسم</h4>
                                    <section id="name-box" class="name-box boxes">
                                        <a id="name-edit">تغيير</a>
                                        <input id="username" name="name" type="text" value="{{Auth::guard('customer')->user()->name}}" disabled>
                                    </section>
                                    <h4>الايميل</h4>
                                    <section id="email-box" class="mail-box boxes">
                                        <a id="email-edit">تغيير</a>
                                        <input id="email" name="email" class="email" type="email" value="{{Auth::guard('customer')->user()->email}}"
                                            disabled>
                                    </section>
                                    @if($errors->has('email'))
                                    <span style="margin-bottom:20px;">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    
                             
                                  
                                    
                                    @endif
                                    <input id="submit" class="submit" type="submit" value="استمرار">
                            </form>
                        </div>
                        <!-- End Basics Content -->
                        <!-- Start privacy Content -->
                        <div class="basics tab-pane fade" id="v-pills-privacy" role="tabpanel"
                            aria-labelledby="v-pills-privacy-tab">
                            <form action="{{route('updateprofile')}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <input type="hidden" name="id" value="{{Auth::guard('customer')->user()->id}}">
                                    <h4>الموبايل</h4>
                                    <section id="phone-box" class="mail-box boxes">
                                       
                                        <input id="phone" name="phone" class="phone" type="number" value="{{Auth::guard('customer')->user()->phone}}"
                                            >
                                    </section>
                                    @if($errors->has('phone'))
                                    <span style="margin-bottom:20px;">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    
                                    @endif
                                    <h4>الباسورد</h4>
                                    <section id="password-box" class="mail-box boxes">
                                        
                                        <input id="password" name="password" class="password" type="password" value=""
                                            >
                                    </section>
                                    @if($errors->has('password'))
                                    <span style="margin-bottom:20px;">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    
                                    @endif
                                    <input id="submit" class="submitj" type="submit" value="استمرار">
                            </form>
                        </div>
                        <!-- End privacy Content -->
                        <!-- Start payments Content -->
                        <div class="tab-pane fade" id="v-pills-payments" role="tabpanel"
                            aria-labelledby="v-pills-payments-tab">بيانات الدفع
                        </div>
                        <!-- End payments Content -->
                        <!-- Start bills Content -->
                        <div class="tab-pane fade" id="v-pills-bills" role="tabpanel"
                            aria-labelledby="v-pills-bills-tab">ايصالات الدفع
                        </div>
                        <!-- End bills Content -->
                        <!-- Start subscriptions Content -->
                        <div class="tab-pane fade" id="v-pills-subscriptions" role="tabpanel"
                            aria-labelledby="v-pills-subscriptions-tab">
                            @if(Auth::guard('customer')->user())

                            @if(Auth::guard('customer')->user()->memb == 1)
                                هذا الحساب مدفوع
                            @endif
                            @if(Auth::guard('customer')->user()->memb == 0)
                                هذا الحساب مجاني

{{--                                    <div class="col-md-12">--}}
{{--                                        <div class="col-75">--}}
{{--                                            <div class="container">--}}

{{--                                                <div style="text-align: center" class="row">--}}
{{--                                                    <div class="col-md-12">--}}
{{--                                                        <form action="{{route('credit')}}" method="POST">--}}
{{--                                                            {{ csrf_field() }}--}}
{{--                                                            <input style="width: 100px;height: 40px" type="submit" value="Paymob" class="btn-success">--}}
{{--                                                        </form>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}

{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                            @endif

                            @endif
                        </div>
                        <!-- End subscriptions Content -->
                    </div>
                </section>
                <!-- End Left Content -->
                <!-- Start Right Content -->
                <section class="right-content col-2">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-basics-tab" data-toggle="pill" href="#v-pills-basics"
                            role="tab" aria-controls="v-pills-basics" aria-selected="true">
                            <i class="fas fa-user"></i>
                            <span class="d-lg-block d-none">
                                البيانات الأساسية
                            </span>
                        </a>
                        <a class="nav-link" id="v-pills-privacy-tab" data-toggle="pill" href="#v-pills-privacy"
                            role="tab" aria-controls="v-pills-privacy" aria-selected="false">
                            <i class="fas fa-user-shield"></i>
                            <span class="d-lg-block d-none">
                                الخصوصية
                            </span>
                        </a>
                        <a class="nav-link" id="v-pills-payments-tab" data-toggle="pill" href="#v-pills-payments"
                            role="tab" aria-controls="v-pills-payments" aria-selected="false">
                            <i class="fab fa-cc-visa"></i>
                            <span class="d-lg-block d-none">
                                بيانات الدفع
                            </span>
                        </a>
                        <a class="nav-link" id="v-pills-bills-tab" data-toggle="pill" href="#v-pills-bills" role="tab"
                            aria-controls="v-pills-bills" aria-selected="false">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span class="d-lg-block d-none">
                                ايصالات الدفع
                            </span>
                        </a>
                        <a class="nav-link" id="v-pills-subscriptions-tab" data-toggle="pill"
                            href="#v-pills-subscriptions" role="tab" aria-controls="v-pills-subscriptions"
                            aria-selected="false">
                            <i class="far fa-handshake"></i>
                            <span class="d-lg-block d-none">
                                بيانات الاشتراك
                            </span>
                        </a>
                    </div>
                </section>
                <!-- End Right Content -->
            </section>
            <!-- End Content -->
        </div>
    </article>
    <!-- End Settings  -->


@endsection

@section('script')
<script src="{{asset('Front_End/js/settings.js')}}"></script>
@endsection