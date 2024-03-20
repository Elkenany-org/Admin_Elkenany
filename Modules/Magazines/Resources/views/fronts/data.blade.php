
@foreach($magazines as $value)
    <!-- Start One Card -->
    <a href="{{ route('front_magazine',$value->id) }}" class="cards one-card">
        <section class="top">
            <section class="right-logo d-lg-block d-none">
                <img src="{{asset('uploads/magazine/images/'.$value->image)}}" alt="logo">
            </section>
            <section class="left-content">
                <h2 class="main-title"> {{$value->name}}</h2>
                <div class="logo-holder d-lg-none d-block">
                    <img src="{{asset('uploads/magazine/images/'.$value->image)}}" alt="logo">
                </div>
                <div class="rate-box">
                    <div class="rating-readonly" data-rate-value="{{$value->rate}}"></div>
                </div>
                <p>
                {{$value->short_desc}}
                </p>
            </section>
        </section>
        <section class="bottom">
            <section class="address-box">
                <i class="fas fa-map-marker-alt"></i>
                <span class="address">    {{$value->address}}</span>
            </section>
            <span class="more-details">اعرف أكتر</span>
        </section>
        <div class="advertisements-top">
            الراعي
        </div>
        <div class="advertisement">
            الراعي
        </div>
    </a>
    <!-- End One Card -->
@endforeach
                        
                 