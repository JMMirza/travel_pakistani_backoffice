@extends('layouts.master')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0 align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Quotations</h4>
                    <div>
                        <button type="button" class="btn btn-soft-secondary btn-sm">
                            ALL
                        </button>
                        <button type="button" class="btn btn-soft-secondary btn-sm">
                            1M
                        </button>
                        <button type="button" class="btn btn-soft-secondary btn-sm">
                            6M
                        </button>
                        <button type="button" class="btn btn-soft-primary btn-sm">
                            1Y
                        </button>
                    </div>
                </div><!-- end card header -->

                <div class="card-header p-0 border-0 bg-soft-light">
                    <div class="row g-0 text-center">
                        <div class="col-6 col-sm-3">
                            <div class="p-3 border border-dashed border-start-0">
                                <h5 class="mb-1"><span class="counter-value" data-target="7585">0</span></h5>
                                <p class="text-muted mb-0">Pending</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="p-3 border border-dashed border-start-0">
                                <h5 class="mb-1"><span class="counter-value" data-target="7585">0</span></h5>
                                <p class="text-muted mb-0">In Process</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="p-3 border border-dashed border-start-0">
                                <h5 class="mb-1"><span class="counter-value" data-target="7585">0</span></h5>
                                <p class="text-muted mb-0">Quoted</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="p-3 border border-dashed border-start-0">
                                <h5 class="mb-1"><span class="counter-value" data-target="7585">0</span></h5>
                                <p class="text-muted mb-0">Approved</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0 pb-2">
                    <div class="w-100">
                        <div id="customer_impression_charts" data-colors='["--vz-primary", "--vz-success", "--vz-danger"]' class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection



@push('footer_scripts')
<script src="{{ asset('theme/dist/default/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
    $(document).ready(function() {

        var linechartcustomerColors = getChartColorsArray("customer_impression_charts"),
            options = {
                series: [{
                        name: "Orders",
                        type: "area",
                        data: [34, 65, 46, 68, 49, 61, 42, 44, 78, 52, 63, 67],
                    },
                    {
                        name: "Earnings",
                        type: "bar",
                        data: [
                            89.25, 98.58, 68.74, 108.87, 77.54, 84.03, 51.24, 28.57,
                            92.57, 42.36, 88.51, 36.57,
                        ],
                    },
                    {
                        name: "Refunds",
                        type: "line",
                        data: [8, 12, 7, 17, 21, 11, 5, 9, 7, 29, 12, 35],
                    },
                ],
                chart: {
                    height: 370,
                    type: "line",
                    toolbar: {
                        show: !1
                    }
                },
                stroke: {
                    curve: "straight",
                    dashArray: [0, 0, 8],
                    width: [2, 0, 2.2]
                },
                fill: {
                    opacity: [0.1, 0.9, 1]
                },
                markers: {
                    size: [0, 0, 0],
                    strokeWidth: 2,
                    hover: {
                        size: 4
                    }
                },
                xaxis: {
                    categories: [
                        "Jan",
                        "Feb",
                        "Mar",
                        "Apr",
                        "May",
                        "Jun",
                        "Jul",
                        "Aug",
                        "Sep",
                        "Oct",
                        "Nov",
                        "Dec",
                    ],
                    axisTicks: {
                        show: !1
                    },
                    axisBorder: {
                        show: !1
                    },
                },
                grid: {
                    show: !0,
                    xaxis: {
                        lines: {
                            show: !0
                        }
                    },
                    yaxis: {
                        lines: {
                            show: !1
                        }
                    },
                    padding: {
                        top: 0,
                        right: -2,
                        bottom: 15,
                        left: 10
                    },
                },
                legend: {
                    show: !0,
                    horizontalAlign: "center",
                    offsetX: 0,
                    offsetY: -5,
                    markers: {
                        width: 9,
                        height: 9,
                        radius: 6
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 0
                    },
                },
                plotOptions: {
                    bar: {
                        columnWidth: "30%",
                        barHeight: "70%"
                    }
                },
                colors: linechartcustomerColors,
                tooltip: {
                    shared: !0,
                    y: [{
                            formatter: function(e) {
                                return void 0 !== e ? e.toFixed(0) : e;
                            },
                        },
                        {
                            formatter: function(e) {
                                return void 0 !== e ? "$" + e.toFixed(2) + "k" : e;
                            },
                        },
                        {
                            formatter: function(e) {
                                return void 0 !== e ? e.toFixed(0) + " Sales" : e;
                            },
                        },
                    ],
                },
            },
            chart = new ApexCharts(
                document.querySelector("#customer_impression_charts"),
                options
            );
        chart.render();

    });

    function getChartColorsArray(e) {
        if (null !== document.getElementById(e)) {
            var e = document.getElementById(e).getAttribute("data-colors");
            return (e = JSON.parse(e)).map(function(e) {
                var t = e.replace(" ", "");
                if (-1 === t.indexOf(",")) {
                    var o = getComputedStyle(
                        document.documentElement
                    ).getPropertyValue(t);
                    return o || t;
                }
                e = e.split(",");
                return 2 != e.length ?
                    t :
                    "rgba(" +
                    getComputedStyle(
                        document.documentElement
                    ).getPropertyValue(e[0]) +
                    "," +
                    e[1] +
                    ")";
            });
        }
    }
</script>
@endpush