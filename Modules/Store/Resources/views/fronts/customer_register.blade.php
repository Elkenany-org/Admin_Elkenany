<!DOCTYPE html>
<html lang="ar">

    <head>



        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-NYH035MQGZ"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'G-NYH035MQGZ');
          console.log(' Google Analytics Is Fired')
        </script>



        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="This is Description Area">
        <!-- Icon -->
        <link rel="icon" href="{{asset('Front_End/images/favicon.png')}}">
        <!-- Title -->
        <title> حساب جديد</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{asset('Front_End/css/bootstrap.css')}}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{asset('Front_End/css/all.min.css')}}">
        <!-- slick -->
        <link rel="stylesheet" type="text/css" href="{{asset('Front_End/slick/slick.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('Front_End/slick/slick-theme.css')}}" />
        <!-- My CSS -->
        <link rel="stylesheet" href="{{asset('Front_End/css/sign_up.css')}}">
        
    </head>

    <body>

        <!-- Start Sign Up  -->
        <article class="container sign-up ">
            <h2 class="main-title font-weight-bold">سجل حساب جديد !</h2>
            <div class="holder">
                <section class="sign-up-box">
                    <section class="sign-up-extras">
                         <a class="sign-up-facebook" href="{{ route('customer_facebook') }}">
                            <i class="fab fa-facebook-f"></i>
                            الدخول عن طريق فيسبوك
                        </a>  
                        <a class="sign-up-google" href="{{ route('customer_google') }}">
                            <i class="fab fa-google"></i>
                            الدخول عن طريق جوجل
                        </a>
                    </section>
                    <h3 class="or font-weight-bold">أو</h3>
                    <form method="POST" action="{{ route('customer_register_start') }}">
                        @csrf
                        @if($errors->has('name'))
                        <span style="margin-bottom:20px;">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                           
                        @endif
                        <input name="name" type="text" required placeholder="الاسم">
                        @if($errors->has('email'))
                        <span style="margin-bottom:20px;">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                           
                        @endif
                        <input name="email" required type="email" placeholder="الأيميل">

                        @if($errors->has('phone'))
                        <span style="margin-bottom:20px;">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                           
                        @endif
                        <input name="phone" type="number" required placeholder="رقم الموبايل">


                        @if($errors->has('password'))
                        <span style="margin-bottom:20px;">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                           
                        @endif
                        <input name="password" type="password" required placeholder="الرقم السري">

                        
                        <!-- <input type="checkbox" style="width: 10%;height: 20px;margin-top: 8px;" id="memb" name="memb" value="1">
                        <label for="memb"> هل تريد حساب مدفوع ؟</label><br> -->
                       
                        <input class="submit" type="submit" value="سجل الان">
                        <div class="check-box">
                            <input class="checkbox" type="checkbox" value="agreement">
                            <span class="font-weight-bold">أوافق علي استقبال الاخبار واخر التحديثات من الكناني</span>
                        </div>
                    </form>
                </section>
                <span class="font-weight-bold d-block">بتسجيل حساب بأنك توافق علي <a class="terms links" href="{{route('front_get_terms')}}"> الشروط
                        والأحكام</a></span>
            </div>
        </article>
        <!-- End Sign Up  -->


        <!-- jQuery first, then Popper.js, then Bootstrap JS v4.5 -->
        <script src="{{asset('Front_End/js/jquery-3.5.1.slim.min.js')}}"></script>
        <script src="{{asset('Front_End/js/popper.min.js')}}"></script>
        <script src="{{asset('Front_End/js/bootstrap.min.js')}}"></script>
        <!-- JQuery -->
        <script src="{{asset('Front_End/js/jquery-3.5.1.js')}}"></script>
    </body>

</html>