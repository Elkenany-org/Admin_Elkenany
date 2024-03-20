@foreach($ships as $value)
    <!-- Start One Row  -->
    <section class="table__row">
        <section class="cell__container">
            <span class="cell__content">{{$value->name}} </span>
        </section>
        <div class="wall"></div>
        <section class="cell__container">
            <span class="cell__content">   {{$value->dir_date}}</span>
        </section>
        <div class="wall"></div>
        <section class="cell__container ">
            <span class="cell__content">{{$value->load}}</span>
        </section>
        <div class="wall"></div>
        <section class="cell__container ">
            <span class="cell__content">{{$value->ShipsProduct ? $value->ShipsProduct->name : '-'}}</span>
        </section>
        <div class="wall"></div>
        <section class="cell__container ">
            <span class="cell__content">{{$value->country}}</span>
        </section>
        <div class="wall"></div>
        <section class="cell__container">
            <span class="cell__content">{{$value->date}}</span>
        </section>
        <div class="wall"></div>
        <section class="cell__container">
            <span class="cell__content">   {{$value->Company ? $value->Company->name : '-'}}</span>
        </section>
        <div class="wall"></div>
        <section class="cell__container">
            <span class="cell__content">{{$value->agent}}</span>
        </section>


        <div class="wall"></div>
        <section class="cell__container">
            <span class="cell__content">{{$value->Ports ? $value->Ports->name : '-'}}</span>
        </section>

    </section>
    <!-- End One Row  -->
@endforeach