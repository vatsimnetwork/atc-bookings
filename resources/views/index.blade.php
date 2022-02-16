@extends('app')

@section('content')

    <main class="container">
        <div class="row">
            <div class="col">
                <div class="alert alert-info" role="alert">
                    While controllers are able to book positions on their local facility websites, this serves as no
                    ultimate guarantee that the position will be open at the published time. Remember that this is a
                    voluntary network and members are providing this service in their spare time.
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-2">
                <div class="d-grid">
                    <button class="btn btn-info btn-block" id="toggleOrder" data-type="callsign" data-newtype="start">
                        Order by Callsign
                    </button>
                </div>
            </div>
            <div class="col">
                <span class="badge rounded-pill" style="background-color: #3C34FF !important;">Booking</span>
                <span class="badge rounded-pill" style="background-color: #ED2DC6 !important;">Training</span>
                <span class="badge rounded-pill" style="background-color: #EFB515 !important;">Event</span>
                <span class="badge rounded-pill" style="background-color: #2ECB11 !important;">Exam</span>
            </div>
        </div>
        <div class="row">
            <div class="col" id="chart"></div>
        </div>
    </main>
@endsection

@section('custom_js')
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '/get-bookings/start',
                type: 'get',
                success: function(data) {
                    loadGraphInit(data);
                },
                error: function(e) {
                    console.warn('An error occured getting bookings')
                }
            })
        });
        $('#toggleOrder').click(function() {
            var btn = $(this);
            var newtype = btn.data('newtype');
            var filtertype = btn.data('type');
            $.ajax({
                url: '/get-bookings/' + filtertype,
                type: 'get',
                success: function(data) {
                    btn.data('type', newtype);
                    btn.data('newtype', filtertype);
                    btn.html('Order by ' + newtype.charAt(0).toUpperCase() + newtype.slice(1));
                    updateGraphData(data);
                },
                error: function(e) {
                    console.warn('An error occured getting bookings')
                }
            })
        });

        function updateGraphData(data) {
            ApexCharts.exec('bookings', 'updateSeries', [{
                data: data
            }], true);
        }

        function loadGraphInit(data) {
            var options = {
                series: [
                    {
                        data: data
                    }
                ],
                annotations: {
                    xaxis: [{
                        x: Date.now(),
                        borderColor: '#990016',
                        yAxisIndex: 0,
                        label: {
                            show: true,
                            text: 'Current Time',
                            style: {
                                color: '#FFF',
                                background: '#d00010'
                            }
                        }
                    }]
                },
                chart: {
                    id: 'bookings',
                    height: 1000,
                    type: 'rangeBar',
                    foreColor: '#fff',
                    toolbar: {
                        tools: {
                            download: false
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: true
                    }
                },
                xaxis: {
                    type: 'datetime',
                    position: 'top',
                    datetimeUTC: true,
                    axisBorder: {
                        color: '#fff',
                    },
                    axisTicks: {
                        color: '#fff',
                    },
                },
                tooltip: {
                    theme: 'dark',
                    x: {
                        format: 'HH:mm',
                    }
                },
                grid: {
                    show: true,
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        }
    </script>
@endsection
