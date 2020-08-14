<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
    <h1>Thông báo đặt hàng thành công</h1>
    <p>Chào {{ $order->name }},
        <br> Cám ơn bạn đã mua sắm tại Melani
        <br>
        <br> Đơn hàng của bạn đang 
        <b>chờ shop</b>  
        <b>xác nhận</b> (trong vòng 24h)
        <br> Chúng tôi sẽ thông tin <b>trạng thái đơn hàng</b> trong email tiếp theo.
        <br> Bạn vui lòng kiểm tra email thường xuyên nhé.</p>
        <h2>Mã đơn hàng của của bản : MLNXAM{{ $order->id }}FIIRAS{{ $order->id }}</h2>
    <table border="1" cellpadding="3px"	cellspacing="0px">
        <thead>
            <th>Stt</th>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Thành tiền</th>
        </thead><?php $i=0;$totalAmount = 0;?>
        <tbody>
            @foreach ($carts as $cart)
            <?php $i++;?>
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $cart['name'] }}
                        @if($cart['color'] != '')
                        <p>Color :{{ $cart['color'] }}</p>
                        @endif
                        @if($cart['size'] != '')
                        <p>Cỡ :{{ $cart['size'] }}</p>
                        @endif
                    </td>
                    <td>{{ $cart['quantity'] }}</td>
                    <td>{{ number_format($cart['price']) }} VNĐ</td>
                    <td>{{ number_format($cart['price']*$cart['quantity']) }} VNĐ</td>
                </tr>
               <?php $totalAmount += $cart['price']*$cart['quantity'];?>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" align="center">Tổng tiền : </td>
                <td>{{ number_format($totalAmount) }} VNĐ</td>
            </tr>
        </tfoot>
    </table>

    <script src="//code.jquery.com/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</body>
</html>