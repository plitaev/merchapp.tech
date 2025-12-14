<?php

namespace App\Actions\Core\BotUser;

use Filament\Notifications\Notification;

use App\Models\Core\BotUserRecurrentSchedule;
use App\Models\Core\Pay;

class BotUserSetRecurrentFromAdmin
{
    public function handle(int $bot_user_id) {
        $last_pay = Pay::select('status', 'recurrent')->where('bot_user_id', $bot_user_id)->orderByDesc('created_at')->first();

        if ($last_pay) {

            if ($last_pay->status == 0) {

                if ($last_pay->recurrent == 1) {

                    $last_pay = Pay::select('id')
                        ->where('bot_user_id', $bot_user_id)
                        ->where('status', 1)
                        ->whereNotNull('pay_system_payment_method_id')
                        ->orderByDesc('created_at')
                        ->first();

                    if ($last_pay) {
                        BotUserRecurrentSchedule::create(
                            [
                                'bot_user_id' => $bot_user_id,
                                'prevous_pay_id' => $last_pay->id,
                                'recurrent_datetime' => date('Y-m-d H:i:s', time()),
                                'run_status' => 0
                            ]
                        );
                    } else {
                        Notification::make()
                            ->title('Не найден предыдущий успешный платеж с токеном автосписания')
                            ->danger()
                            ->send();
                    }

                } else {
                    Notification::make()
                        ->title('Последняя неудачная попытка оплаты не является автоплатежом')
                        ->danger()
                        ->send();
                }

            } else {

                Notification::make()
                    ->title('У пользователя уже есть выполненный платеж')
                    ->danger()
                    ->send();

            }

        } else {

            Notification::make()
                ->title('Не найден предыдущий платеж пользователя')
                ->danger()
                ->send();

        }
    }
}
