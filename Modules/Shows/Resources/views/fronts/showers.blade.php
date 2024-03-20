
@extends('layouts.front')
@section('style')

<title>  العارضون</title>
@endsection
@section('content')



    <!-- Start Header -->
    <header class="banner__header">      
    <div class="container-fluid one-full-slider-banner">
        @foreach($ads as $ad)
            <a href="{{$ad->link}}" target="_blank"><img alt="banner" class="banner" src="{{asset('uploads/full_images/'.$ad->image)}}"></a>
        @endforeach 
    </div>
</header>

<article class="partners slider my-4 mt-5">
        <div class="container-fluid logos__holder ">

            <section class="partners-slider" dir='rtl'>
            @foreach($logos as $logo)
                <div class="item">
                <a href="{{$logo->link}}" target="_blank" class="logo-holder"><img alt="partner logo" src="{{asset('uploads/full_images/'.$logo->image)}}"></a>
                <div class="wall"></div>
                </div>
            @endforeach 
             
            </section>
        </div>
    </article>
    <!-- End Partners -->
  <!-- page tabs -->
  <div class="page__tabs container">
        <ul class="tabs__list">
            <li class="list__item list__item-4">
                <a class="list__link" href="{{ route('front_one_show',$show->id) }}">عن المعرض</a>
            </li>

            <li class="list__item list__item-4">
                <a class="list__link " href="{{ route('front_one_show_review',$show->id) }}">المراجعات</a>
            </li>

            <li class="list__item list__item-4">
                <a class="list__link active" href="{{ route('front_one_show_Showers',$show->id) }}">العارضون</a>
            </li>

            <li class="list__item list__item-4">
                <a class="list__link " href="{{ route('front_one_show_speakers',$show->id) }}">المتحدثون</a>
            </li>
        </ul>
    </div>
    <!-- page tabs -->
    <article class="global__container container">
        <div class="row">
            @foreach($show->Showers as $value)
            <div class="col-6 col-md-4 col-lg-3">
                    <div class="person__card">
                        <div class="person__image">
                            <img alt="" class="image" src="{{asset('uploads/show/shower/'.$value->image)}}">
                        </div>
                        <div class="person__card__body">
                            <div class="name">{{$value->name}} </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </article>

</div>

@endsection

@section('script')
<script src="{{asset('Front_End/js/jquery.nice-select.min.js')}}"></script>


<script>

</script>
@endsection