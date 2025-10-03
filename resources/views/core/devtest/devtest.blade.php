<table>
    @foreach ($webhooks as $webhook)
        <tr>
            <td>{{$webhook->getcourse_id}}</td>
            <td>{{$webhook->name}}</td>
            <td>{{$webhook->email}}</td>
            <td>{{date('d.m.Y', strtotime($webhook->created_at))}}</td>
        </tr>
    @endforeach
</table>
