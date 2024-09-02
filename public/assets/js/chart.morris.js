$(document).ready(function () {
    lineChart();
    donutChart();
    pieChart();

    $(window).resize(function () {
        window.lineChart.redraw();
        window.donutChart.redraw();
        window.pieChart.redraw();
    });
});

function lineChart() {

    window.lineChart = Morris.Line({
        element: 'line-chart',
        data: [{ y: '2006', a: 100, b: 90 },
        { y: '2007', a: 75, b: 65 },
        { y: '2008', a: 50, b: 40 },
        { y: '2009', a: 75, b: 65 },
        { y: '2010', a: 50, b: 40 },
        { y: '2011', a: 75, b: 65 },
        { y: '2012', a: 100, b: 90 }],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Series A', 'Series B'],
        lineColors: ['#009688', '#cdc6c6'],
        lineWidth: '3px',
        resize: true,
        redraw: true
    });
}

function donutChart() {
    window.donutChart = Morris.Donut({
        element: 'donut-chart',
        data: [{ label: "Total Users", value: 50 },
        { label: "Pending Orders", value: 25 },
        { label: "Successful Orders", value: 5 },
        { label: "Total Photos", value: 10 },
        { label: "Total Events", value: 10 },
        ],
        backgroundColor: '#f2f5fa',
        labelColor: '#0097b2',
        colors: ['#0097b2', '#009ce7', '#4bb8ec', '#9bd8f5'],
        resize: true, redraw: true
    });
}
function pieChart() {
    var paper = Raphael("pie-chart"); 

    paper.piechart(100, 100, 90, [18.373, 18.686, 2.867, 23.991, 9.592, 0.213], 
        { 
            legend: ["Windows/Windows Live", "Server/Tools", "Online Services", "Business", "Entertainment/Devices", "Unallocated/Other"] 
        }
    );
}