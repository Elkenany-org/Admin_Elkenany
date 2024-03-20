@extends('layouts.app')

@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
        /*@import url('https://fonts.googleapis.com/css2?family=Lato:wght@300&display=swap');*/


        .container {
            /*min-height: 100vh;*/
            display: flex;
            justify-content: center;
            align-items: center;
            /*background-color: #eee*/
        }

        .container .card {
            height: 100%;
            width: 100%;
            /*background-color: #fff;*/
            position: relative;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            font-family: 'Lato', sans-serif;
            border-radius: 10px
        }

        .container .card .form {
            width: 100%;
            height: 100%;
            display: flex
        }

        .container .card .left-side {
            width: 20%;
            background-color: #fff;
            /*height: 100%;*/
            /*background-image: url("https://imgur.com/QOg5WCr.jpg");*/
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            display: flex;
            justify-content: right;
            align-items: center;
            color: #000
        }

        .progres_bar {
            counter-reset: progress 0
        }

        .progres_bar li {
            list-style: none;
            counter-increment: progress 1;
            position: relative;
            margin-bottom: 70px;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 1px
        }

        .progres_bar li::before {
            position: absolute;
            content: counter(progress);
            height: 30px;
            width: 30px;
            border-radius: 50%;
            color: #fff;
            z-index: 5;
            border: 2px solid #007bff;
            background-color: #007bff;
            left: -40px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 15px;
            font-weight: 800;
            top: -8px;
            cursor: pointer;
            transition: all 0.5s
        }

        .progres_bar li::after {
            position: absolute;
            content: '';
            height: 90px;
            width: 4px;
            background-color: #007bff;
            left: -25px;
            top: -70px
        }

        .progres_bar li:nth-child(1)::after {
            display: none
        }

        .progres_bar li.active::after {
            background-color: #fff !important
        }

        .progres_bar li.active {
            color: #007bff !important
        }

        .progres_bar li:nth-child(1) {
            color: #007bff
        }

        .progres_bar li:nth-child(1)::before {
            background-color: #fff;
            color: #000 !important
        }

        .progres_bar li.active::before {
            background-color: #fff !important;
            color: #000 !important
        }

        .container .card .right-side {
            width: 80%;
            background-color: #fff;
            height: 100%;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            box-sizing: border-box;
            padding: 20px
        }

        .main {
            display: none
        }

        .active {
            display: block !important
        }

        .manage {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center
        }

        .manage p {
            font-size: 14px;
            margin-top: 10px
        }

        .input_div {
            margin-top: 40px;
            display: flex;
            gap: 20px;
            width: 100%
        }

        .input_div textarea {
            /*height: 90px;*/
            width: 100%;
            outline: 0;
            border: 1px solid #b1b1b1;
            border-radius: 5px;
            padding: 5px;
            resize: none;
            box-sizing: border-box;
            transition: all 0.5s
        }

        .input_text {
            position: relative;
            width: 100%
        }

        input[type="text"] {
            height: 45px;
            width: 100%;
            outline: 0;
            border: 1px solid #b1b1b1;
            border-radius: 5px;
            padding: 5px;
            box-sizing: border-box;
            transition: all 0.5s
        }

        input[type="password"] {
            height: 45px;
            width: 100%;
            outline: 0;
            border: 1px solid #b1b1b1;
            border-radius: 5px;
            padding: 5px;
            box-sizing: border-box;
            transition: all 0.5s;
            padding-right: 30px
        }

        .input_text select {
            height: 45px;
            width: 100%;
            outline: 0;
            border: 1px solid #b1b1b1;
            border-radius: 5px;
            padding: 5px;
            box-sizing: border-box;
            transition: all 0.5s;
            cursor: pointer;
            color: #007bff
        }

        .input_text select option:nth-child(1) {
            /*display: none*/
        }

        input[type="number"] {
            height: 45px;
            width: 100%;
            outline: 0;
            border: 1px solid #b1b1b1;
            border-radius: 5px;
            padding: 5px;
            box-sizing: border-box;
            transition: all 0.5s
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0
        }

        .shown_name {
            color: green !important
        }

        .fa-eye-slash {
            position: absolute;
            top: 14px;
            right: 7px;
            cursor: pointer;
            transition: all 0.5s;
            color: #b1b1b1
        }

        .fa-eye {
            position: absolute;
            top: 14px;
            right: 7px;
            cursor: pointer;
            transition: all 0.5s;
            color: #b1b1b1
        }

        .fa-eye {
            position: absolute;
            top: 14px;
            right: 7px;
            cursor: pointer;
            transition: all 0.5s;
            color: #b1b1b1
        }

        .input_text label {
            pointer-events: none;
            position: absolute;
            top: 14px;
            right: 5px;
            font-size: 14px;
            color: #b1b1b1;
            transition: all 0.5s;
            text-align: right !important;
        }

        .input_text input:focus {
            border: 1px solid #007bff !important
        }
        .input_text textarea:focus {
            border: 1px solid #007bff !important
        }

        .input_text input:focus~label,
        .input_text input:valid~label {
            top: -25px;
            right: 0;
            color: #007bff;
            font-weight: 700;
            text-align:right;
        }

        .input_text textarea:focus~label,
        .input_text textarea:valid~label {
            top: -25px;
            right: 0;
            color: #007bff;
            font-weight: 700;
            text-align:right;
        }

        .agree {
            margin-top: 30px;
            display: flex;
            gap: 5px;
            width: 100% !important;
            align-items: center
        }

        .agree span {
            height: 20px;
            width: 20px !important;
            border-radius: 3px;
            border: 1px solid #b1b1b1;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.5s;
            text-align: center
        }

        .agree span i {
            width: 100%;
            margin-top: 1px;
            color: #fff
        }

        .agree p {
            font-size: 14px
        }

        .agree p a {
            text-decoration: none
        }

        .agree_green {
            background-color: green !important
        }

        .agree_submit {
            margin-top: 30px;
            display: flex;
            gap: 5px;
            width: 100% !important
        }

        .agree_submit span {
            height: 18px;
            width: 20px !important;
            border-radius: 3px;
            border: 1px solid #b1b1b1;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.5s
        }

        .agree_submit span i {
            width: 100%;
            margin-top: 1px;
            color: #fff
        }

        .agree_submit p {
            font-size: 14px
        }

        .agree_submit_green {
            background-color: green !important
        }

        .button {
            display: flex;
            justify-content: end;
            margin-top: 20px
        }

        .button button {
            height: 45px;
            width: 30%;
            border-radius: 5px;
            outline: 0;
            border: none;
            cursor: pointer;
            transition: all 0.5s;
            color: #fff;
            font-size: 15px;
            background-color: #007bff
        }

        .button button:hover {
            background-color: #fff !important;
            border: 1px solid #007bff !important;
            color: #007bff !important
        }

        .step_1 {
            margin-top: 60px
        }

        .step_2 {
            gap: 10px
        }

        .m_top {
            margin-top: 64px
        }

        .step_3 {
            margin-top: 112px
        }

        .step_4 {
            margin-top: 117px
        }

        .h4_txt {
            text-align: center
        }

        .checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: #7ac142;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards
        }

        .checkmark {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: block;
            stroke-width: 2;
            stroke: #fff;
            stroke-miterlimit: 10;
            margin: 10% auto;
            box-shadow: inset 0px 0px 0px #7ac142;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both
        }

        .checkmark__check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards
        }

        @keyframes stroke {
            100% {
                stroke-dashoffset: 0
            }
        }

        @keyframes scale {

            0%,
            100% {
                transform: none
            }

            50% {
                transform: scale3d(1.1, 1.1, 1)
            }
        }

        @keyframes fill {
            100% {
                box-shadow: inset 0px 0px 0px 30px #7ac142
            }
        }

        .warning {
            border: 1px solid red !important
        }

        @media (max-width:750px) {
            .container .card {
                max-width: 350px;
                height: auto;
                margin: 50px 0
            }

            .container .card .right-side {
                width: 100%;
                border-radius: 10px
            }

            .container .card .left-side {
                display: none
            }

            .input_div {
                display: block
            }

            .input_text {
                margin-top: 40px
            }
        }

    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="m-0" style="display: inline;">إضافة إشعار جديد<i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
                </div>
                <div class="card-body">
                    <div class="container">
                        <div class="card">
                            <div class="form">
                                <div class="left-side" style="margin-top: 30px">

                                    <ul class="progres_bar">
                                        <li><i class="fas fa-building"></i> شركه </li>
                                        <li><i class="fas fa-calendar-alt"></i> جدوله </li>
                                        <li><i class="fas fa-bell"></i> الإشعار </li>
                                    </ul>
                                </div>
                                <div class="right-side ">
                                    <form method="POST" action="{{route('storenotification')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="main active">
                                            <div class="input_div col-md-12">
                                                <div class="input_text col-md-6">
                                                    <input type="search" id="search" class="form-control "><label>شركه</label>
                                                </div>

                                            </div>
                                            <div class="input_div col-md-12">
                                                <div class="card col-md-6" id="card-search" style="display: none;padding: 10px">

                                                </div>
                                            </div>

                                            <div class="button step_1">
                                                <button class="next_btn" type="button">التالي</button>
                                            </div>
                                        </div>
                                        <div class="main">
                                            <div class="input_div">
                                                <div class="input_text">
                                                    <select class="col-md-6" name="duration_type" id="duration_type">
                                                        @foreach(config('notification.type') as $k => $v)
                                                            <option value="{{$k}}">{{$v}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="scheduling" style="display: none">
                                                <div class="input_div col-md-6">
                                                    <div class="input_text">
                                                        <input type="date" name="notification[0][date_at]" min="{{date('Y-m-d')}}" id="date_schedule" class="written_name form-control"><label>تاريخ</label>
                                                    </div>
                                                </div>
                                                <div class="input_div col-md-6">
                                                    <div class="input_text">
                                                        <input type="time" name="notification[0][time_at]" id="time_schedule" class="written_name form-control"><label>وقت</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="daily" style="display: none">
                                                <div class="input_div" style="width: 50%">
                                                    <label>وقت</label>
                                                    <div class="input_text">
                                                        <input type="time" name="time_at" id="time_daily" class="written_name form-control">
                                                    </div>
                                                </div>
                                                <div class="input_div">
                                                    <label>من</label>
                                                    <div class="input_text">
                                                        <input type="date" min="{{date('Y-m-d')}}" id="from" class="written_name form-control">
                                                    </div>
                                                    <label>إلي</label>
                                                    <div class="input_text">
                                                        <input type="date" min="{{date('Y-m-d')}}" id="to" class="written_name form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="custom" style="display: none">
                                                <div class="input_div" style="width: 50%">
                                                    <label>وقت</label>
                                                    <div class="input_text">
                                                        <input type="time" name="time_at" id="time_custom" class="written_name form-control">
                                                    </div>
                                                </div>
                                                <div class="input_div">
                                                    <div class="">
                                                        <input type="checkbox" value="Saturday" id="Saturday" class="written_name day"> <label for="Saturday"> السبت</label>
                                                        <input type="checkbox" value="Sunday" id="Sunday" class="written_name day"> <label for="Sunday"> الأحد</label>
                                                        <input type="checkbox" value="Monday" id="Monday" class="written_name day"> <label for="Monday"> الاثنين</label>
                                                        <input type="checkbox" value="Tuesday" id="Tuesday" class="written_name day"> <label for="Tuesday"> الثلاثاء</label>
                                                        <input type="checkbox" value="Wednesday" id="Wednesday" class="written_name day"> <label for="Wednesday"> الاربعاء</label>
                                                        <input type="checkbox" value="Thursday" id="Thursday" class="written_name day"> <label for="Thursday"> الخميس</label>
                                                        <input type="checkbox" value="Friday" id="Friday" class="written_name day"> <label for="Friday"> الجمعه</label>
                                                    </div>
                                                </div>
                                                <span class="text-danger" style="display: none" id="error-msg">*يجب تحديد واحده علي الاقل*</span>
                                                <div class="input_div">
                                                    <div class="input_text">
                                                        <input type="date" name="date_from" min="{{date('Y-m-d')}}" id="date_from" class="written_name form-control"><label>من</label>
                                                    </div>
                                                    <div class="input_text">
                                                        <input type="date" name="date_to" min="{{date('Y-m-d')}}" id="date_to" class="written_name form-control"><label>إلي</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="button step_2 m_top"> <button class="prev_btn" type="button">السابق</button> <button class="next_btn" type="button">التالي</button> </div>
                                        </div>
                                        <div class="main">
                                            <div class="content-notification">

                                            </div>
                                            <div class="content-basic" id="content_basic">
                                                <div class="input_div">
                                                    <div class="input_text">
                                                        <input class="written_name" name="notification[0][title]" id="title" type="text" require required> <label>العنوان</label>
                                                    </div>
                                                </div>
                                                <div class="input_div">
                                                    <div class="input_text">

                                                        <textarea class="written_name" name="notification[0][body]" id="body" require required></textarea><label>الوصف</label>
                                                    </div>
                                                </div>
                                                <div class="input_div">
                                                    <div class="input_text">
                                                        <input class="written_name" name="notification[0][image]" type="file"><label>صوره</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="button step_2 step_4">
                                                <button class="prev_btn" type="button">السابق</button>
                                                <button class="sbmt_btn" >حفظ</button>
                                            </div>

                                        </div>
                                    </form>
                                    <div class="main ">
                                        <div class="manage">
                                            <h3>تم الحفظ <span class="shown_name"></span></h3>
                                            <p></p>
                                        </div> <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" /> </svg>
                                        <div class="h4_txt">
                                            <h4></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" crossorigin="anonymous"></script>
    <script>
        var next_click=document.querySelectorAll(".next_btn");
        var prev_click=document.querySelectorAll(".prev_btn");
        var sbmt_click=document.querySelectorAll(".sbmt_btn");
        var main_page=document.querySelectorAll(".main");
        var p_bar =document.querySelectorAll(".progres_bar li");
        var written_name=document.querySelector(".written_name");
        var shown_name=document.querySelector(".shown_name");

        let formnumber=0;


        next_click.forEach(function(btn){
            btn.addEventListener('click',function(){
                if(!validate_form()){
                    return false;
                }
                formnumber++;
                update_form();
                progress_forward();
            });
        });

        prev_click.forEach(function(btn){
            btn.addEventListener('click',function(){
                formnumber--;
                update_form();
                progress_backward();
            });
        });

        sbmt_click.forEach(function(btn){
            btn.addEventListener('click',function(){
                if(!validate_form()){
                    return false;
                }
                formnumber++;
                update_form();
                shown_name.innerHTML=written_name.value;
            });
        });

        function progress_forward(){
            p_bar[formnumber].classList.add('active');
        }

        function progress_backward(){
            var f_num = formnumber+1;
            p_bar[f_num].classList.remove('active');
        }



        function update_form(){
            main_page.forEach(function(main_pages){
                main_pages.classList.remove('active');
            });
            main_page[formnumber].classList.add('active');
        }



        var getDaysBetweenDates = function(startDate, endDate) {
            var now = startDate.clone(), dates = [];

            while (now.isSameOrBefore(endDate)) {
                dates.push(now.format('YYYY-MM-DD'));
                now.add(1, 'days');
            }
            return dates;
        };


// daily

        var dateFrom, dateTo, time, startDate, endDate;

                $('#from').change(function(e){
                    dateFrom = this.value;

                    startDate = moment(dateFrom);
                });

                $('#to').change(function(e){
                    dateTo = this.value;

                    endDate = moment(dateTo);

                    const dates = getDaysBetweenDates(startDate, endDate);
                    buildFormDaily(dates)
                });

                $('#time_daily').change(function(e){
                    time = this.value;
                    const dates = getDaysBetweenDates(startDate, endDate);
                    buildFormDaily(dates)
                });

        function buildFormDaily(dates)
        {
            $('.content-notification').empty();
            dates.forEach(function (date,k) {
                var key = parseInt(k+1);
                var html = '';
                html += '<div class="card" style="padding: 10px">'+
                    '<p><label>التاريخ :</label>'+date+'</p>'+
                    '<div class="row">'+
                    '<div class="input_div col-md-4" style="margin-top:0">'+
                    '<div class="input_text">'+
                    '<input class="written_name form-control" name="notification['+key+'][title]" type="text" require required> <label>العنوان</label>'+
                    '<input class="written_name form-control" name="notification['+key+'][date_at]" value="'+date+'" type="hidden" > '+
                    '<input class="written_name form-control" name="notification['+key+'][time_at]" value="'+time+'" type="hidden" > '+
                    '</div>'+
                    '</div>'+
                    '<div class="input_div col-md-6" style="margin-top:0">'+
                    '<div class="input_text">'+
                    '<textarea class="written_name form-control" name="notification['+key+'][body]" cols="20" rows="2" require required></textarea><label>الوصف</label>'+
                    '</div>'+
                    '</div>'+
                    '<div class="input_div col-md-2" style="margin-top:0">'+
                    '<div class="input_text">'+
                    '<input class="image_input" id="image"  type="file" style="width: 85%;"  name="notification['+key+'][image]"><label>صوره</label>'+
                    '</div>'+
                    '</div>'+
                    '</div>'+
                    '</div>';
                $('.content-notification').append(html);
            });
        }


//cutom

        function getArabicDay(day){
            var myArray = {'Saturday': 'السبت', 'Sunday': 'الأحد', 'Monday': 'الإثنين', 'Tuesday': 'الثلاثاء', 'Wednesday': 'الأربعاء', 'Thursday': 'الخميس', 'Friday': 'الجمعة'};
            return myArray[day];
        }

        var weekday = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];

        var days_arr = [];

        $('#date_from').change(function(e){
            dateFrom = this.value;
            startDate = moment(dateFrom);
        });
        $('#time_custom').change(function(e){
            time = this.value;
        })


        $('#date_to').change(function(e) {
            dateTo = this.value;

             endDate = moment(dateTo);

            const dates = getDaysBetweenDates(startDate, endDate);

            buildFormCustom(dates);
        });

        $('.day').on('click', function() {
            if(this.checked){
                days_arr.push(this.value);
            }else{
                if ((index = days_arr.indexOf(this.value)) !== -1) {
                    days_arr.splice(index, 1);
                }
            }
            const dates = getDaysBetweenDates(startDate, endDate);

            buildFormCustom(dates);
        });


        function buildFormCustom(dates)
        {
            $('.content-notification').empty();
            dates.forEach(function (date) {
                var dateOfDay = new Date(date);
                var html = '';
                days_arr.forEach(function (value,k){

                    if(value == weekday[dateOfDay.getDay()]){

                        var dateFormate = moment(dateOfDay).format('YYYY-MM-DD');

                        var arabic_day = getArabicDay(value);
                        var key = Math.floor((Math.random() * 1000) + 1);

                        html += '<div class="card" style="padding: 10px">'+
                            '<p><label>التاريخ :</label>'+dateFormate+ '</p>'+
                            '<p><label>اليوم :</label>'+arabic_day + '</p>'+
                            '<div class="row">'+
                            '<div class="input_div col-md-4" style="margin-top:0">'+
                            '<div class="input_text">'+
                            '<input class="written_name form-control" name="notification['+key+'][title]" type="text" require required> <label>العنوان</label>'+
                            '<input class="written_name form-control" name="notification['+key+'][date_at]" value="'+dateFormate+'" type="hidden" > '+
                            '<input class="written_name form-control" name="notification['+key+'][time_at]" value="'+time+'" type="hidden" > '+
                            '</div>'+
                            '</div>'+
                            '<div class="input_div col-md-6" style="margin-top:0">'+
                            '<div class="input_text">'+
                            '<textarea class="written_name form-control" name="notification['+key+'][body]" cols="20" rows="2" require required></textarea><label>الوصف</label>'+
                            '</div>'+
                            '</div>'+
                            '<div class="input_div col-md-2" style="margin-top:0">'+
                            '<div class="input_text">'+
                            '<input class="image_input" id="image" type="file" style="width: 85%;"  name="notification['+key+'][image]"><label>صوره</label>'+
                            '</div>'+
                            '</div>'+
                            '</div>'+
                            '</div>';
                        $('.content-notification').append(html);
                    }
                });
            });
        }



        var duration_type;
        $(document).ready(function (){

            $('#duration_type').on('change',function(e){
                duration_type = this.value;
                if(this.value == 'now'){
                    $('#content_basic').show();
                    $('.content-notification').empty();
                    $('#body').attr("require",true).attr("required",true);
                    $('#title').attr("require",true).attr("required",true);
                }

                if(this.value == 'daily'){

                    $('.daily').show();

                    $('#time_daily').attr('require',true);
                    $('#from').attr('require',true);
                    $('#to').attr('require',true);

                    $('#content_basic').hide();

                    $('#title').removeAttr("require").removeAttr("required");
                    $('#body').removeAttr("require").removeAttr("required");
                    $('#time_custom').removeAttr("name");
                }else {
                    $('.daily').hide();

                    $("#time_daily").removeAttr("require");
                    $("#from").removeAttr("require");
                    $("#to").removeAttr("require");
                }


                if(this.value == 'scheduling'){
                    $('.scheduling').show();
                    $('.content-notification').empty();
                    $('#content_basic').show();
                    $('#body').attr("require",true).attr("required",true);
                    $('#title').attr("require",true).attr("required",true);

                    $('#date_schedule').attr('require',true);
                    $('#time_schedule').attr('require',true);
                }else {
                    $('.scheduling').hide();

                    $("#date_schedule").removeAttr("require");
                    $("#time_schedule").removeAttr("require");
                }

                if(this.value == 'custom'){
                    $('.custom').show();
                    $('#content_basic').hide();
                    $('#time_daily').removeAttr("name");

                    $('#time_custom').attr("require",true).attr("required",true);
                    $('#date_from').attr("require",true).attr("required",true);
                    $('#date_to').attr("require",true).attr("required",true);




                    $('#title').removeAttr("require").removeAttr("required");
                    $('#body').removeAttr("require").removeAttr("required");
                }else {
                    $('.custom').hide();

                    $('#time_custom').removeAttr("require").removeAttr("required");
                    $('#date_from').removeAttr("require").removeAttr("required");
                    $('#date_to').removeAttr("require").removeAttr("required");
                }

            });
        })



        function validate_form(){
            var validate=true;
            var all_inputs=document.querySelectorAll(".main.active input");
            var all_textarea=document.querySelectorAll(".main.active textarea");

            all_inputs.forEach(function(inpt){
                inpt.classList.remove('warning');
                if(inpt.hasAttribute("require")){
                    if(inpt.value.length=="0"){
                        validate=false;
                        inpt.classList.add('warning');
                    }
                }
            });
            all_textarea.forEach(function(inpt){
                inpt.classList.remove('warning');
                if(inpt.hasAttribute("require")){
                    if(inpt.value.length=="0"){
                        validate=false;
                        inpt.classList.add('warning');
                    }
                }
            });
            if(duration_type == 'custom') {
                if($('.day:checked').length == 0)
                {
                    $('#error-msg').show();
                    validate=false;
                } else{
                    $('#error-msg').hide();
                }
            }

            return validate;
        }
    </script>

    <script>
        var searchRequest = null;

        $(function () {
            var minlength = 3;

            $("#search").keyup(function () {
                var that = this, value = $(this).val();
                if (value.length >= minlength ) {
                    if (searchRequest != null)
                        searchRequest.abort();
                    searchRequest = $.ajax({
                        type: "GET",
                        url: "{{route('search-company')}}",
                        data: {
                            'keyword' : value
                        },
                        // dataType: "text",
                        success: function(msg){
                            var html = ''
                            if (msg.length > 0) {
                                msg.forEach(function (value,k) {
                                    $('#card-search').empty();
                                    html += '<div class="input_text">' +
                                                '<input type="radio" name="company_id" value="'+value.id+'"> <span>' + value.name + '</span>' +
                                            '</div>';
                                    $('#card-search').show().append(html);
                                });
                            } else {
                                $('#card-search').show().append('<p> لا توجد بيانات</p>');
                            }


                            //we need to check if the value is the same
                            if (value==$(that).val()) {
                                //Receiving the result of search here
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection


