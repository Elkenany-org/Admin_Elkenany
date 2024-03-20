let _seed = 42;
Math.random = function () {
    _seed = _seed * 16807 % 2147483647;
    return (_seed - 1) / 2147483646;
};
const options = {
    series: [{
        name: "",
        data: [2, -1, 5, -3, 4, 0, 2],
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
        categories: ['20/1', '21/1', '22/1', '23/1', '24/1', '25/1', '26/1']
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
};

const options2 = {
    series: [{
        name: "",
        data: [0, 1, -1, 2, -3, 3, 5],
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
        colors: ['#eca008'],
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
        categories: ['20/1', '21/1', '22/1', '23/1', '24/1', '25/1', '26/1']
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
};

const chart1 = new ApexCharts(document.querySelector("#chart1"), options);
chart1.render();
const chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
chart2.render();
const chart3 = new ApexCharts(document.querySelector("#chart3"), options2);
chart3.render();
const chart4 = new ApexCharts(document.querySelector("#chart4"), options2);
chart4.render();
const chart5 = new ApexCharts(document.querySelector("#chart5"), options2);
chart5.render();
const chart6 = new ApexCharts(document.querySelector("#chart6"), options2);
chart6.render();
const chart7 = new ApexCharts(document.querySelector("#chart7"), options2);
chart7.render();
const chart8 = new ApexCharts(document.querySelector("#chart8"), options2);
chart8.render();
const chart9 = new ApexCharts(document.querySelector("#chart9"), options2);
chart9.render();
const chart10 = new ApexCharts(document.querySelector("#chart10"), options2);
chart10.render();
const chart11 = new ApexCharts(document.querySelector("#chart11"), options);
chart11.render();
const chart12 = new ApexCharts(document.querySelector("#chart12"), options2);
chart12.render();
const chart13 = new ApexCharts(document.querySelector("#chart13"), options2);
chart13.render();
const chart14 = new ApexCharts(document.querySelector("#chart14"), options2);
chart14.render();
const chart15 = new ApexCharts(document.querySelector("#chart15"), options2);
chart15.render();
const chart16 = new ApexCharts(document.querySelector("#chart16"), options2);
chart16.render();
const chart17 = new ApexCharts(document.querySelector("#chart17"), options2);
chart17.render();
const chart18 = new ApexCharts(document.querySelector("#chart18"), options2);
chart18.render();
const chart19 = new ApexCharts(document.querySelector("#chart19"), options2);
chart19.render();
const chart20 = new ApexCharts(document.querySelector("#chart20"), options2);
chart20.render();
