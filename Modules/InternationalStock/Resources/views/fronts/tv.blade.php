
@extends('layouts.front')
@section('style')
<title>  tv الكناني</title>
@endsection
@section('content')

<article class="tv__main__container">
        <div class="container">
            <div class="row">

                <!-- Start Left Section -->
                <section class="col-12 col-md-7">
                    <section class="single__episode__container">
                        <div class="single__episode">
                            <div class="main__video">
                                <iframe class="video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen frameborder="0"
                                        src="{{$tv->link}}"
                                        title="YouTube video player"></iframe>
                            </div>
                            <div class="main__content">
                                <h2 class="title main__content-title">  {{$tv->title}}</h2>
                                <p class="body main__content-body">
                                
                                </p>
                            </div>
                        </div>
                    </section>
                    <!-- End Left Section -->
                </section>
                <!-- Start Right Section -->
                <section class="col-12 col-md-5">
                    <div class="other__episodes__container">
                        <h2 class="main__title">
                            الحلقات الأخرى
                        </h2>
                        <div class="other__episodes">
                            <!-- Start One Card -->
                            @foreach($tvs as $value)
                            <a class="episode"
                            data-title       = "{{$value->title}}"
                            data-desc       = "{{$value->desc}}"
                            data-link       = "{{$value->link}}">
                                <img alt="تعريف البورصة العالمية" class="episode__video-mask"
                                     src="{{asset('uploads/news/avatar/'.$value->image)}}">
                                <p class="episode__content">
                                    <span class="episode__content-title">  {{$value->title}}</span>
                          
                                </p>
                            </a>
                            @endforeach
                            <!-- End One Card -->
                          
                          
                        </div>
                    </div>
                </section>
                <!-- End Right Section -->


            </div>
        </div>
    </article>

@endsection

@section('script')

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>tinymce.init({
  selector: '#mydata',
  height: 500,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table paste imagetools wordcount'
  ],
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});

//edit video
$('.episode').on('click',function(){
        var title   = $(this).data('title')
        var desc    = $(this).data('desc')

        var link      = $(this).data('link')



     

        
        $('.title').html(title)
        $(".main__content-body").html(desc)
        $('.video').attr('src',link);


       
    })
</script>
@endsection