

    <!-- Start Footer -->
<footer>
    <div class="overlay">
        <div class="container">
            <div class="row">
                <ul class="col-lg-3 col-md-6 col-6">
                    <li class="title">عن الشركة </li>
                    <li><a href="{{route('front_get_about')}}">من نحن</a></li>
                    <li><a href="{{route('front_get_privacy')}}">شروط الإستخدام و الخصوصية</a></li>
                </ul>
                <ul class="col-lg-3 col-md-6 col-6">
                    <li class="title">تحتاج للمساعدة؟</li>
                    <li><a href="{{route('front_get_contuct_uss')}}">إتصل بنا</a></li>
                </ul>
                <ul class="col-lg-3 col-md-6 col-6">
                    <li class="title">القطاعات</li>
                    <li><a href="{{ route('front_local_sections','poultry') }}">البورصة اليومية</a></li>
                    <li><a href="{{ route('front_section','poultry') }}">دليل الشركات</a></li>
                    <!-- <li><a href="{{ route('front_sections','poultry') }}"> الاستشاريون</a></li> -->
                    <li><a href="{{ route('front_section_store','poultry') }}">سوق الكناني</a></li>
                    <li><a href="{{ route('front_section_news_last','poultry') }}">الأخبار</a></li>
                </ul>
                <ul class="col-lg-3 col-md-6 col-6">
                    <li class="title">الخدمات</li>
                    <li><a href="{{ route('front_shows','1') }}">المعارض</a></li>
                    <li><a href="{{ route('front_magazines','1') }}">الدلائل والمجلات</a></li>
                    <li><a href="{{ route('front_ships') }}">حركة السفن</a></li>
                </ul>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <section class="media-box col-12">
                        <a class="google-play" href="https://play.google.com/store/apps/details?id=com.Elkenany"><img alt="google play" src="{{asset('Front_End/images/android_ar.png')}}"></a>
                        <a class="app-store" href="#"><img alt="app store" src="{{asset('Front_End/images/iphone_ar.png')}}"></a>
                    </section>
                </div>
                <div class="col-12">
                    <div class="social__bar mb-3 text-center">
                        <a class="slider__nav__item facebook" href="https://www.facebook.com/elkenanyapp"
                           target="_blank">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a class="slider__nav__item twitter" href="https://twitter.com/Elkenanyapp"
                           target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a class="slider__nav__item instagram"
                           href="https://www.instagram.com/elkenany_group/" target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a class="slider__nav__item youtube"
                           href="https://www.youtube.com/channel/UCDWq4tL0xEnXHsULbb-6xEw?view_as=subscriber">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a class="slider__nav__item google-plus"
                           href="mailto:info@elkenanygroup.com"
                           target="_blank">
                            <i class="fab fa-google-plus"></i>
                        </a>
                        <a class="slider__nav__item linkedin"
                           href="https://www.linkedin.com/company/elkenany-group" target="_blank">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                </div>
                <div class="col-12">
                    <p class="copyright__text">جميع الحقوق &copy; محفوظة للكناني جروب 2022</p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- End Footer -->