<!DOCTYPE html>
<html>
<head>
    <script src="https://securepay.tinkoff.ru/html/payForm/js/tinkoff_v2.js"></script>
</head>
<body>
<form name="TinkoffPayForm" id="TinkoffPayForm" onsubmit="pay(this); return false;">
    <input class="tinkoffPayRow" type="hidden" id="terminalkey" name="terminalkey" value="">
    <input class="tinkoffPayRow" type="hidden" name="frame" value="false">
    <input class="tinkoffPayRow" type="hidden" name="language" value="ru">
    <input class="tinkoffPayRow" type="text" value="1000" name="amount" required id="amount">
    <input class="tinkoffPayRow" type="text" value="1" name="order" id="order">
    <input class="tinkoffPayRow" type="text" value="Тестовый продукт" name="description" id="description">
    <input class="tinkoffPayRow" type="text" value="Плита Евгений" name="name" id="name">
    <input class="tinkoffPayRow" type="text" value="evgeniiplita@gmail.com" name="email" id="email">
    <input class="tinkoffPayRow" type="hidden" id="receipt" name="receipt" value='{"Email":"evgeniiplita@gmail.com","EmailCompany":"evgeniiplita@gmail.com","Taxation":"usn_income","Items":[{"Name":"Тестовый продукт","Price":1000,"Quantity": 1.00,
"Amount":100000,"PaymentMethod":"full_payment","PaymentObject":"service","Tax":"none"}]}'>
    <input class="tinkoffPayRow" type="submit" value="Оплатить">
</form>
</body>
</html>
