@extends('layouts.front')
    @section('style')
    <!-- My CSS -->
    <link rel="stylesheet" href="{{asset('Front_End/css/nice-select.css')}}">
    <link rel="stylesheet" href="{{asset('Front_End/css/market/market.css')}}">
    <title>   الشات</title>
    @endsection
@section('content')
<!-- Start Header -->


<!-- departments nav -->
<div class="page__tabs nb-0 container">
        <ul class="tabs__list">
        <li class="list__item list__item-3">
            <a class="list__link " href="{{ route('front_section_store',$section->type) }}">السوق</a>
        </li>
        <li class="list__item list__item-3">
            <a  class="list__link" href="{{ route('front_my_ads',$section->id) }}">اعلاناتك</a>
        </li>
        <li class="list__item list__item-3">
        @if(Auth::guard('customer')->user())
            <a  class="list__link active" href="{{ route('front_chats',$section->id) }}">الرسائل</a>
        @else
            <a  class="list__link " href="{{ route('customer_login') }}">الرسائل</a>
        @endif
        </li>
    </ul>
</div>
<!-- departments nav -->
<!-- Start Global Sections -->
<section class="chat__container">
    <div class="chat__content container">
        <div class="row">
            <div class="col-8 col-md-9 padding__small">
                <div class="messages__body">
                    <h1 class="chat__main__header"></h1>
                    <div class="messages__container chek" id="chatContainer">
                       
                    </div>
                </div>
            </div>
            <div class="col-4 col-md-3 padding__small">
                <div class="side__bar">
                    <div class="chats__container">
                        <div class="chats">

                        
                            @foreach($chats as $value)

                            @if($value->user_id == Auth::guard('customer')->user()->id)
                                <div class="chat__body man" data-id="{{$value->id}}">
                                    <img class="img__right"
                                        src="{{asset('uploads/customers/avatar/'.$value->Owner->avatar)}}"
                                        alt="Avatar">
                                    <p class="chat__name"> {{$value->Owner->name}}</p>
                                </div>
                            @endif
                            @if($value->owner_id == Auth::guard('customer')->user()->id)
                                <div class="chat__body man" data-id="{{$value->id}}">
                                    <img class="img__right"
                                        src="{{asset('uploads/customers/avatar/'.$value->User->avatar)}}"
                                        alt="Avatar">
                                    <p class="chat__name"> {{$value->User->name}}</p>
                                </div>
                            @endif
                            @endforeach 
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-9 padding__small masg">
                
            </div>
        </div>
    </div>
</section>
<!-- End Global Sections -->

@endsection

@section('script')
<script src="{{asset('Front_End/js/jquery.nice-select.min.js')}}"></script>
<script src="{{asset('Front_End/js/market_chat.js')}}"></script>
<script>
    $('select').niceSelect();

    $(document).on('click','.man', function(){
        var id =$(this).attr('data-id');
    var data = {
        id : $(this).attr('data-id'),
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('my-chats-massages') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
        $("input[name='id']").val(id)
            console.log(s);
            $('.chek').html(` `);
            $('.masg').html(` `);
            $.each(s,function(k,v){
                if(v.sender_id === {{Auth::guard('customer')->user()->id}}){

                    $('.chek').append(`
                        <div class="message__body__darker">
                            <div class="chat__person">
                                <img class="img"
                                     src="{{asset('uploads/customers/avatar/')}}/${v.send.avatar}"
                                     alt="Avatar">
                                <p class="person__name">${v.send.name}</p>
                            </div>
                            <div class="message margin__right">
                                <p>${v.massage}</p>
                                <span class="time__light">${v.created_at}</span>
                            </div>
                        </div>
                    `);

                }else{
                    $('.chek').append(`
                        <div class="message__body">
                            <div class="chat__person">
                                <img class="img"
                                     src="{{asset('uploads/customers/avatar/')}}/${v.send.avatar}"
                                     alt="Avatar">
                                <p class="person__name">${v.send.name}</p>
                            </div>
                            <div class="message margin__left">
                                <p>${v.massage}</p>
                                <span class="time__dark">${v.created_at}</span>
                            </div>
                        </div>
                    `);
                }
          
    })
    $('.masg').append(` `);
    $('.masg').append(`
    <div class="msg__form">
                    <form action="{{route('front_chats_write')}}" id="formm" method="post">
                        {{csrf_field()}}
                    
                        <input type="hidden" name="id" class="form-control" value="${id}" >
                        <div class="row" dir="rtl">
                            <div class="col-10">
                                <div class="form-group">
                                    <textarea class="form-control"  name="massage" id="message" placeholder="أكتب رسالتك.."
                                              rows="1"></textarea>
                                </div>
                            </div>
                            <div class="col-2">
                                <button type="button" class="submit__button clic" id="sendMsgButton">إرسال
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                    `);
    $('.chek').animate({ scrollTop: $('.chek')[0].scrollHeight}, 1000);
	}});
});



$(document).on('click', '.clic' , function(e){
  e.preventDefault();
  jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
    $.ajax(
    {
        url: "{{route('front_chats_write')}}",
        type: 'post',
        data:  $('#formm').serialize(),
        success: function (v,status){
            $("textarea[name='massage']").val('')
            $("textarea[name='massage']").html('')
            
          console.log(v)

          $('.chek').append(`
                        <div class="message__body__darker">
                            <div class="chat__person">
                                <img class="img"
                                     src="{{asset('uploads/customers/avatar/')}}/${v.send.avatar}"
                                     alt="Avatar">
                                <p class="person__name">${v.send.name}</p>
                            </div>
                            <div class="message margin__right">
                                <p>${v.massage}</p>
                                <span class="time__light">${v.created_at}</span>
                            </div>
                        </div>
                    `);
                    $('.chek').animate({ scrollTop: $('.chek')[0].scrollHeight}, 1000);
        
        }
    });


});
</script>

@endsection