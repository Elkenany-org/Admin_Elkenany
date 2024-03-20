
@extends('layouts.front')
@section('style')
<title>     اتصل بنا</title>
@endsection
@section('content')


<div class="container">
    <div class="row">   
        <div class="col-12">
            <div class="card__main">
                    <h1 class="main__title">أتصل بنا</h1>
                    <div class="description">
                        <p class="content">
                        {{$main->desc}}
                        </p>

                        
                    </div>
                </div>
                <div class="card__body__contact">
   
                    <div class="row justify-content-between">
                        <div class="col-12 col-md-6">
                            <div class="small__box">
                                <iframe allowfullscreen=""
                                        loading="lazy"
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3419.1663659006135!2d31.396551984633955!3d31.02161417870295!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14f79dab646f757f%3A0x3241a361fb0f88eb!2z2KfZhNmD2YbYp9mG2Yog2KzYsdmI2Kg!5e0!3m2!1sar!2seg!4v1628257010927!5m2!1sar!2seg"
                                        style="border:0;width: 100%;height: 100%"
                                ></iframe>
                            </div>
                        </div>
                        
                        @include('../parts.alert')
                        <div class="col-12 col-md-6 margin__top">
                            <div class="small__box">
                                <div class="form__container">
                                <form class="form__box row justify-content-center"action="{{route('front_add_contuct')}}" method="post" enctype="multipart/form-data">
                                        {{csrf_field()}}
                                
                                    <input class="inputs" name="name" placeholder="الأسم" type="text">
                                    <input class="inputs" name="email" placeholder="الإيميل" type="text">
                                    <input class="inputs" name="phone" placeholder="رقم الموبيل" type="text">
                                    <input class="inputs"  name="job" placeholder="الصفة الوظيفية" type="text">
                                    <input class="inputs" name="company" placeholder="أسم الشركة" type="text">
                                    <textarea class="inputs" name="desc" placeholder="التفاصيل" rows="5"></textarea>
                                    <button class="btn__submit" type="submit">إرسال</button>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>

                      <!-- End Company Details -->
             <!-- End Company Details -->
             <div class="row justify-content-between">
                    <div class="col-12">
                        <div class="box__data">
                            <div class="box__title">
                                        <span class="title__icon">
                                         <svg fill="#000000" height="24px" viewBox="0 0 24 24"
                                              width="24px" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0V0z"
                                                                                                    fill="none"/><path
                                                 d="M12 1.95c-5.52 0-10 4.48-10 10s4.48 10 10 10h5v-2h-5c-4.34 0-8-3.66-8-8s3.66-8 8-8 8 3.66 8 8v1.43c0 .79-.71 1.57-1.5 1.57s-1.5-.78-1.5-1.57v-1.43c0-2.76-2.24-5-5-5s-5 2.24-5 5 2.24 5 5 5c1.38 0 2.64-.56 3.54-1.47.65.89 1.77 1.47 2.96 1.47 1.97 0 3.5-1.6 3.5-3.57v-1.43c0-5.52-4.48-10-10-10zm0 13c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"/></svg>
                                        </span>
                                <p class="title">تواصل معنا</p>
                            </div>
                            <div class="box__body">
                                <div class="box__element">
                                @foreach($all as  $value)
                                    <p class="title">
                                                 <span class="span__icon">
                                                   <svg fill="#000000" height="24px"
                                                        viewBox="0 0 24 24" width="24px"
                                                        xmlns="http://www.w3.org/2000/svg"><path
                                                           d="M0 0h24v24H0V0z" fill="none"/><path
                                                           d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 2.88-2.88 7.19-5 9.88C9.92 16.21 7 11.85 7 9z"/><circle
                                                           cx="12" cy="9" r="2.5"/></svg>
                                                 </span>
                                        <span>
                                                        عنوان {{$value->name}}
                                                    </span>
                                    </p>
                                    <div class="body">
                                        <ul class="body__list">
                                            <li class="single__element">
                                            <a class="element__content"
                                           href="https://maps.google.com/?q={{$value->latitude}},{{$value->longitude}}"
                                           rel="noreferrer" target="_blank">{{$value->address}}</a>
                                            </li>
                                           
                                        </ul>
                                    </div>

                                @endforeach 
                                  

                                </div>
                                <div class="box__element">
                                    @foreach($all as  $value)
                                        <p class="title">
                                                    <span class="span__icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                                                             width="20px" height="20px"
                                                             viewBox="0 0 31 31"
                                                             fill="#000000">
                                                            <path d="M0 0h24v24H0V0z" fill="none"/>
                                                        <path d="M 16 2.59375 L 15.28125 3.28125 L 2.28125 16.28125 L 3.71875 17.71875 L 5 16.4375 L 5 28 L 14 28 L 14 18 L 18 18 L 18 28 L 27 28 L 27 16.4375 L 28.28125 17.71875 L 29.71875 16.28125 L 16.71875 3.28125 Z M 16 5.4375 L 25 14.4375 L 25 26 L 20 26 L 20 16 L 12 16 L 12 26 L 7 26 L 7 14.4375 Z"/></svg>

{{--                                                    <svg fill="#000000" height="20px" viewBox="0 0 24 24" width="20px"--}}
{{--                                                        xmlns="http://www.w3.org/2000/svg"><path--}}
{{--                                                            d="M0 0h24v24H0V0z" fill="none"/><path--}}
{{--                                                            d="M6.54 5c.06.89.21 1.76.45 2.59l-1.2 1.2c-.41-1.2-.67-2.47-.76-3.79h1.51m9.86 12.02c.85.24 1.72.39 2.6.45v1.49c-1.32-.09-2.59-.35-3.8-.75l1.2-1.19M7.5 3H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.49c0-.55-.45-1-1-1-1.24 0-2.45-.2-3.57-.57-.1-.04-.21-.05-.31-.05-.26 0-.51.1-.71.29l-2.2 2.2c-2.83-1.45-5.15-3.76-6.59-6.59l2.2-2.2c.28-.28.36-.67.25-1.02C8.7 6.45 8.5 5.25 8.5 4c0-.55-.45-1-1-1z"/></svg>--}}
                                                    </span>
                                            <span>
                                            {{$value->name}}
                                                </span>
                                        </p>
                                        <div class="body">
                                            <ul class="body__list">
                                                @foreach($value->phones() as $q)
                                                    <li class="single__element">
                                                        <a class="element__content"
                                                        href="tel:{{$q}}"
                                                        rel="noreferrer" target="_blank"> أرضي: {{$q}}</a>
                                                    </li>
                                                @endforeach
                                                @foreach($value->faxs() as $f)
                                                    <li class="single__element">
                                                        <a class="element__content"
                                                        href="tel:{{$f}}"
                                                        rel="noreferrer" target="_blank"> الفاكس: {{$f}}</a>
                                                    </li>
                                                @endforeach
                                                @foreach($value->mobiles() as $p)
                                                    <li class="single__element">
                                                        <a class="element__content"
                                                        href="tel:{{$p}}"
                                                        rel="noreferrer" target="_blank"> الهاتف: {{$p}}</a>
                                                    </li>
                                                @endforeach
                                                @foreach($value->emails() as $p)
                                                    <li class="single__element">
                                                        <a class="element__content"
                                                        href="mailto:{{$p}}"
                                                        rel="noreferrer" target="_blank"> البريد الإلكتروني: {{$p}}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach 
                                  
                                </div>
{{--                                <div class="box__element">--}}
{{--                                    <p class="title">--}}
{{--                                                 <span class="span__icon">--}}
{{--                                                      <svg fill="#000000" height="20px" viewBox="0 0 24 24" width="20px"--}}
{{--                                                           xmlns="http://www.w3.org/2000/svg"><path--}}
{{--                                                              d="M0 0h24v24H0V0z" fill="none"/><path--}}
{{--                                                              d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6zm-2 0l-8 5-8-5h16zm0 12H4V8l8 5 8-5v10z"/>--}}
{{--                                                      </svg>--}}
{{--                                                 </span>--}}
{{--                                        <span>--}}
{{--                                                    البريد الإلكتروني--}}
{{--                                            </span>--}}
{{--                                    </p>--}}
{{--                                    <div class="body">--}}
{{--                                        <ul class="body__list">--}}
{{--                                            @foreach($value->emails() as $q)--}}
{{--                                            <li class="single__element">--}}
{{--                                                <a class="element__content"--}}
{{--                                                href="mailto:{{$q}}"--}}
{{--                                                   rel="noreferrer" target="_blank">{{$q}}</a>--}}
{{--                                            </li>--}}
{{--                                            @endforeach--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
                
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection