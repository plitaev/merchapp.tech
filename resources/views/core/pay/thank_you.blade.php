<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        .button {
            display: inline-block;
            padding: .75rem 1.25rem;
            border-radius: 10rem;
            color: #fff;
            text-transform: uppercase;
            font-size: 1rem;
            letter-spacing: .15rem;
            transition: all .3s;
            position: relative;
            overflow: hidden;
            z-index: 1;
            &:after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: #0cf;
                border-radius: 10rem;
                z-index: -2;
            }
            &:before {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 0%;
                height: 100%;
                background-color: darken(#0cf, 15%);
                transition: all .3s;
                border-radius: 10rem;
                z-index: -1;
            }
            &:hover {
                color: #fff;
                &:before {
                    width: 100%;
                }
            }
        }

        /* optional reset for presentation */
        * {
            font-family: Arial;
            text-decoration: none;
            font-size: 20px;
        }
        .container {
            padding-top: 50px;
            margin: 0 auto;
            width: 100%;
            text-align: center;
        }
        h1 {
            text-transform: uppercase;
            font-size: .8rem;
            margin-bottom: 2rem;
            color: #777;
        }
        span {
            display: block;
            margin-top: 2rem;
            font-size: .7rem;
            color: #777;
            a {
                font-size: .7rem;
                color: #999;
                text-decoration: underline;
            }
        }
    </style>

</head>
<body>

<div class="container">
    <h1>ОПЛАТА УСПЕШНО ЗАВЕРШЕНА. ВЫ МОЖЕТЕ ЗАКРЫТЬ БРАУЗЕР, ДОСТУП ПРИДЕТ ПРЯМО В БОТ.</h1>
    <a href="https://t.me/{{$bot->alias}}" target="_blank" class="button">Закрыть</a>
</div>

</body>
</html>
