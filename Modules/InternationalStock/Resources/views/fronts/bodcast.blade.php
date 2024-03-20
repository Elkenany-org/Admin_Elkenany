
@extends('layouts.front')
@section('style')
<link rel="stylesheet" href="{{asset('Front_End/css/companies_details.css')}}">
<link rel="stylesheet" href="{{asset('Front_End/css/nice-select.css')}}">
<title>  {{$new->title}}</title>
@endsection
@section('content')



<section class="main__container container">
    <div class="row">
     
        {{csrf_field()}}

        <div class="col-12">

        <div class="news__container">
                    <div class="header">
                        <div class="card__img">
                        <img alt="" src="{{asset('uploads/news/avatar/'.$new->image)}}"/>
                        </div>
                        <div class="card__main__header">
                            <h1 class="content__title">{{$new->title}}</h1>
                            <div class="content__title__footer">
                           
                            </div>
                        </div>
                    </div>
                    <div class="content">
                        <div class="podcast-player" dir="ltr">
                            <div class="podcast-player-controls">
                                <button class="podcast-play"><i class="fa fa-play"></i><span>Play</span></button>
                                <button class="podcast-pause"><i class="fa fa-pause"></i><span>Pause</span></button>
                                <button class="podcast-rewind"><i class="fa fa-fast-backward"></i><span>Rewind</span>
                                </button>
                                <span class="podcast-current-time podcast-time">00:00</span>
                                <progress class="podcast-progress" value="0"></progress>
                                <span class="podcast-duration podcast-time">00:00</span>
                                <button class="podcast-speed">1x</button>
                                <button class="podcast-mute"><i class="fa fa-volume-up"></i><span>Mute/Unmute</span>
                                </button>
                            </div>
                            <!--         Here you should put audio file src               -->
                            <audio src="{{asset('uploads/news/file/'.$new->file)}}">
                            <source src="{{asset('uploads/news/file/'.$new->file)}}" type="audio/mpeg">
                            </audio>
                            <a class="podcast-download" download
                               href="{{asset('uploads/news/file/'.$new->file)}}">Download
                                MP3</a>
                        </div>
                        <div class="content__description">
                            <p>
                                 
                            {!!$new->desc!!}
                            </p>
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
                                            <a href="{{ route('front_one_bodcast',$value->id) }}">
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


<script>
    (function () {

        const podcastPlayers = document.querySelectorAll('.podcast-player');
        const speeds = [1, 1.5, 2, 2.5, 3]

        for (let i = 0; i < podcastPlayers.length; i++) {
            let player = podcastPlayers[i];
            let audio = player.querySelector('audio');
            let play = player.querySelector('.podcast-play');
            let pause = player.querySelector('.podcast-pause');
            let rewind = player.querySelector('.podcast-rewind');
            let progress = player.querySelector('.podcast-progress');
            let speed = player.querySelector('.podcast-speed');
            let mute = player.querySelector('.podcast-mute');
            let currentTime = player.querySelector('.podcast-current-time');
            let duration = player.querySelector('.podcast-duration');

            let currentSpeedIdx = 0;

            pause.style.display = 'none';

            let toHHMMSS = function (totalSecs) {
                let sec_num = parseInt(totalSecs, 10); // don't forget the second param
                let hours = Math.floor(sec_num / 3600);
                let minutes = Math.floor((sec_num - (hours * 3600)) / 60);
                let seconds = sec_num - (hours * 3600) - (minutes * 60);

                if (hours < 10) {
                    hours = "0" + hours;
                }
                if (minutes < 10) {
                    minutes = "0" + minutes;
                }
                if (seconds < 10) {
                    seconds = "0" + seconds;
                }

                let time = hours + ':' + minutes + ':' + seconds;
                return time;
            }

            audio.addEventListener('loadedmetadata', function () {
                progress.setAttribute('max', Math.floor(audio.duration));
                duration.textContent = toHHMMSS(audio.duration);
            });

            audio.addEventListener('timeupdate', function () {
                progress.setAttribute('value', audio.currentTime);
                currentTime.textContent = toHHMMSS(audio.currentTime);
            });

            play.addEventListener('click', function () {
                this.style.display = 'none';
                pause.style.display = 'inline-block';
                pause.focus();
                audio.play();
            }, false);

            pause.addEventListener('click', function () {
                this.style.display = 'none';
                play.style.display = 'inline-block';
                play.focus();
                audio.pause();
            }, false);

            rewind.addEventListener('click', function () {
                audio.currentTime -= 30;
            }, false);

            progress.addEventListener('click', function (e) {
                audio.currentTime = Math.floor(audio.duration) * (e.offsetX / e.target.offsetWidth);
            }, false);

            speed.addEventListener('click', function () {
                currentSpeedIdx = currentSpeedIdx + 1 < speeds.length ? currentSpeedIdx + 1 : 0;
                audio.playbackRate = speeds[currentSpeedIdx];
                this.textContent = speeds[currentSpeedIdx] + 'x';
                return true;
            }, false);

            mute.addEventListener('click', function () {
                if (audio.muted) {
                    audio.muted = false;
                    this.querySelector('.fa').classList.remove('fa-volume-off');
                    this.querySelector('.fa').classList.add('fa-volume-up');
                } else {
                    audio.muted = true;
                    this.querySelector('.fa').classList.remove('fa-volume-up');
                    this.querySelector('.fa').classList.add('fa-volume-off');
                }
            }, false);
        }
    })(this);
</script>
@endsection