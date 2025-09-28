<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Bot extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'alias',
        'telegram_token',
        'telegram_webhook',
        'business_connection_id',
        'message_worktime_after_minutes',
        'business_bot_delay_after_bot_sent_message_in_minutes',
        'business_bot_delay_after_operator_sent_message_in_minutes',
        'yookassa_shop_id',
        'yookassa_shop_secret',
        'yookassa_currency',
        'yookassa_tax_system_code_id',
        'yookassa_vat_code_id',
        'yookassa_payment_mode_id',
        'yookassa_payment_subject_id',
    ];

    public function bot_n(): BelongsTo
    {
        return $this->belongsTo(TelegramSupergroup::class, 'telegram_supergroup_id', 'id');
    }

    public function yookassa_tax_system_code(): BelongsTo
    {
        return $this->belongsTo(YookassaTaxSystemCode::class, 'yookassa_tax_system_code_id', 'id');
    }

    public function yookassa_vat_code(): BelongsTo
    {
        return $this->belongsTo(YookassaVatCode::class, 'yookassa_vat_code_id', 'id');
    }

    public function yookassa_payment_subject(): BelongsTo
    {
        return $this->belongsTo(YookassaPaymentSubject::class, 'yookassa_payment_subject_id', 'id');
    }

    public function yookassa_payment_mode(): BelongsTo
    {
        return $this->belongsTo(YookassaPaymentMode::class, 'yookassa_payment_mode_id', 'id');
    }


}
