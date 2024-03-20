@foreach($moves as $key => $value)
    @if($value->price > '0')
        <!-- Start One Row  -->
        <section class="table__row row__image table_all">
            <a href="{{ route('front_company',$value->Company->id) }}" class="cell__container">
                <div class="cell__content__with__image">
                    <img class="image" src="{{$value->Company->image_url}}" alt="logo" style="width: 150px;height: 120px">
                    <span class="name"> {{$value->Company->name}}</span>
                </div>
            </a>

            <div class="wall" style="background-color: #1a5302 !important;"></div>
            <section class="cell__container">
                <span class="cell__content">{{round($value->price, 2)}} جنية</span>
            </section>

            <div class="wall"></div>
            <section class="cell__container">
                <span class="cell__content"> {{$value->StockFeed->name }}</span>
            </section>

            <div class="wall"></div>
            <section class="cell__container">
                @if($value->change < '0' )
                    <span class="cell__content down">{{round($value->change, 2)}}</span>
                    <span class="cell__content time">{{Date::parse($value->created_at)->format('H:i / Y-m-d')}}</span>
                @endif
                @if($value->change > '0' )
                    <span class="cell__content up">+{{round($value->change, 2)}}</span>
                    <span class="cell__content time">{{Date::parse($value->created_at)->format('H:i / Y-m-d')}}</span>
                @endif
                @if($value->change == '0' )
                    <span class="cell__content">{{round($value->change, 2)}}</span>
                    <span class="cell__content time">{{Date::parse($value->created_at)->format('H:i / Y-m-d')}}</span>
                @endif
            </section>

            <div class="wall  d-lg-block d-none"></div>
            <section class="data charts d-lg-block d-none">
                <div class="chart{{$value->id}}"></div>
            </section>
            <script>
                var changes = [];
                var dates = [];

                <?php foreach($value->FodderStock->movements()['changes'] as  $ch){ ?>
                changes.push('<?php echo $ch; ?>');
                <?php } ?>

                <?php foreach($value->FodderStock->movements()['dates'] as $da){ ?>
                dates.push('<?php echo $da; ?>');
                <?php } ?>

                new ApexCharts(document.querySelector(".chart{{$value->id}}"), {
                    series: [{
                        name: "",
                        data: changes,
                    }],
                    chart: {
                        height: 120,
                        width: '100%',
                        type: 'line',
                        zoom: {
                            enabled: false
                        },
                        toolbar: {
                            show: false
                        }
                    },
                    yaxis: {
                        show: false
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'straight',
                        colors: ['#008000'],
                        width: 2,
                    },
                    title: {
                        text: '',
                        align: 'left'
                    },
                    grid: {
                        show: false,
                        row: {
                            opacity: 0.5
                        },
                    },
                    xaxis: {
                        categories: dates
                    },
                    markers: {
                        colors: ['#000']
                    },
                    tooltip: {
                        fillSeriesColor: true,
                        theme: true,
                        style: {
                            fontSize: '15px'
                        },
                        onDatasetHover: {
                            highlightDataSeries: false,
                        },
                    }
                }).render();

            </script>

        </section>
        <!-- End One Row  -->
    @endif
@endforeach