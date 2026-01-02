<!DOCTYPE html>
<html>
<head>
    <script src="https://securepay.tinkoff.ru/html/payForm/js/tinkoff_v2.js"></script>
</head>
<body>
<form name="TinkoffPayForm" id="TinkoffPayForm" onsubmit="pay(this); return false;">
    <div style="margin: 25px">
        <div>Ключ тестового терминала</div>
        <input class="tinkoffPayRow" type="text" id="terminalkey" name="terminalkey" value="">
    </div>
    <div style="margin: 25px">
        <div>Открывать во всплывающем окне</div>
        <input class="tinkoffPayRow" type="text" name="frame" value="false">
    </div>
    <div style="margin: 25px">
        <div>Язык</div>
        <input class="tinkoffPayRow" type="text" name="language" value="ru">
    </div>
    <div style="margin: 25px">
        <div>Сумма</div>
        <input class="tinkoffPayRow" type="text" value="1000" name="amount" required id="amount">
    </div>
    <div style="margin: 25px">
        <div>ID заказа на стороне merchApp</div>
        <input class="tinkoffPayRow" type="text" value="1" name="order" id="order">
    </div>
    <div style="margin: 25px">
        <div>Описание</div>
        <input class="tinkoffPayRow" type="text" value="Тестовый продукт" name="description" id="description">
    </div>
    <div style="margin: 25px">
        <div>Фамилия и имя клиента</div>
        <input class="tinkoffPayRow" type="text" value="Плита Евгений" name="name" id="name">
    </div>
    <div style="margin: 25px">
        <div>Email клиента</div>
        <input class="tinkoffPayRow" type="text" value="evgeniiplita@gmail.com" name="email" id="email">
    </div>
    <div style="margin: 25px">
        <div>JSON чека (Проверьте совпадение цены, почты и ФИО клиента)</div>
        <textarea class="tinkoffPayRow" type="hidden" id="receipt" name="receipt" style="width: 500px; height: 500px">{"Email":"evgeniiplita@gmail.com","EmailCompany":"evgeniiplita@gmail.com","Taxation":"usn_income","Items":[{"Name":"Тестовый продукт","Price":1000,"Quantity": 1.00,"Amount":100000,"PaymentMethod":"full_payment","PaymentObject":"service","Tax":"none"}]}</textarea>
    </div>
    <input class="tinkoffPayRow" type="submit" value="Оплатить">
</form>
</body>
</html>
