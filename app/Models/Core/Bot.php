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
        'yookassa_recurrent',
        'prodamus_payment_method_id',
        'prodamus_payment_object_id',
        'prodamus_npd_income_type_id',
        'prodamus_tax_id',
        'robokassa_merchant_login',
        'robokassa_merchant_password',
        'robokassa_nomenclature_code',
        'robokassa_tax_id',
        'robokassa_payment_method_id',
        'robokassa_payment_object_id',
        'robokassa_vat_id',
        'ban_time',
        'prodamus_sys',
        'prodamus_url',
        'prodamus_key',
        'prodamus_key_recurrent',
        'tbank_terminal_key',
        'tbank_terminal_password',
        'tbank_tax_id',
        'tbank_taxation_id',
        'ban_time',
        'recurrent_time'
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

    public function prodamus_payment_method(): BelongsTo
    {
        return $this->belongsTo(ProdamusPaymentMethod::class, 'prodamus_payment_method_id', 'id');
    }

    public function prodamus_payment_object(): BelongsTo
    {
        return $this->belongsTo(ProdamusPaymentObject::class, 'prodamus_payment_object_id', 'id');
    }

    public function prodamus_npd_income_type(): BelongsTo
    {
        return $this->belongsTo(ProdamusNpdIncomeType::class, 'prodamus_npd_income_type_id', 'id');
    }

    public function prodamus_tax(): BelongsTo
    {
        return $this->belongsTo(ProdamusTax::class, 'prodamus_tax_id', 'id');
    }

    public function robokassa_tax(): BelongsTo
    {
        return $this->belongsTo(RobokassaTax::class, 'robokassa_tax_id', 'id');
    }

    public function robokassa_payment_method(): BelongsTo
    {
        return $this->belongsTo(RobokassaPaymentMethod::class, 'robokassa_payment_method_id', 'id');
    }

    public function robokassa_payment_object(): BelongsTo
    {
        return $this->belongsTo(RobokassaPaymentObject::class, 'robokassa_payment_object_id', 'id');
    }

    public function robokassa_vat(): BelongsTo
    {
        return $this->belongsTo(RobokassaVAT::class, 'robokassa_vat_id', 'id');
    }

    public function tbank_taxation(): BelongsTo
    {
        return $this->belongsTo(TbankTaxation::class, 'tbank_taxation_id', 'id');
    }

    public function tbank_tax(): BelongsTo
    {
        return $this->belongsTo(TbankTax::class, 'tbank_tax_id', 'id');
    }

}
