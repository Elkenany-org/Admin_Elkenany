@extends('layouts.front')
    @section('style')
    <!-- My CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="{{asset('Front_End/css/nice-select.css')}}">
    <link rel="stylesheet" href="{{asset('Front_End/css/market/market.css')}}">
    <title>    {{$ads->title}} تعديل</title>
    @endsection
@section('content')

<!-- Strat Add Ads Form -->
<article class="add-ads">
    <div class="holder">
        <h2 class="main-title">تعديل إعلان</h2>
        <form class="form-box" action="{{route('front_update_store_ads')}}" method="post" enctype="multipart/form-data">
					{{csrf_field()}}
            <input type="hidden" name="id" class="form-control" value="{{$ads->id}}" >
            <input class="ad-name inputs" name="title" type="text" value="{{$ads->title}}" placeholder="اسم الإعلان">
            <input class="ad-price inputs" type="number"  name="salary" value="{{$ads->salary}}" placeholder="السعر">
            <input class="ad-address inputs" name="address" type="text"  value="{{$ads->address}}" placeholder="العنوان">
            <input class="ad-number inputs" name="phone" type="number" value="{{$ads->phone}}" placeholder="الموبايل">
            <textarea class="ad-address inputs" name="desc" rows="5" value="{{$ads->desc}}" placeholder="تفاصيل الاعلان"> {{$ads->desc}}</textarea>
            <div class="input-images"></div>
       
            <span class="contact-me">تواصل معي عن طريق</span>
            <section class="radio-box"  >
                <div class="box">
                    <input id="mobile" name="con_type" type="radio" value="الموبايل"{{ isset($ads) && $ads->con_type == 'الموبايل' ? 'checked'  :'' }}>
                    <label for="mobile">الموبايل</label>
                </div>
                <div class="box center-box">
                    <input id="msgs" name="con_type" type="radio" value="الرسائل"{{ isset($ads) && $ads->con_type == 'الرسائل' ? 'checked'  :'' }}>
                    <label for="msgs">الرسائل</label>
                </div>
                <div class="box">
                    <input id="both" name="con_type" type="radio" value="كلاهما"{{ isset($ads) && $ads->con_type == 'كلاهما' ? 'checked'  :'' }}>
                    <label for="both">كلاهما</label>
                </div>
            </section>
      
               <input id="submit" class="submit" type="submit" value="إضافة الإعلان">
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
        let preloaded = [
            <?php foreach($ads->StoreAdsimages as  $value){ ?>
            {
                id: <?php echo $value->id ?>,
                src: '<?php echo asset('uploads/stores/alboum/'.$value->image) ?>'
            },
            <?php } ?>
        ];
        $('.input-images').imageUploader({
            preloaded: preloaded
        });
        $('select').niceSelect();
    });
</script>

@endsection