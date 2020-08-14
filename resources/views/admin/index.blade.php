@extends('admin/master')

@section('title','Trang chủ quản trị')

@section('main')
	
<div class="row">
	<div class="col-lg-3 col-xs-6">
	  <!-- small box -->
	  <div class="small-box bg-aqua">
		<div class="inner">
		  <h3>{{$listOrd}}</h3>

		  <p>New Orders</p>
		</div>
		<div class="icon">
		  <i class="ion ion-bag"></i>
		</div>
	<a href="{{ route('order.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
	  </div>
	</div>
	<!-- ./col -->
	<div class="col-lg-3 col-xs-6">
	  <!-- small box -->
	  <div class="small-box bg-green">
		<div class="inner">
		  <h3>{{ number_format($totalAmount->total) }}<sup style="font-size: 20px"> VNĐ</sup></h3>

		  <p>Tổng tiền</p>
		</div>
		<div class="icon">
		  <i class="ion ion-stats-bars"></i>
		</div>
		<a href="{{ route('order.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
	  </div>
	</div>
	<!-- ./col -->
	<div class="col-lg-3 col-xs-6">
	  <!-- small box -->
	  <div class="small-box bg-yellow">
		<div class="inner">
		  <h3>{{ count($listCus) }}</h3>

		  <p>User Registrations</p>
		</div>
		<div class="icon">
		  <i class="ion ion-person-add"></i>
		</div>
		<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
	  </div>
	</div>
	<!-- ./col -->
	<div class="col-lg-3 col-xs-6">
	  <!-- small box -->
	  <div class="small-box bg-red">
		<div class="inner">
		  <h3>{{ count($listPro) }}</h3>

		  <p>Sản phẩm</p>
		</div>
		<div class="icon">
		  <i class="ion ion-pie-graph"></i>
		</div>
		<a href="{{ route('product.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
	  </div>
	</div>
	<!-- ./col -->
  </div>
  <div style="width: 100%">
	<canvas id="canvas"></canvas>
</div>
@stop()
@section('js')
<script>
	var totalOrder = {!! json_encode($orderMonth) !!};
	var totalAmount = {!! json_encode($data) !!};
	var barChartData = {
		labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July','August','September','October','November','December'],
		datasets: [{
			label: 'Số đơn hàng',
			backgroundColor: window.chartColors.orange,
			yAxisID: 'y-axis-1',
			data: totalOrder
		}, {
			label: 'Doanh thu',
			backgroundColor: window.chartColors.blue,
			yAxisID: 'y-axis-2',
			data: totalAmount
		}]

	};
	window.onload = function() {
		var ctx = document.getElementById('canvas').getContext('2d');
		window.myBar = new Chart(ctx, {
			type: 'bar',
			data: barChartData,
			options: {
				responsive: true,
				title: {
					display: true,
					text: 'Thống kê đơn hàng'
				},
				tooltips: {
					mode: 'index',
					intersect: true
				},
				scales: {
					yAxes: [{
						type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
						display: true,
						position: 'left',
						id: 'y-axis-1',
					}, {
						type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
						display: true,
						position: 'right',
						id: 'y-axis-2',
						gridLines: {
							drawOnChartArea: false
						}
					}],
				}
			}
		});
	};
</script>
@endsection
