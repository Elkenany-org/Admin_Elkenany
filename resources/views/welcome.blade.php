
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


    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="This is Description Area" name="description">
    <link href="{{asset('Front_End/images/favicon.png')}}" rel="icon">
    <title> الكناني | الرئيسية</title>
    <link rel="stylesheet" href="{{asset('Front_End/css/vendors/bootstrap.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('Front_End/css/vendors/all.min.css')}}">
    <link href="{{asset('Front_End/css/vendors/splide.min.css')}}" rel="stylesheet">
    <link href="{{asset('Front_End/css/vendors/splide-default.min.css')}}" rel="stylesheet">
    <link href="{{asset('Front_End/css/styles.css')}}" rel="stylesheet">
    <style type="text/css">

.elknany__title {
  visibility: hidden;
  margin: 0;
  font-size: 1px;
  display: inline; }

.home__page__container {
  direction: rtl;
  width: 100vw;
  height: 450px;
  min-height: 450px;
  margin-top: 0;
  position: relative;
  z-index: 1;
  transition: 0.2s ease-in-out; }
  @media (min-width: 300px) and (max-width: 765px) {
    .home__page__container {
      min-height: calc(100vh - 70px); } }
  @media (min-width: 768px) and (max-width: 990px) {
    .home__page__container {
      height: 300px; } }
  @media (max-width: 767px) {
    .home__page__container {
      height: 480px; } }
  .home__page__container .home__page__slider {
    width: 100vw;
    height: 450px;
    min-height: 450px; }
    .home__page__container .home__page__slider .item {
      height: 60vh;
      min-height: 450px;
      width: 100%; }
      .home__page__container .home__page__slider .item img {
        width: 100%;
        height: 100%; }
    .home__page__container .home__page__slider .header__main__arrow-prev {
      right: 1rem !important; }
    .home__page__container .home__page__slider .header__main__arrow-next {
      left: 2rem !important; }
    .home__page__container .home__page__slider .header__main__pagination {
      bottom: -8rem; }
      @media (min-width: 300px) and (max-width: 765px) {
        .home__page__container .home__page__slider .header__main__pagination {
          bottom: 4rem; } }
  .home__page__container .home__page__search__console {
    position: relative;
    z-index: 5;
    bottom: 48px; }
    .home__page__container .home__page__search__console .content__holder {
      bottom: 12px;
      width: 100%; }
      .home__page__container .home__page__search__console .content__holder .two-way-search-box {
        border-radius: 1.875rem;
        background-color: #ffffff;
        box-shadow: 6px 6px 5px rgba(0, 0, 0, 0.08), 0px 1px 5px rgba(0, 0, 0, 0.3); }
        .home__page__container .home__page__search__console .content__holder .two-way-search-box .head {
          display: flex;
          align-items: center;
          justify-content: center;
          flex-flow: row;
          flex-wrap: nowrap; }
          .home__page__container .home__page__search__console .content__holder .two-way-search-box .head .head__title {
            width: 50%;
            color: #1f6b00;
            font-weight: 700;
            transition: 0.2s ease-in-out;
            border-bottom: 4px solid #3d9c12;
            text-align: center;
            cursor: pointer;
            padding: 0.625rem 0;
            font-size: var(--fs-20, 1.25rem);
            margin: 0; }
            .home__page__container .home__page__search__console .content__holder .two-way-search-box .head .head__title:hover {
              background-color: #EEEEEE;
              border-bottom: 4px solid #FFAA00 !important;
              color: #FFAA00 !important; }
            @media (max-width: 767px) {
              .home__page__container .home__page__search__console .content__holder .two-way-search-box .head .head__title {
                font-size: var(--fs-18, 1.125rem); } }
          .home__page__container .home__page__search__console .content__holder .two-way-search-box .head .active {
            border-bottom: 4px solid #FFAA00; }
            .home__page__container .home__page__search__console .content__holder .two-way-search-box .head .active:hover {
              background-color: #dcdcdc;
              border-bottom: 4px solid #FFAA00 !important;
              color: #FFAA00 !important; }
          .home__page__container .home__page__search__console .content__holder .two-way-search-box .head .title-right {
            border-radius: 0 30px 0 0;
            color: #FFAA00; }
            .home__page__container .home__page__search__console .content__holder .two-way-search-box .head .title-right:hover {
              border-radius: 0 30px 0 0; }
          .home__page__container .home__page__search__console .content__holder .two-way-search-box .head .title-left {
            border-radius: 30px 0 0 0; }
            .home__page__container .home__page__search__console .content__holder .two-way-search-box .head .title-left:hover {
              border-radius: 30px 0 0 0; }
        .home__page__container .home__page__search__console .content__holder .two-way-search-box .body {
          padding: .5em;
          min-height: 75px; }
          @media (min-width: 300px) and (max-width: 765px) {
            .home__page__container .home__page__search__console .content__holder .two-way-search-box .body {
              min-height: 250px; } }
          .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-template-rows: 1fr;
            grid-column-gap: 0;
            grid-row-gap: 0; }
            .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs.tab3 {
              grid-template-columns: repeat(3, 1fr); }
            @media (max-width: 767px) {
              .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs {
                display: block; } }
            .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 {
              position: relative; }
              @media (max-width: 767px) {
                .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 {
                  display: flex;
                  justify-content: flex-start !important;
                  align-items: center; } }
              .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item .nav-link, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 .nav-link {
                display: flex;
                flex-flow: row wrap;
                text-align: right !important;
                justify-content: flex-start !important;
                align-items: center;
                padding: 0.5rem 0rem; }
                @media (min-width: 768px) {
                  .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item .nav-link, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 .nav-link {
                    text-align: center;
                    display: flex;
                    flex-flow: row wrap;
                    justify-content: center !important; } }
                .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item .nav-link .icon-holder .icon, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 .nav-link .icon-holder .icon {
                  height: 35px;
                  width: 35px;
                  display: inline-block;
                  margin-left: .3rem; }
                  @media (max-width: 991px) {
                    .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item .nav-link .icon-holder .icon, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 .nav-link .icon-holder .icon {
                      height: 28px;
                      width: 35px; } }
                .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item .nav-link .title, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 .nav-link .title {
                  font-size: 1rem;
                  color: #1f6b00;
                  font-weight: bold;
                  transition: all .3s;
                  margin: 0; }
                  @media (max-width: 767px) {
                    .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item .nav-link .title, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 .nav-link .title {
                      font-size: .9rem; } }
                .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item .nav-link::after, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 .nav-link::after {
                  color: #1f6b00;
                  transition: all .3s;
                  position: relative;
                  top: 0; }
                  @media (max-width: 767px) {
                    .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item .nav-link::after, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 .nav-link::after {
                      position: absolute;
                      top: 10px;
                      left: 0; } }
                .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item .nav-link:hover .title, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 .nav-link:hover .title {
                  color: #d48f04; }
                .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item .nav-link:hover::after, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 .nav-link:hover::after {
                  color: #d48f04; }
                .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item .nav-link:focus .title, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 .nav-link:focus .title {
                  color: #d48f04; }
                .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item .nav-link:focus::after, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 .nav-link:focus::after {
                  color: #d48f04; }
              .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item .dropdown-menu, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 .dropdown-menu {
                width: 100% !important;
                font-size: 1rem;
                border-color: #1a5302;
                box-shadow: 0 0 4px 0 rgba(0, 0, 0, 0.08), 0 2px 4px 0 rgba(0, 0, 0, 0.12); }
                @media (max-width: 767px) {
                  .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item .dropdown-menu, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 .dropdown-menu {
                    font-size: .9rem; } }
                .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item .dropdown-menu .dropdown-item, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 .dropdown-menu .dropdown-item {
                  color: #1f6b00;
                  padding: 6px 0; }
                  .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item .dropdown-menu .dropdown-item:active, .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 .dropdown-menu .dropdown-item:active {
                    color: #ffffff; }
              .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item:not(:nth-of-type(5)):not(:nth-of-type(10)), .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2:not(:nth-of-type(5)):not(:nth-of-type(10)) {
                border-left: 1px solid #1a5302; }
                @media (max-width: 991px) {
                  .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item:not(:nth-of-type(5)):not(:nth-of-type(10)), .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2:not(:nth-of-type(5)):not(:nth-of-type(10)) {
                    border: 0; } }
            .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 {
              display: none; }
              .home__page__container .home__page__search__console .content__holder .two-way-search-box .body .tabs .nav-item-2 .dropdown-menu {
                width: inherit; }

@media (max-width: 991px) {
  .two-way-search-box {
    border-radius: 20px !important; }
    .two-way-search-box .head .title-right {
      border-radius: 0 20px 0 0 !important; }
    .two-way-search-box .head .title-left {
      border-radius: 20px 0 0 0 !important; }
    .two-way-search-box .body .nav-item {
      width: 100% !important; }
      .two-way-search-box .body .nav-item .nav-link {
        display: flex;
        flex-flow: row wrap;
        text-align: right !important;
        justify-content: right !important;
        align-items: center; }
        .two-way-search-box .body .nav-item .nav-link .icon-holder {
          width: auto !important; } }

@media (max-width: 768px) {
  .two-way-search-box {
    border-radius: 0.9375 !important; }
    .two-way-search-box .head .title-right {
      border-radius: 0 0.9375 0 0 !important; }
    .two-way-search-box .head .title-left {
      border-radius: 0.9375 0 0 0 !important; } }

@media (max-width: 576px) {
  .two-way-search-box {
    border-radius: 10px !important; }
    .two-way-search-box .head .title-right {
      border-radius: 0 0.625rem 0 0 !important; }
    .two-way-search-box .head .title-left {
      border-radius: 0.625rem 0 0 0 !important; }
    .two-way-search-box .body .nav-item {
      width: 100% !important; }
      .two-way-search-box .body .nav-item .nav-link .icon-holder .icon {
        height: 30px !important;
        width: 30px !important; } }

.full__partner__slider {
  margin-top: 6rem;
  text-align: center;
  margin-bottom: 1rem; }
  .full__partner__slider .section__title {
    background-color: #FFAA00;
    border-radius: 20px 0 20px 0;
    display: inline-block;
    padding: 10px 35px;
    font-size: 1.6rem;
    font-weight: bold;
    margin: 3rem auto; }
  .full__partner__slider .item {
    outline: none;
    position: relative; }
    .full__partner__slider .item .logo-holder {
      width: 125px;
      height: 150px;
      border: 1px solid #FFAA00;
      padding: 1.5rem 1rem;
      border-radius: 24px;
      margin: 0 auto;
      display: block;
      outline: none; }
      .full__partner__slider .item .logo-holder img {
        width: 100%;
        height: 100%;
        transition: all .5s;
        border-radius: 12px; }
      .full__partner__slider .item .logo-holder:hover img {
        transform: scale(1.09, 1.09); }
    .full__partner__slider .item .wall {
      height: 90px;
      width: 10px;
      background-color: #FFAA00;
      position: absolute;
      top: 15%;
      left: 0; }
      @media (max-width: 767px) {
        .full__partner__slider .item .wall {
          display: none; } }
  .full__partner__slider .splide__pagination {
    bottom: -2rem !important; }
  @media (max-width: 767px) {
    .full__partner__slider {
      margin-top: 0; }
      .full__partner__slider .section__title {
        margin: 0 auto 1.5rem auto; } }

.splide__arrow svg {
  fill: #1f6b00; }

.splide__pagination li button {
  background: #1f6b00; }
  .splide__pagination li button:hover, .splide__pagination li button.is-active {
    background: #FFAA00; }

.splide__slide img {
  width: 100%;
  height: auto; }
    </style>
</head>

<body>
<div class="content">
    <!-- Start Loading Screen -->
    <article class="d-none loading-screen">
        <div class="chicken-loader">
            <span></span>
        </div>
    </article>
    <!-- End Loading Screen -->

    <!-- Start button to top onclick="topFunction()" -->
    <button class="btn_top" id="myBtn">
        <span class="arrow"></span>
    </button>
    <!-- End button to top -->
    @include('../fronts.sidebar')

<!-- Start Header -->

<!-- Start Header -->
    <header class="home__page__container">
        <div class="home__page__slider">
            <div class="splide" id="image-slider">
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach($gallary as $val)
                            <li class="splide__slide">
                                <img alt="banner" src="{{asset('uploads/main/'.$val->image)}}">
                            </li>
                        @endforeach
                        </ul>
                    </div>
            </div>
        </div>

        <div class="container home__page__search__console">
            <div class="content__holder">
                <div class="content__holder-holder">
                    <section class="two-way-search-box">
                        <section class="head">
                            <h2 class="head__title title-right col-6 active">
                                القطاعات
                            </h2>
                            <h2 class="head__title title-left col-6">
                                الخدمات
                            </h2>
                        </section>
                        <section class="body">
                            <!---------- Start first Tab ----------->
                            <section class="first-tab tabs">
                                    <!-- Start Dropdown Item -->
                                    <section class="nav-item dropdown">
                                        <a aria-expanded="false" aria-haspopup="true"
                                           class="nav-link dropdown-toggle"
                                           data-toggle="dropdown" href="#" id="navbarDropdown" role="button">
                                            <div class="icon-holder mb-lg-2">
                                                <img alt="icon" class="icon" src="{{asset('Front_End/images/chicken_icon.svg')}}">
                                            </div>
                                            <span class="title">الداجني</span>
                                        </a>
                                        <div aria-labelledby="navbarDropdown" class="dropdown-menu">
                                            <a class="dropdown-item"
                                               href="{{ route('front_local_sections','poultry') }}">
                                                البورصة اليومية
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item"
                                               href="{{ route('front_section','poultry') }}">
                                                دليل الشركات
                                            </a>
<!-- 
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item"
                                               href="{{ route('front_sections','poultry') }}">
                                               الإستشاريين
                                            </a> -->
                                          
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('front_section_store','poultry') }}">
                                                سوق الكناني
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('front_section_news','poultry') }}">
                                                الأخبار
                                            </a>
                                        </div>
                                    </section>
                                    <!-- End Dropdown Item -->

                                    <!-- Start Dropdown Item -->
                                    <section class="nav-item dropdown">
                                        <a aria-expanded="false" aria-haspopup="true"
                                           class="nav-link dropdown-toggle"
                                           data-toggle="dropdown"
                                           href="#" id="navbarDropdown" role="button">
                                            <div class="icon-holder mb-lg-2">
                                                <img alt="icon" class="icon" src="{{asset('Front_End/images/cow_icon.svg')}}">
                                            </div>
                                            <span class="title">الحيواني</span>
                                        </a>
                                        <div aria-labelledby="navbarDropdown" class="dropdown-menu">
                                            <a class="dropdown-item"
                                               href="{{ route('front_local_sections','animal') }}">
                                                البورصة اليومية
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item"
                                               href="{{ route('front_section','animal') }}">
                                                دليل الشركات
                                            </a>

                                            <!-- <div class="dropdown-divider"></div>
                                            <a class="dropdown-item"
                                               href="{{ route('front_sections','animal') }}">
                                               الإستشاريين
                                            </a> -->
                                          
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('front_section_store','animal') }}">
                                                سوق الكناني
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('front_section_news','animal') }}">
                                                الأخبار
                                            </a>
                                        </div>
                                    </section>
                                    <!-- End Dropdown Item -->

                                    <!-- Start Dropdown Item -->
                                    <section class="nav-item dropdown">
                                        <a aria-expanded="false" aria-haspopup="true"
                                           class="nav-link dropdown-toggle"
                                           data-toggle="dropdown"
                                           href="#" id="navbarDropdown" role="button">
                                            <div class="icon-holder mb-lg-2">
                                                <img alt="icon" class="icon" src="{{asset('Front_End/images/plant_icon.svg')}}">
                                            </div>
                                            <span class="title">الزراعي</span>
                                        </a>
                                        <div aria-labelledby="navbarDropdown" class="dropdown-menu">
                                            <a class="dropdown-item"
                                               href="{{ route('front_local_sections','farm') }}">
                                                البورصة اليومية
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item"
                                               href="{{ route('front_section','farm') }}">
                                                دليل الشركات
                                            </a>

                                            
                                            <!-- <div class="dropdown-divider"></div>
                                            <a class="dropdown-item"
                                               href="{{ route('front_sections','farm') }}">
                                               الإستشاريين
                                            </a>
                                         -->
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('front_section_store','farm') }}">
                                                سوق الكناني
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('front_section_news','farm') }}">
                                                الأخبار
                                            </a>
                                        </div>
                                    </section>
                                    <!-- End Dropdown Item -->

                                    <!-- Start Dropdown Item -->
                                    <section class="nav-item dropdown">
                                        <a aria-expanded="false" aria-haspopup="true"
                                           class="nav-link dropdown-toggle"
                                           data-toggle="dropdown"
                                           href="#" id="navbarDropdown" role="button">
                                            <div class="icon-holder mb-lg-2">
                                                <img alt="icon" class="icon" src="{{asset('Front_End/images/fish_icon.svg')}}">
                                            </div>
                                            <span class="title">السمكي</span>
                                        </a>
                                        <div aria-labelledby="navbarDropdown" class="dropdown-menu">
                                            <a class="dropdown-item"
                                               href="{{ route('front_local_sections','fish') }}">
                                                البورصة اليومية
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item"
                                               href="{{ route('front_section','fish') }}">
                                                دليل الشركات
                                            </a>

                                            <!-- <div class="dropdown-divider"></div>
                                            <a class="dropdown-item"
                                               href="{{ route('front_sections','fish') }}">
                                               الإستشاريين
                                            </a> -->
                                           
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('front_section_store','fish') }}">
                                                سوق الكناني
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('front_section_news','fish') }}">
                                                الأخبار
                                            </a>
                                        </div>
                                    </section>
                                    <!-- End Dropdown Item -->

                                    <!-- Start Dropdown Item -->
                                    <section class="nav-item dropdown">
                                        <a aria-expanded="false" aria-haspopup="true"
                                           class="nav-link dropdown-toggle"
                                           data-toggle="dropdown"
                                           href="#" id="navbarDropdown" role="button">
                                            <div class="icon-holder mb-lg-2">
                                                <img alt="icon" class="icon" src="{{asset('Front_End/images/horse_icon.svg')}}">
                                            </div>
                                            <span class="title">الخيول العربية</span>
                                        </a>
                                        <div aria-labelledby="navbarDropdown" class="dropdown-menu">
                                            <a class="dropdown-item"
                                               href="{{ route('front_local_sections','horses') }}">
                                                البورصة اليومية
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item"
                                               href="{{ route('front_section','horses') }}">
                                                دليل الشركات
                                            </a>

                                            <!-- <div class="dropdown-divider"></div>
                                            <a class="dropdown-item"
                                               href="{{ route('front_sections','horses') }}">
                                               الإستشاريين
                                            </a> -->
                                           
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('front_section_store','horses') }}">
                                                سوق الكناني
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('front_section_news','horses') }}">
                                                الأخبار
                                            </a>
                                        </div>
                                    </section>
                                    <!-- End Dropdown Item -->
                                </section>
                            <!---------- End first Tab ----------->

                            <!---------- Start Second Tab ----------->
                            <section class="second-tab tabs tab3">

                            <!-- Start Dropdown Item -->
                            <section class="nav-item-2 dropdown-2">
                                <a class="nav-link " href="{{ route('front_shows','1') }}">
                                    <div class="icon-holder mb-lg-3">
                                        <img alt="icon" class="icon" src="{{asset('Front_End/images/showroom.svg')}}">
                                    </div>
                                    <span class="title">معارض</span>
                                </a>
                            </section>
                            <!-- End Dropdown Item -->

                            <!-- Start Dropdown Item -->
                            <section class="nav-item-2 dropdown-2">
                                <a class="nav-link " href="{{ route('front_magazines','1') }}">
                                    <div class="icon-holder mb-lg-3">
                                        <img alt="icon" class="icon" src="{{asset('Front_End/images/newspaper.svg')}}">
                                    </div>
                                    <span class="title">دلائل ومجلات</span>
                                </a>
                            </section>
                            <!-- End Dropdown Item -->
                            <!-- Start Dropdown Item -->
                            <!-- <section class="nav-item-2 dropdown-2">
                                <a class="nav-link " href="{{ route('front_section_tenders','الداجني') }}">
                                    <div class="icon-holder mb-lg-3">
                                        <img alt="icon" class="icon" src="{{asset('Front_End/images/hammer.svg')}}">
                                    </div>
                                    <span class="title"> المناقصات</span>
                                </a>
                            </section> -->
                            <!-- End Dropdown Item -->


                            <!-- Start Dropdown Item -->
                            <section class="nav-item-2 dropdown-2" style="border: 0 !important;">
                                <a class="nav-link " href="{{ route('front_ships') }}">
                                    <div class="icon-holder mb-lg-3">
                                        <img alt="icon" class="icon" src="{{asset('Front_End/images/market_icon.svg')}}">
                                    </div>
                                    <span class="title">حركة السفن</span>
                                </a>
                            </section>
                            <!-- End Dropdown Item -->


                            </section>
                            <!---------- End Second Tab ----------->
                        </section>
                    </section>
                </div>
            </div>
        </div>
    </header>
   
</div>
    <!-- End Header -->

    <!-- Start Partners -->
    <article class="full__partner__slider container-fluid">
        <h2 class="section__title">شركاء النجاح</h2>
        <section class="splide" id="partnerCarousel">
            <div class="splide__track">
                <ul class="splide__list">
                    @foreach($logos as $logo)
                    <li class="splide__slide">
                        <div class="item">
                            <a class="logo-holder" href="{{$logo->link}}" target="_blank">
                                <img alt="partner logo"
                                src="{{asset('uploads/full_images/'.$logo->image)}}">
                            </a>
                            <div class="wall"></div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </section>
    </article>
    <!-- End Partners -->

 
    @include('../fronts.footer')


<script src="{{asset('Front_End/js/vendors/jquery-3.5.1.js')}}"></script>
<script src="{{asset('Front_End/js/vendors/popper.min.js')}}"></script>
<script src="{{asset('Front_End/js/vendors/bootstrap.min.js')}}"></script>
<script src="{{asset('Front_End/js/splide.min.js')}}"></script>
<script src="{{asset('Front_End/js/global.js')}}"></script>
<script>
    $(window).on('load', function(){
        $(window).scrollTop(0);
    });
</script>
<script>
    $(document).ready(function () {

        new Splide('#image-slider', {
            type: 'loop',
            perPage: 1,
            perMove: 1,
            rewind: true,
            direction: 'rtl',
            cover: true,
            width: '100vw',
            height: '450px',
            autoplay: true,
            interval: 5000,
            classes: {
                // Add classes for arrows.
                arrows: 'splide__arrows header__main__arrows',
                arrow: 'splide__arrow header__main__arrow',
                prev: 'splide__arrow--prev header__main__arrow-prev',
                next: 'splide__arrow--next header__main__arrow-next',

                // Add classes for pagination.
                pagination: 'splide__pagination header__main__pagination', // container
                page: 'splide__pagination__page', // each button
            },
        }).mount();
        new Splide('#partnerCarousel', {
            type: 'loop',
            perPage: 4,
            perMove: 1,
            rewind: true,
            direction: 'rtl',
            cover: true,
            width: '100vw',
            autoplay: true,
            interval: 3000,
            padding: {
                right: '1rem',
                left: '1rem',
            },
            breakpoints: {
                1200: {
                    perPage: 4,
                    perMove: 1,
                },
                1100: {
                    perPage: 3,
                    perMove: 1,
                },
                950: {
                    perPage: 2,
                    perMove: 1,
                },
                800: {
                    perPage: 1,
                    perMove: 1,
                },
                600: {
                    perPage: 1,
                    perMove: 1,
                },
                450: {
                    perPage: 1,
                    perMove: 1,
                },
                300: {
                    perPage: 1,
                    perMove: 1,
                },
            },
        }).mount();
        ////////////////Start Switch between search tabs////////////////
        $('.two-way-search-box .title-right').click(function () {
            $(this).css('border-bottom-color', '#FFAA00');

            $('.title-left').css('border-bottom-color', '#3d9c12');
            $('.title-left').css('color', '#1a5302');
            $('.title-right').css('color', '#FFAA00');

            $('.body').css('display', 'none').fadeIn("1000");
            $('.two-way-search-box .dropdown').css('display', 'block');
            $('.two-way-search-box .dropdown-2').css('display', 'none');
        });

        $('.two-way-search-box .title-left').click(function () {
            $(this).css('border-bottom-color', '#FFAA00');

            $('.title-right').css('border-bottom-color', '#3d9c12');
            $('.title-left').css('color', '#FFAA00');
            $('.title-right').css('color', '#1a5302');

            $('.body').css('display', 'none').fadeIn("1000");
            $('.two-way-search-box .dropdown').css('display', 'none');
            $('.two-way-search-box .dropdown-2').css('display', 'block');
        });
////////////////End Switch between search tabs////////////////
    });
</script>

<!-- End Scripts -->
</body>

</html>