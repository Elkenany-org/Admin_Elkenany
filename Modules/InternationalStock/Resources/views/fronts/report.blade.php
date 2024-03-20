
@extends('layouts.front')
@section('style')
<link rel="stylesheet" href="{{asset('Front_End/css/companies_details.css')}}">
<link rel="stylesheet" href="{{asset('Front_End/css/nice-select.css')}}">
<title>  {{$new->title}}</title>
@endsection
@section('content')



<section class="global__container news__container container">
    <div class="row">
     
        {{csrf_field()}}

        <div class="col-12">
                <div class="news__container-content">
                    <div class="news__header">
                    <img alt="" src="{{asset('uploads/news/avatar/'.$new->image)}}"/>
                    </div>
                    <div class="content">
                        <h1 class="content__title">{{$new->title}}</h1>
                        <div class="content__description">
                        
                           
                            {!!$new->desc!!}
                          
                        </div>
                    </div>
                </div>
                <div class="read__more">
                    <h2 class="read__more-title">إقرا أيضا :</h2>
                    <div class="new__cards row">
                        @foreach($news as $value)
                            <div class="col-12 col-lg-6">
                                <div class="product__card min-sm-0  regular__hover">
                                    <div class="image__card semi__image">
                                        <img alt='product-image'
                                            src="{{asset('uploads/news/avatar/'.$value->image)}}"/>
                                    </div>
                                    <div class="product__content">
                                        <header class="product__title semi__title">
                                            <a href="{{ route('front_one_reports',$value->id) }}">
                                            {{$value->title}}
                                            </a>
                                        </header>
                                        <section class="product__bottom justify-content-end">
                                            <section class="date__box">
                                                <span class="date">{{$value->created_at}}</span>
                                            </section>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

    </div>
</section>

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
});</script>
@endsection