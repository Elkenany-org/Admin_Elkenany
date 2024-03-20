
@extends('layouts.front')
@section('style')

<title>  التحليل الفني</title>
<style type="text/css">

.ajax-load{

background: #fff;
padding: 10px 0px;
width: 100%;
text-align:center;

}

</style> 
@endsection
@section('content')

<!-- Start Header -->

    <!-- End Partners -->
  <!-- Start breadcrumb -->
  <div class="breadcrumb__container nt-0 container">
        <div class="custom__breadcrumb">
            <h1 class="page__title">التحليل الفني</h1>
        </div>
    </div>
    <!-- End breadcrumb -->

<article class="inner-search-box d-lg-block d-none">
    <div class="container">
        <form class="box-tabs">
            <div class="search-box-overlay"></div>
           
            <section class="tabs tabs-1">
                <i class="fas fa-sort-amount-down"></i>
                <h4 class="title sect">الترتيب:</h4>
                <select name="sort" onChange="window.location.href=this.value">
                    <option value="{{ route('front_analysistec') }}" {{ isset($sort) && $sort == "0" ? 'selected'  :'' }}> الابجدي</option>
                    <option value="{{ route('front_analysistec_view') }}"{{ isset($sort) && $sort == "1" ? 'selected'  :'' }}> َالاكثر تداولا</option>
                  
                </select>
            </section>
        </form>
    </div>
</article>
  
    <!-- Start Global Content -->
    <article class="global__container container">
        <div class="row">

           
          

            <!-- Start Left Section -->
            <section class="main__left__container col-lg-12 col-md-12 col-sm-12">
                <div class="new__cards row">
                                        
                    @foreach($news as $value)

                    <div class="col-12">
                        <div class="product__card  regular__hover">
                            <div class="image__card">
                                <img alt='product-image'
                                src="{{asset('uploads/news/avatar/'.$value->image)}}"/>
                            </div>
                            <div class="product__content">
                                <header class="product__title">
                                    <a href="{{ route('front_one_analysistec',$value->id) }}">
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
                <div class="row pagination__container">
                        <div class="col-12">
                        {{$news->links()}}
                        </div>
                    </div>
               

            </section>
            <!-- End Left Section -->
            {{csrf_field()}}
        </div>
    
        
    </article>
    <!-- End Global Content -->

@endsection

@section('script')
<script src="{{asset('Front_End/js/jquery.nice-select.min.js')}}"></script>
<script>
    $('select').niceSelect();
</script>





@endsection