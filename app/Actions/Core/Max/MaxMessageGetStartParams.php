<?php
namespace App\Actions\Core\Max;

class MaxMessageGetStartParams {
    public function handle(array $webhook) {

        if (isset($webhook['message']['body']['text'])) {
            //== Если есть текст, разбиваем через пробел, чтобы проверить входной параметр
            (array) $Astart = explode(' ', $webhook['message']['body']['text']);
        } else {
            //== Если нету, то записываем входной параметр как none
            (array) $Astart = ['none', 'none'];
        }

        if (isset($webhook['update_type']) && $webhook['update_type'] == 'bot_started' && $Astart[0] == 'none') {
            (array) $Astart = ['/start', 'none'];
        }

        return $Astart;
    }
}
