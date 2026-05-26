{{--
<table>
    @foreach ($bot_users as $bot_user)
        <tr>
            <td>{{$bot_user->first_name}}</td>
            <td>{{$bot_user->last_name}}</td>
            <td>{{$bot_user->email}}</td>
            <td>{{$bot_user->date_end}}</td>
            <td>{{date('d.m.Y', strtotime($bot_user->date_end))}}</td>
            <td>{{$bot_user->ref_from_telegram_to_max}}</td>
        </tr>
    @endforeach
</table>
--}}
{{--
<table>
    @foreach ($bot_users as $bot_user)
        <tr>
            <td>{{$bot_user->first_name}}</td>
            <td>{{$bot_user->last_name}}</td>
            <td>{{$bot_user->email}}</td>
            <td>{{$bot_user->price}}</td>
        </tr>
    @endforeach
</table>
--}}
<table>
    @foreach ($pays as $pay)
        <tr>
            <td>{{$pay->bot_user->first_name}}</td>
            <td>{{$pay->bot_user->last_name}}</td>
            <td>{{$pay->bot_user->email}}</td>
            <td>{{$pay->price}}</td>
            <td>{{date('d.m.Y', strtotime($pay->created_at))}}</td>
        </tr>
    @endforeach
</table>
