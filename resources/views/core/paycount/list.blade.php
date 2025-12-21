<!DOCTYPE html>
<html>
<head>
</head>
<body>

<table>
    <tr>
        <td>first_name</td>
        <td>last_name</td>
        <td>email</td>
        <td>phone</td>
        <td>numbers</td>
    </tr>

    @foreach ($bot_users as $bot_user)
        <tr>
            <td>{{$bot_user->first_name}}</td>
            <td>{{$bot_user->last_name}}</td>
            <td>{{$bot_user->email}}</td>
            <td>{{$bot_user->phone}}</td>

            <td>
                @if (isset($tickets[$bot_user->id]))
                    {{implode(', ', $tickets[$bot_user->id])}}
                @endif
            </td>

        </tr>
    @endforeach

</table>

</body>
</html>
