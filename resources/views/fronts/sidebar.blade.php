
     <!-- Start Navigation Bar -->
     <nav class="navbar navbar-expand-lg navbar-light bg-light">
 
        <a class="navbar-brand" href="{{ route('fronts') }}">
            <img alt="main logo" src="{{asset('Front_End/images/logo.png') }}">
        </a>
        <button aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"
                class="navbar-toggler"
                data-target="#navbarSupportedContent" data-toggle="collapse" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle"
                       data-toggle="dropdown"
                       href="#" id="mainCategoriesDropdown" role="button">
                        القطاعات
                    </a>
                    <ul aria-labelledby="mainCategoriesDropdown" class="dropdown-menu">
                        <li>
                            <a class="nav-link dropdown-toggle text-center" data-bs-toggle="dropdown"> القطاع
                                الداجنى </a>
                            <div class="dropdown-divider"></div>
                            <ul class="submenu dropdown-menu submenu-left">
                                <li><a class="dropdown-item" href="{{ route('front_local_sections','poultry') }}">البورصة
                                    اليومية</a></li>
                                <div class="dropdown-divider"></div>
                                <li><a class="dropdown-item" href="{{ route('front_section','poultry') }}">دليل
                                    الشركات</a></li>
                                <div class="dropdown-divider"></div>
                                <li><a class="dropdown-item" href="{{ route('front_section_news_last','poultry') }}">
                                    الأخبار</a></li>
                                <div class="dropdown-divider"></div>
                               <!-- <li><a class="dropdown-item"
                                 href="{{ route('front_sections','poultry') }}">الاستشاريون</a></li> -->
                                <li><a class="dropdown-item" href="{{ route('front_section_store','poultry') }}">سوق
                                    الكناني</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="nav-link dropdown-toggle text-center" data-bs-toggle="dropdown"> القطاع
                                الحيوانى </a>
                            <div class="dropdown-divider"></div>
                            <ul class="submenu dropdown-menu submenu-left">
                                <li><a class="dropdown-item" href="{{ route('front_local_sections','animal') }}">البورصة
                                    اليومية</a></li>
                                <div class="dropdown-divider"></div>
                                <li><a class="dropdown-item" href="{{ route('front_section','animal') }}">دليل
                                    الشركات</a></li>
                                <div class="dropdown-divider"></div>
                                    <li><a class="dropdown-item" href="{{ route('front_section_news_last','animal') }}">
                                    الأخبار</a></li>
                                <div class="dropdown-divider"></div>
                                    <!-- <li><a class="dropdown-item"
                                 href="{{ route('front_sections','animal') }}">الاستشاريون</a></li> -->
                                <li><a class="dropdown-item" href="{{ route('front_section_store','animal') }}">سوق
                                    الكناني</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="nav-link dropdown-toggle text-center" data-bs-toggle="dropdown"> القطاع
                                الزراعى </a>
                            <div class="dropdown-divider"></div>
                            <ul class="submenu dropdown-menu submenu-left">
                                <li><a class="dropdown-item" href="{{ route('front_local_sections','farm') }}">البورصة
                                    اليومية</a></li>
                                <div class="dropdown-divider"></div>
                                <li><a class="dropdown-item" href="{{ route('front_section','farm') }}">دليل
                                    الشركات</a></li>
                                <div class="dropdown-divider"></div>
                                    <li><a class="dropdown-item" href="{{ route('front_section_news_last','farm') }}">
                                    الأخبار</a></li>
                                <div class="dropdown-divider"></div>
                                    <!-- <li><a class="dropdown-item"
                                 href="{{ route('front_sections','farm') }}">الاستشاريون</a></li> -->
                                <li><a class="dropdown-item" href="{{ route('front_section_store','farm') }}">سوق
                                    الكناني</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="nav-link dropdown-toggle text-center" data-bs-toggle="dropdown"> القطاع
                                السمكى </a>
                            <div class="dropdown-divider"></div>
                            <ul class="submenu dropdown-menu submenu-left">
                                <li><a class="dropdown-item" href="{{ route('front_local_sections','fish') }}">البورصة
                                    اليومية</a></li>
                                <div class="dropdown-divider"></div>
                                <li><a class="dropdown-item" href="{{ route('front_section','fish') }}">دليل
                                    الشركات</a></li>
                                <div class="dropdown-divider"></div>
                                    <li><a class="dropdown-item" href="{{ route('front_section_news_last','fish') }}">
                                    الأخبار</a></li>
                                <div class="dropdown-divider"></div>
                                    <!-- <li><a class="dropdown-item"
                                 href="{{ route('front_sections','fish') }}">الاستشاريون</a></li> -->
                                <li><a class="dropdown-item" href="{{ route('front_section_store','fish') }}">سوق
                                    الكناني</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="nav-link dropdown-toggle text-center" data-bs-toggle="dropdown"> الخيول
                                العربية </a>
                            <ul class="submenu dropdown-menu submenu-left">
                                <li><a class="dropdown-item" href="{{ route('front_local_sections','horses') }}">البورصة
                                    اليومية</a></li>
                                <div class="dropdown-divider"></div>
                                <li><a class="dropdown-item" href="{{ route('front_section','horses') }}">دليل
                                    الشركات</a></li>
                                <div class="dropdown-divider"></div>
                                    <li><a class="dropdown-item" href="{{ route('front_section_news_last','horses') }}">
                                    الأخبار</a></li>
                                <div class="dropdown-divider"></div>
                                    <!-- <li><a class="dropdown-item"
                                 href="{{ route('front_sections','horses') }}">الاستشاريون</a></li> -->
                                <li><a class="dropdown-item" href="{{ route('front_section_store','horses') }}">سوق
                                    الكناني</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle"
                       data-toggle="dropdown"
                       href="#" id="servicesDropdown" role="button">
                        الخدمات
                    </a>
                    <div aria-labelledby="servicesDropdown" class="dropdown-menu">
                        <!--                        <a class="dropdown-item" href="#">البورصة العالمية</a>-->
                        <!--                        <div class="dropdown-divider"></div>-->
                        <a class="dropdown-item" href="{{ route('front_shows','1') }}">المعارض</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('front_magazines','1') }}">دلائل ومجلات</a>
                    <!-- <div class="dropdown-divider"></div>-->
                        <!-- <a class="dropdown-item" href="{{ route('front_section_tenders','poultry') }}">مناقصات</a> -->
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('front_ships') }}">حركة السفن</a>
                        <!-- <a class="dropdown-item" href="{{ route('front_academy_courses') }}"> الاكاديمية</a> -->
                    </div>
                </li>
            </ul>

            <form action="{{route('front_nav_search')}}" method="get" class="form-inline my-2 my-lg-0">
                <input class="search-area" name="search" id="searchNavBar" placeholder="بحث" type="search">
                <button class="submit" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            @if(!(Auth::guard('customer')->user()))
                <section class="sign-box">
                    <a class="log-in" href="{{ route('customer_login') }}">الدخول</a>
                    <a class="sign-up" href="{{ route('customer_register') }}">انضم الان</a>

                </section>
                @endif
        </div>
        @if(Auth::guard('customer')->user())
                <section class="profile-box dropdown d-lg-block d-none">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <img class="profile-photo" src="{{asset('uploads/customers/avatar/'.Auth::guard('customer')->user()->avatar)}}" alt="profile pic">
                        <h6> {{ Auth::guard('customer')->user()->name }}</h6>
                        <div class="arrow">
                            <img class="arrow-down" src="{{asset('Front_End/images/down_arrow.svg')}}" alt="arrow down">
                        </div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item dropdown-item-especial" href="#">
                            <img class="profile-photo" src="{{asset('uploads/customers/avatar/'.Auth::guard('customer')->user()->avatar)}}" alt="profile pic">
                            <h6>  {{ Auth::guard('customer')->user()->name }} </h6>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{route('customer_edit')}}">الإعدادات</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">تسجيل الخروج</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="get" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </section>
                @endif
              
        
    </nav>
    <!-- End Navigation Bar -->