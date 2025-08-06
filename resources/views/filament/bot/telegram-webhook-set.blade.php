<div id="result" style="word-wrap: anywhere"></div>

<a
    href="javascript:void(0);"
    x-data="{getWebhookInfo: function () {
              fetch('/telegram/get_webhook_info/{{$record->telegram_token}}/{{$record->telegram_webhook}}', {
               method: 'POST',
               headers: {'Content-Type': 'application/json'},
               data: {'token': '{{$record->telegram_token}}', 'webhook': '{{$record->telegram_webhook}}'}
              }).then(response => {
               if (!response.ok) {
                throw new Error('Network response was not ok');
               }
               return response.json();
              }).then(data => document.getElementById('result').innerHTML = JSON.stringify(data)).catch(error => console.error('Error:', error));
              }
             }"
    @click="getWebhookInfo();"
    x-init="getWebhookInfo()"
    class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg  fi-btn-color-gray fi-color-gray fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 [input:checked+&]:bg-gray-400 [input:checked+&]:text-white [input:checked+&]:ring-0 [input:checked+&]:hover:bg-gray-300 dark:[input:checked+&]:bg-gray-600 dark:[input:checked+&]:hover:bg-gray-500 fi-ac-action fi-ac-btn-action">
    Запросить статус Webhook
</a>

<a
    href="javascript:void(0);"
    x-data="{setWebhook: function () {
              fetch('/telegram/set_webhook/{{$record->id}}/{{$record->telegram_token}}/{{$record->telegram_webhook}}', {
               method: 'POST',
               headers: {'Content-Type': 'application/json'},
               data: {'token': '{{$record->telegram_token}}', 'webhook': '{{$record->telegram_webhook}}'}
              }).then(response => {
               if (!response.ok) {
                throw new Error('Network response was not ok');
               }
               return response.json();
              }).then(data => document.getElementById('result').innerHTML = JSON.stringify(data)).catch(error => console.error('Error:', error));
              }
             }"
    @click="setWebhook();"
    class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg  fi-btn-color-gray fi-color-gray fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 [input:checked+&]:bg-gray-400 [input:checked+&]:text-white [input:checked+&]:ring-0 [input:checked+&]:hover:bg-gray-300 dark:[input:checked+&]:bg-gray-600 dark:[input:checked+&]:hover:bg-gray-500 fi-ac-action fi-ac-btn-action">
    Установить Webhook
</a>

<a
    href="javascript:void(0);"
    x-data="{deleteWebhook: function () {
              fetch('/telegram/delete_webhook/{{$record->telegram_token}}/{{$record->telegram_webhook}}', {
               method: 'POST',
               headers: {'Content-Type': 'application/json'},
               data: {'token': '{{$record->telegram_token}}', 'webhook': '{{$record->telegram_webhook}}'}
              }).then(response => {
               if (!response.ok) {
                throw new Error('Network response was not ok');
               }
               return response.json();
              }).then(data => document.getElementById('result').innerHTML = JSON.stringify(data)).catch(error => console.error('Error:', error));
              }
             }"
    @click="deleteWebhook();"
    class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg  fi-btn-color-gray fi-color-gray fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 [input:checked+&]:bg-gray-400 [input:checked+&]:text-white [input:checked+&]:ring-0 [input:checked+&]:hover:bg-gray-300 dark:[input:checked+&]:bg-gray-600 dark:[input:checked+&]:hover:bg-gray-500 fi-ac-action fi-ac-btn-action">
    Удалить Webhook
</a>

<a
    href="javascript:void(0)"
    @click="close()"
    class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg  fi-btn-color-gray fi-color-gray fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 [input:checked+&]:bg-gray-400 [input:checked+&]:text-white [input:checked+&]:ring-0 [input:checked+&]:hover:bg-gray-300 dark:[input:checked+&]:bg-gray-600 dark:[input:checked+&]:hover:bg-gray-500 fi-ac-action fi-ac-btn-action">
    Закрыть
</a>
