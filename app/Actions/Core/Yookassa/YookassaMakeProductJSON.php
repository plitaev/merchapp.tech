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
                    'currency' => 'RUB'
                ],
                'tax_system_code' => 6,
                'vat_code' => '1',
                'payment_mode' => 'full_payment',
                'payment_subject' => 'service',
                'save_payment_method' => $save_payment_method
            ]
        ];
    }
}
