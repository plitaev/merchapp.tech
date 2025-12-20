<!DOCTYPE html>
<html>
<head>
</head>
<body>
<form action="/paycount/load_post" method="POST" enctype="multipart/form-data">
    @csrf
    <textarea style="margin: 0 auto; width: 980px; height: 600px"></textarea>
    <div style="margin-top: 25px"><input type="submit" value="Отправить"/></div>
</form>
</body>
</html>
