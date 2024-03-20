
<!DOCTYPE html>
<html lang="ar">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <title>  {{$show->name}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="This is Description Area">




<link href="{{asset('Front_End/css/vendors/splide.min.css')}}" rel="stylesheet">
    <link href="{{asset('Front_End/css/vendors/splide-default.min.css')}}" rel="stylesheet">

    <script type="module" src="https://www.gstatic.com/firebasejs/9.0.1/firebase-messaging-sw.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Icon -->
    <link rel="icon" href="{{asset('Front_End/images/favicon.png')}}">
    <!-- Title -->
   
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('Front_End/css/vendors/bootstrap.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('Front_End/css/vendors/all.min.css')}}">
    <!-- slick -->
    <link rel="stylesheet" type="text/css" href="{{asset('Front_End/vendors/slick/slick.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('Front_End/vendors/slick/slick-theme.css')}}" />
    <!-- My CSS -->
    <link rel="stylesheet" href="{{asset('Front_End/css/styles.css')}}">
    <style type="text/css">

.page__gallery {
  margin: 1rem auto 3rem auto; }
  .page__gallery .splide img {
    border-radius: 12px !important;
    width: 100%;
    height: 100%; }
  .page__gallery .splide .splide__pagination {
    bottom: -2rem; }
  .page__gallery #primary-slider {
    margin: 1rem auto; }
  .page__gallery #secondary-slider {
    margin: 1rem auto; }
    .page__gallery #secondary-slider .splide__slide {
      margin-block: 1rem; }
      .page__gallery #secondary-slider .splide__slide.is-active {
        border-radius: 8px;
        border-color: #FFAA00; }
        
.splide__pagination li button {
  background: #1f6b00; }
  .splide__pagination li button:hover, .splide__pagination li button.is-active {
    background: #FFAA00; }
    </style>

</head>
<body>
<div class="main__content__body">
  <!--Start Loading Screen-->
  <article class="loading-screen">
        <div class="chicken-loader">
            <span></span>
        </div>
    </article>
    <!--End Loading Screen-->

    <!-- Start button to top -->
    <button class="btn_top" id="myBtn">
        <span class="arrow"></span>
    </button>
    <!-- End button to top -->

    <!-- Start Navigation Bar -->
    @include('../fronts.sidebar')

<!-- Start Header -->
<header class="banner__header">
        <div class="container-fluid one-full-slider">
        @foreach($ads as $ad)
            <a href="{{$ad->link}}" target="_blank"><img alt="banner" class="banner" src="{{asset('uploads/full_images/'.$ad->image)}}"></a>
        @endforeach 
    </div>
</header>

<article class="partners slider my-2">
    <div class="container-fluid logos__holder">
        <section class="partners-slider" dir="rtl">

        @foreach($logos as $logo)
            <div class="item">
            <a href="{{$logo->link}}" target="_blank" class="logo-holder"><img alt="partner logo" src="{{asset('uploads/full_images/'.$logo->image)}}"></a>
            <div class="wall"></div>
            </div>
        @endforeach 
            
        </section>
    </div>
</article>
    <!-- End Header -->

<div class="exhibition__bar__container">
    <div class="container actions__container">
        <ul class="list-unstyled actions__list">
            <li class="single__action">
            @if(Auth::guard('customer')->user())
                @if(!empty($rating))
                <form class="form__box row justify-content-center" action="{{route('front_add_notgoing')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="show_id" value="{{$show->id}}">
                    <button class="going__btn" style="margin: 0 2.5rem;" type="submit">عدم الذهاب</button>
                </form>
                @endif
            @if(empty($rating))
            <form class="form__box row justify-content-center" action="{{route('front_add_going')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="show_id" value="{{$show->id}}">
                    <button class="going__btn" style="margin: 0 2.5rem;" type="submit">الذهاب</button>
                </form>
            @endif
                
                
            @else
                <a class="going__btn" href="{{ route('customer_login') }}">الذهاب</a>
            @endif
            </li>
            <li class="wall"></li>
            <li class="single__action">
            
                <button class="action" data-target="#request__booth" data-toggle="modal">طلب مكان عرض</button>
            </li>
            <li class="wall"></li>
            <li class="single__action">
                <button class="action" data-target="#addRate" data-toggle="modal">إضافة تقييم</button>
            </li>
            <li class="wall"></li>
            <li class="single__action">
                <button class="action" data-target="#share" data-toggle="modal">المشاركة</button>
            </li>
        </ul>
        
    </div>
    <!-- Start Gallery Modal -->
    <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="request__booth"
            tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header flex-row-reverse align-items-baseline">
                    <p class="modal__title">طلب مكان عرض</p>
                    <button aria-label="Close" class="modal__close" data-dismiss="modal" type="button">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid" dir="rtl">
                        <div class="form__container">
                            <form class="form__box row justify-content-center" action="{{route('front_add_place')}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <input type="hidden" name="show_id" value="{{$show->id}}">
                                <input class="inputs" name="name" placeholder="الأسم" type="text">
                                <input class="inputs" name="email" placeholder="الإيميل" type="text">
                                <input class="inputs" name="phone" placeholder="رقم الموبيل" type="text">
                                <input class="inputs" name="company" placeholder="أسم الشركة" type="text">
                                <textarea class="inputs" name="desc" placeholder="التفاصيل" rows="5"></textarea>
                                <button class="action__yellow__green__button w-75" type="submit">إرسال</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- End Gallery Modal -->
        <!-- Start Gallery Modal -->
    <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="addRate"
            tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header flex-row-reverse align-items-baseline">
                    <p class="modal__title">تقييم المعرض</p>
                    <button aria-label="Close" class="modal__close" data-dismiss="modal" type="button">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid" dir="rtl">
                        <div class="form__container">
                        <form class="form__box row justify-content-center" action="{{route('front_add_reating')}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <input type="hidden" name="show_id" value="{{$show->id}}">
                                <input class="inputs" name="name" placeholder="الأسم" type="text">
                                <input class="inputs" name="email" placeholder="الإيميل" type="text">

                                    <!--        New Rating Component        -->
                                    <div class="main__rating d-flex align-items-center justify-content-center"
                                        style="margin: 1rem auto !important;">
                                    <div class="global__rating">
                                        <i class="rating__star far fa-star"></i>
                                        <i class="rating__star far fa-star"></i>
                                        <i class="rating__star far fa-star"></i>
                                        <i class="rating__star far fa-star"></i>
                                        <i class="rating__star far fa-star"></i>
                                    </div>
                                    <div class="mx-2">
                                        <span class="rating__result"></span>
                                        <input class="rating" type="hidden" name="rating" value="">
                                    </div>
                                </div>

                                <textarea class="inputs" name="desc" placeholder="التقييم" rows="5"></textarea>
                                <button class="action__yellow__green__button w-75" type="submit">إرسال</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- End Gallery Modal -->
        <!-- Start Gallery Modal -->
    <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="share"
            tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header flex-row-reverse align-items-baseline">
                    <p class="modal__title">مشاركة المعرض</p>
                    <button aria-label="Close" class="modal__close" data-dismiss="modal" type="button">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="share__container">
                            <h4>مشاركة المعرض عبر منصات التواصل الاجتماعى</h4>
                            <div class="area__container">
                                <p class="cop" id="item-to-copy"> {{ route('front_one_show',$show->id) }}</p>
                                <button class="p__link">نسخ رابط المعرض
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- End Gallery Modal -->
</div>

    @include('../parts.alert')

    <!-- Start Partners -->
    <!-- End Partners -->
<!-- page tabs -->
<div class="page__tabs container">
    <ul class="tabs__list">
        <li class="list__item list__item-4">
            <a class="list__link active" href="{{ route('front_one_show',$show->id) }}">عن المعرض</a>
        </li>

        <li class="list__item list__item-4">
            <a class="list__link " href="{{ route('front_one_show_review',$show->id) }}">المراجعات</a>
        </li>

        <li class="list__item list__item-4">
            <a class="list__link " href="{{ route('front_one_show_Showers',$show->id) }}">العارضون</a>
        </li>

        <li class="list__item list__item-4">
            <a class="list__link " href="{{ route('front_one_show_speakers',$show->id) }}">المتحدثون</a>
        </li>
    </ul>
</div>
    <!-- page tabs -->

<div class="about__exhibition container">
    <div class="row">
        <div class="col-12">
            <!-- Start Company Gallery -->

              <!-- Start Company Gallery -->
              <article class="company-gallery">
                   @if(count($show->ShowImgs) > 0)
                    <div class="page__gallery">
                        <div class="splide" id="primary-slider">
                            <div class="splide__track">
                                <ul class="splide__list">
                                @foreach($show->ShowImgs as $value)
                                    <li class="splide__slide">
                                        <a class="logo-holder">
                                        <img alt="partner logo" src="{{asset('uploads/show/alboum/'.$value->image)}}">
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="splide" id="secondary-slider">
                            <div class="splide__track">
                                <ul class="splide__list">
                                @foreach($show->ShowImgs as $value)
                                    <li class="splide__slide">
                                        <a class="logo-holder">
                                        <img alt="partner logo" src="{{asset('uploads/show/alboum/'.$value->image)}}">
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="slider__description">
                    {{$show->desc}}
                    </div>
                    
                </article>
                <!-- End Company Gallery -->

             
            <!-- End Company Gallery -->

      
        </div>
        <div class="col-12">
            <div class="about__exhibition-content row">
                <div class="col-12 col-md-6">
                    <div class="content__card">
                        <div class="title">
                            التواريخ
                        </div>
                        <div class="body">
                            <ul>
                            @foreach($show->time() as $value)
                                <li>
                             
                                    <p><i class="far fa-clock"></i><span>{{$value}}</span></p>
                                   
                                   
                                </li>

                                @endforeach
                                
                          
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                        <div class="content__card">
                            <div class="title">الوقت</div>
                            <div class="body">
                                <ul>
                                <li>
                                @if(!empty($show->watch()))
                                        @foreach($show->watch() as $value)
                                        <p><i class="far fa-clock"></i><span>{{$value}}</span></p>
                                        @endforeach
                                    @endif
                                </li>
                                
                                </ul>
                            </div>
                        </div>
                    </div>
                @if(count($show->ShowTacs) != 0)
                <div class="col-12 col-md-6">
                    <div class="content__card">
                        <div class="title">
                            تكلفة الدخول
                        </div>
                        <div class="body">
                            <ul>
                                @foreach($show->ShowTacs as $value)
                                    <li>
                                        <p class="single__data-big">
                                        <span class="body__title"><i
                                                class="fas fa-ticket-alt"></i>{{$value->name}}</span>
                                            <span class="body__content"><span>{{$value->price}} جنية</span></span>
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-12 col-md-6">
                    <div class="content__card">
                        <div class="title">
                            المشاركون
                        </div>
                        <div class="body">
                            <ul>
                                <li>
                                    <p><i class="fas fa-user-friends"></i><span>{{$show->view_count}} زائر</span></p>
                                </li>
                                <li>
                                    <p><i class="fas fa-user-friends"></i><span>{{count($show->Showers)}} عارض</span></p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="content__card">
                        <div class="title">
                            الجهات المنظمة
                        </div>
                        <div class="body">
                            <ul>
                            @foreach($show->ShowOrgs as $value)
                                <li>
                                    <p><i class="fas fa-sitemap"></i><span> {{$value->Organ->name}}</span></p>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



</div>
    @include('../fronts.footer')


    <!-- Start Scripts -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS v4.5 -->
    <script src="{{asset('Front_End/js/vendors/jquery-3.5.1.slim.min.js')}}"></script>
    <script src="{{asset('Front_End/js/vendors/popper.min.js')}}"></script>
    <script src="{{asset('Front_End/js/vendors/bootstrap.min.js')}}"></script>
    <!-- JQuery -->
    <script src="{{asset('Front_End/js/vendors/jquery-3.5.1.js')}}"></script>
    <!-- Slick -->
    <script src="{{asset('Front_End/js/splide.min.js')}}"></script>
    <!-- My JavaScript -->
    <script src="{{asset('Front_End/js/global.js')}}"></script>

    <script src="{{asset('Front_End/js/rate_initialising.js')}}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var secondarySlider = new Splide('#secondary-slider', {
            fixedWidth: 100,
            height: 75,
            gap: 10,
            direction: 'rtl',
            cover: true,
            isNavigation: true,
            focus: 'center',
            rewind: true,
            breakpoints: {
                '600': {
                    fixedWidth: 66,
                    height: 40,
                }
            },
        }).mount();

        var primarySlider = new Splide('#primary-slider', {
            type: 'fade',
            heightRatio: 0.5,
            height: 450,
            pagination: false,
            direction: 'rtl',
            arrows: false,
            cover: true,
            rewind: true,
            interval: 5000,
            autoplay: true,
            perMove: 1,
        }); // do not call mount() here.

        primarySlider.sync(secondarySlider).mount();
    });
    $(document).ready(function () {
        const ratingResult = document.querySelector(".rating__result");
        $(".p__link").click(function () {
            copyToClipboard(window.location.href);
        });

        function ratingResultCallback(result) {
            console.log("result ", result);
            ratingResult.textContent = `${result}/5`;
            $("input[name='rating']").val(result);
            //  and Then Do Any thing with the rating results
        }

        executeRating(ratingStars, ratingResultCallback);

        function copyToClipboard(text) {
            var $el = $("<input>");
            $(".share__container").append($el);
            $el.val(window.location.href).select();
            document.execCommand("copy");
            $el.remove();
        }
    });
</script>
    <script>
    const ratingResult = document.querySelector(".rating__result");

    function ratingResultCallback(result) {
        console.log('result ', result);
        ratingResult.textContent = `${result}/5`;
        $("input[name='rating']").val(result)

        //  and Then Do Any thing with the rating results
    }

    executeRating(ratingStars, ratingResultCallback);


</script>

<script>


let isInitialized = false;

///////////////////// Start code For Initialized popup-slider and start from image that user clicked on it /////////////////
function sendIndexToPopUp(imageIndex) {
    //Check if slick popup-slider is Initialized before, then destroy it, because it will throw Error
    if (isInitialized) {
        $('.popup-slider').slick('unslick');
    }
    //if slick popup-slider is Initialized put variable isInitialized to true, to check it in next time
    $('.popup-slider').on('init', function (event, slick) {
        isInitialized = true;
    });
    //We Initializing slick popup-slider here to give it the number of (initialSlide) of the image that user clicked on it
    $('.popup-slider').slick({
        dots: false,
        infinite: true,
        autoplay: true,
        slidesToShow: 1,
        adaptiveHeight: true,
        draggable: true,
        arrows: true,
        initialSlide: imageIndex,
        autoplaySpeed: 3000,
        speed: 1000,
    });

    console.log('$(".popup-slider").slick(\'refresh\');');
        $(".popup-slider").slick('refresh');
}

///////////////////// Start code to setPosition for popup-slider after one second from clicking to start modal, because modal making problems /////////////////
$('.logo-holder').on('click', function () {
    window.setTimeout(function (event) {
        $('.popup-slider')[0].slick.setPosition();
    }, 500);
});

///////////////////// End code to setPosition for popup-slider after one second from clicking to start modal, because modal making problems /////////////////

$(document).ready(function () {
    $('.p__link').click(function(){
        copyToClipboard(window.location.href);  
    })
});

function copyToClipboard(text) {

    var $el = $("<input>");
    $(".share__container").append($el);
    $el.val(window.location.href).select();
    document.execCommand('copy')
    $el.remove();
}
</script>
 
</body>

</html>