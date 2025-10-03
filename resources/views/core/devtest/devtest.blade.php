<table>
    @foreach ($webhooks as $webhook)
        <tr>
            <td>{{$webhooks->getcourse_id}}</td>
            <td>{{$webhooks->name}}</td>
            <td>{{$webhooks->email}}</td>
            <td>{{date('d.m.Y', strtotime($webhooks->created_at))}}</td>
        </tr>
    @endforeach
</table>
