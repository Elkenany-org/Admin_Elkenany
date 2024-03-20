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
        <link rel="stylesheet" href="{{asset('Front_End/css/login.css')}}">
        
    </head>

    <body>

        <!-- Start Login -->
        <article class="container login">
            <h2 class="main-title font-weight-bold">تسجيل الدخول</h2>
            <div class="holder">
                <section class="login-box">
                    <img src="{{asset('Front_End/images/logo.png')}}" alt="Main Logo">
                    <form method="POST" action="{{ route('customer_login_start') }}">
                        @csrf
                        <input name="phone" type="text" placeholder="رقم الموبايل">
                        <input name="password" type="password" placeholder="الرقم السري">
                        <input class="submit" type="submit" value="تسجيل الدخول">
                    </form>
                    <section class="login-options">
                        <a class="forgot-password links" href="#">هل نسيت كلمة السر؟</a>
                        <span class="sign-up font-weight-bold"> ليس لديك حساب ؟ <a class="links" href="{{ route('customer_register') }}"> سجل
                                الان</a></span>
                    </section>
                    <h3 class="or font-weight-bold">أو</h3>
                    <section class="login-extras">
                        <a class="login-facebook" href="{{ route('customer_facebook') }}">
                            <i class="fab fa-facebook-f"></i>
                            الدخول عن طريق فيسبوك
                        </a>  
                        <a class="login-google" href="{{ route('customer_google') }}">
                            <i class="fab fa-google"></i>
                            الدخول عن طريق جوجل
                        </a>
                        <span class="font-weight-bold">بتسجل حساب بأنك توافق علي <a class="terms links" href="{{route('front_get_terms')}}"> الشروط
                                والأحكام</a></span>
                    </section>
                </section>
            </div>
        </article>


        <!-- jQuery first, then Popper.js, then Bootstrap JS v4.5 -->
        <script src="{{asset('Front_End/js/jquery-3.5.1.slim.min.js')}}"></script>
        <script src="{{asset('Front_End/js/popper.min.js')}}"></script>
        <script src="{{asset('Front_End/js/bootstrap.min.js')}}"></script>
        <!-- JQuery -->
        <script src="{{asset('Front_End/js/jquery-3.5.1.js')}}"></script>
    </body>

</html>