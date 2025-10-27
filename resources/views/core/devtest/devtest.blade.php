<table>
    @foreach ($pays as $pay)
        <tr>
            <td>{{$pay->first_name}}</td>
            <td>{{$pay->last_name}}</td>
            <td>{{$pay->email}}</td>
            <td>{{$pay->pay_price}}</td>
            <td>{{date('d.m.Y', strtotime($pay->created_at))}}</td>
        </tr>
    @endforeach
</table>
