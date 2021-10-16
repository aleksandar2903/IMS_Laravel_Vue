@extends('layouts.app')

@section('content')
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-8">
            <div class="card bg-default">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-light text-uppercase ls-1 mb-1">{{ __('Overview')}}</h6>
                            <h5 class="h3 text-white mb-0">{{__('This Week Traffic')}}</h5>
                        </div>
                        <div class="col">
                            <ul class="nav nav-pills justify-content-end">
                                <li id="chart-data" class="nav-item mr-2 mr-md-0" data-prefix="$">
                                    <a href="javascript:void(0)" id="sales" class="nav-link py-2 px-3 active"
                                        data-toggle="tab">
                                        <span class="d-none d-md-block">{{ __('Sales')}}</span>
                                        <span class="d-md-none">S</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0)" id="purchases" class="nav-link py-2 px-3"
                                        data-toggle="tab">
                                        <span class="d-none d-md-block">{{__('Purchases')}}</span>
                                        <span class="d-md-none">P</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <!-- Chart wrapper -->
                        <canvas id="chart-sales-dark" class="chart-canvas chartjs-render-monitor"
                            style="display: block; width: 100%; height: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1">{{ __('Overview')}}</h6>
                            <h5 class="h3 mb-0">{{__('Top Selling Products')}}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="chart-bars" class="chart-canvas chartjs-render-monitor"
                            style="display: block; width: 100%; height: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-xl-4">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                  <!-- Surtitle -->
                  <h6 class="surtitle">{{ __('Overview')}}</h6>
                  <!-- Title -->
                  <h5 class="h3 mb-0">Top {{ __('Categories')}} (€)</h5>
                </div>
                <!-- Card body -->
                <div class="card-body">
                  <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                    <!-- Chart wrapper -->
                    <canvas id="chart-pie" class="chart-canvas chartjs-render-monitor" style="display: block; height: 100%; width: 100%;"></canvas>
                  </div>
                </div>
              </div>
        </div>
        <div class="col-lg-6 col-xl-8">
            <div class="card">
                <div class="card-header pb-1">
                    <h4>{{__('Recent Sales')}}</h4>
                </div>
                <div class="card-body p-0 m-0">
            <div class="table-responsive table-responsive-xl display compact table-responsive-lg table-responsive-md table-responsive-sm ">
                <table class="table">
                    <thead>
                        <th>{{__('Client')}}</th>
                        <th>{{ __('Stock')}}</th>
                        <th>{{ __('Total Amount')}}</th>
                        <th>{{ __('Paid')}}</th>
                        <th>{{ __('Due')}}</th>
                        <th>{{__('Status')}}</th>
                    </thead>
                    <tbody>
                        @foreach ($sales as $sale)
                            <tr>
                                <td>{{$sale->client->name}}</td>
                                <td>{{$sale->products->sum('qty')}}</td>
                                <td>{{format_money($sale->products->sum('total_amount'))}}</td>
                                <td>{{format_money($sale->paid)}}</td>
                                <td>{{format_money($sale->due)}}</td>
                                <td>{{$sale->status}}</td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function(){
        $( window ).on( "load", onLoad );
            var $chart = $('#chart-sales-dark');
            var $chartBar = $('#chart-bars');
            var $chartPie = $('#chart-pie');
            var chart_data = null;
            var chart_date = null;
            var $data = null;
    function onLoad(){
        changeToSales();
        var topChart = new Chart($chartBar, {
			type: 'bar',
			data: {
				labels: {!!json_encode($products->pluck('name'))!!},
				datasets: [{
					label: {!!json_encode(__('Total Sold'))!!},
					data: {!!json_encode($products->pluck('total_sold'))!!}
				}]
			}
		});
        var topCategoriesChart = new Chart($chartPie, {
			type: 'pie',
			data: {
				labels: {!!json_encode($categories->pluck('name'))!!},
				datasets: [{
					data: {!!json_encode($categories->pluck('total'))!!},
					label: 'Dataset 1',
                    backgroundColor: [
                        Charts.colors.theme['danger'],
						Charts.colors.theme['warning'],
						Charts.colors.theme['success'],
						Charts.colors.theme['primary'],
						Charts.colors.theme['info'],
					],
				}],
			},
			options: {
				responsive: true,
				legend: {
					position: 'top',
				},
				animation: {
					animateScale: true,
					animateRotate: true
				}
			}
		});
    }
    var salesChart = new Chart($chart, $data);
    function changeToSales() {
    salesChart.destroy();
    chart_date = {!!json_encode($thisWeekSales->pluck('date'))!!}.reverse();
    chart_data = {!!json_encode($thisWeekSales->pluck('totalAmount'))!!}.reverse();
    updateData(chart_date, chart_data);
    salesChart = new Chart($chart,$data);
    salesChart.update();
}
document.getElementById('sales').addEventListener('click', changeToSales);
document.getElementById('purchases').onclick = function changeToPurchases() {
    salesChart.destroy();
    chart_data = {!!json_encode($thisWeekPurchases->pluck('totalAmount'))!!}.reverse();
    chart_date = {!!json_encode($thisWeekPurchases->pluck('date'))!!}.reverse();
    updateData(chart_date, chart_data);
    salesChart = new Chart($chart, $data);
    salesChart.update();
}
function updateData(chart_date, chart_data){
            return $data = {
            type: 'line',
            options: {
                scales: {
                    yAxes: [{
                        gridLines: {
                            color: Charts.colors.gray[700],
                            zeroLineColor: Charts.colors.gray[700]
                        },
                    }]
                }
            },
            data: {
                labels: chart_date,
                datasets: [{
                    label: {!!json_encode(__('Total Amount'))!!},
                    data: chart_data
                }]
            }
            };
        }
});
</script>
@endpush
