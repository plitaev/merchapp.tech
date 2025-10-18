<x-filament::page>
    @include('filament.resources.bot-resource.pages.navigation-bot', ['category' => "edit", 'bot_id' => $this->record])
    {{$this->form}}
</x-filament::page>

<script type="text/javascript">
    function getWebhookInfo() {
        fetch('/telegram/get_webhook_info/{{$telegram_token}}/{{$telegram_webhook}}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            data: {'token': '{{$telegram_token}}', 'webhook': '{{$telegram_webhook}}'}
        }).then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        }).then(data => document.getElementById('result').innerHTML = JSON.stringify(data)).catch(error => console.error('Error:', error));
    }
</script>
