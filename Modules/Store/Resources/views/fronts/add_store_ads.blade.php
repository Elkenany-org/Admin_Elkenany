@extends('layouts.front')
    @section('style')
    <!-- My CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="{{asset('Front_End/css/nice-select.css')}}">
    <link rel="stylesheet" href="{{asset('Front_End/css/market/market.css')}}">
    <title>   إضافة إعلان</title>
    @endsection
@section('content')

<!-- Strat Add Ads Form -->
<article class="add-ads">
@include('../parts.alert')
    <div class="holder">
        <h2 class="main-title">إضافة إعلان</h2>
        <form class="form-box" action="{{route('front_store_store_ads')}}" method="post" enctype="multipart/form-data">
					{{csrf_field()}}
            <input type="hidden" name="section_id" class="form-control" value="{{$section->id}}" >
            <input class="ad-name inputs" name="title" type="text" placeholder="اسم الإعلان" required>
            <input class="ad-price inputs" type="number"  name="salary" placeholder="السعر" required>
            <input class="ad-address inputs" name="address" type="text" placeholder="العنوان" required>
            <input class="ad-number inputs" name="phone" type="number" placeholder="الموبايل" required>
            <textarea class="ad-address inputs" name="desc" rows="5" placeholder="تفاصيل الاعلان" required></textarea>
            <div class="input-images"></div>
       
            <span class="contact-me">تواصل معي عن طريق</span>
            <section class="radio-box"  >
                <div class="box">
                    <input id="mobile" name="con_type" type="radio" value="الموبايل">
                    <label for="mobile">الموبايل</label>
                </div>
                <div class="box center-box">
                    <input id="msgs" name="con_type" type="radio" value="الرسائل">
                    <label for="msgs">الرسائل</label>
                </div>
                <div class="box">
                    <input id="both" name="con_type" type="radio" value="كلاهما">
                    <label for="both">كلاهما</label>
                </div>
            </section>
      
            @if(Auth::guard('customer')->user())
               <input id="submit" class="submit" type="submit" value="إضافة الإعلان">
            @endif
        </form>
    </div>
</article>
<!-- End Add Ads Form -->
@endsection

@section('script')
<script src="{{asset('Front_End/js/jquery.nice-select.min.js')}}"></script>
<script src="{{asset('Front_End/js/market/image-uploader.js')}}"></script>
<script>
    $('select').niceSelect();


</script>

<script>
    $(document).ready(function () {
        $('.input-images').imageUploader();
        $('select').niceSelect();
    });
</script>

@endsection