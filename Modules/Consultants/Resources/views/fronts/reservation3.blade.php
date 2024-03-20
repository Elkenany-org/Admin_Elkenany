
@extends('layouts.front')
@section('style')

<title>مواعيد الحجز</title>
@endsection
@section('content')

<!-- Strat Reservation -->
<article class="reservation">
    <div class="reservation__holder">
        <h2 class="title">مواعيد الحجز</h2>
        <section class="reservation-box">
            
            <form action="{{route('front_order')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <section class="reservation-box">
                    <h4 class="day day-title">اختر اليوم</h4>
                    <input type="date" id="date" placeholder="mm/dd/yyyy">
                </section>
                <section class="reservation-box">
                    <h4 class="hour-title">اختر الموعد</h4>
                    <select name="id" class="select__main houre">

                    </select>
                </section>
                <div class="reservation-box muy-2">
                @if(Auth::guard('customer')->user())
                <input id="submit" class="submit" type="submit" value="تاكيد الحجز">
                @endif
                </div>
            </form>
        </section>
    </div>
</article>
<!-- End Reservation -->

@endsection

@section('script')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('Front_End/js/datepicker_initialising.js')}}"></script>

<script type="text/javascript">
 //get by date
 $(document).on('change','#date',function(){
        var data = {
            date : $(this).val(),
            id : '{{$doctor->id}}',
            _token     : $("input[name='_token']").val()
        }
      $.ajax(
      {
          url: "{{url('get-reserv-date-meeting')}}",
          type: 'post',
          data    : data,
          success: function (s){
            console.log(s);
            $('.houre').html('');
            
            $.each(s,function(k,v){
                if(v.doctor_orders.length == 0){
                    $('.houre').append(`

                    <option value="${v.id}">من : ${v.time_from} / الي : ${v.time_to}</option>
                    `);
                }
               
	        })
          
          }
        });
    })
</script>

<script src="{{asset('Front_End/js/jquery.nice-select.min.js')}}"></script>

<script>
    $('select').niceSelect();

</script>
@endsection