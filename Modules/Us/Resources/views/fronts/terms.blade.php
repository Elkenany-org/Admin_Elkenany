
@extends('layouts.front')
@section('style')
<title>شروط الأستخدام</title>
@endsection
@section('content')


<div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card__main">
                    <h1 class="main__title">شروط الأستخدام</h1>
                    <div class="description">
                        <p class="content">
                      
                        {!!$setting->copyrigth!!}
                        </p>
                  
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection