<!DOCTYPE html>
<html>
<head>
</head>
<body>
<form action="/paycount/change_number_post" method="POST" enctype="multipart/form-data">
    @csrf

    <table>
        <tr style="font-weight: bold">
            <td>Number</td>
            <td>Email</td>
        </tr>
        <tr>
            <td>
                <input type="text" style="margin: 0 auto; width: 250px" id="number" name="number">
            </td>
            <td>
                <input type="text" style="margin: 0 auto; width: 500px" id="email" name="email">
            </td>
        </tr>
    </table>

    <div style="margin-top: 25px"><input type="submit" value="Отправить"/></div>
</form>
</body>
</html>
