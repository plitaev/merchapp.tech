<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            border-collapse: collapse; /* Collapses double borders into single borders */
        }

        table, th, td {
            padding: 5px;
            border: 1px solid black;
        }
    </style>
</head>
<body>

<table>
    <tr style="font-weight: bold">
        <td>first_name</td>
        <td>last_name</td>
        <td>email</td>
        <td>phone</td>
        <td>numbers</td>
        <td>count</td>
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
            <td>
                @if (isset($tickets[$bot_user->id]))
                    {{count($tickets[$bot_user->id])}}
                @else
                    0
                @endif
            </td>

        </tr>
    @endforeach

</table>

</body>
</html>
