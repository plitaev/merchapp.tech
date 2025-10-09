<?php

namespace App\Actions\Core\Yookassa;

class YookassaMakeProductJSON
{
    public function handle($bot, $product, int $price, bool $save_payment_method) {
        return [
            [
                'description' => $product->name,
                'quantity' => '1.00',
                'amount' => [
                    'value' => $price,
                    'currency' => $bot->yookassa_currency
                ],
                'tax_system_code' => $bot->yookassa_tax_system_code->code,
                'vat_code' => $bot->yookassa_vat_code->code,
                'payment_mode' => $bot->yookassa_payment_mode->code,
                'payment_subject' => $bot->yookassa_payment_subject->code,
                'save_payment_method' => $save_payment_method
            ]
        ];
    }
}
