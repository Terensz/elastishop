<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Utility;

use Illuminate\Validation\Rule;

class Rules
{
    /**
     * @return array<string, string>
     */
    public static function invoiceSettings(): array
    {
        return [
            'api_key' => 'required|string|max:255',
            'external_inv_id' => 'sometimes|string|max:255',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function invoicePdfSettings(): array
    {
        return [
            'api_key' => 'required|string|max:255',
            'invoice_number' => 'required|string|max:127',
            'external_inv_id' => 'sometimes|string|max:255',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function invoiceXmlSettings(): array
    {
        return [
            'api_key' => 'required|string|max:255',
            'invoice_number' => 'required_without:order_number|string|max:127',
            'order_number' => 'required_without:invoice_number|string|max:127',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function headerSettings(): array
    {
        return [
            'creating_date' => 'required|date',
            'payment_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:creating_date',
            'payment_type' => ['required', 'string', Rule::in(SupportedPayments::forInvoice())],
            'currency' => ['sometimes', 'required', 'string'],
            'language' => ['required', 'string', Rule::in(SupportedLanguages::forInvoice())],
            'order_number' => ['sometimes', 'alpha_num'],
            'proforma_number' => ['sometimes', 'alpha_num'],
            'corrected_invoice_number' => ['sometimes', 'alpha_num'],
            'invoice_prefix' => ['sometimes', 'string'],
            'comment' => ['sometimes', 'string'],
            'exchange_rate_bank' => ['sometimes', 'string'],
            'exchange_rate' => ['sometimes', 'string'],
            'is_proforma_invoice' => ['sometimes', Rule::in(['true', 'false'])],
            'is_correction_invoice' => ['sometimes', Rule::in(['true', 'false'])],
            'is_final_invoice' => ['sometimes', Rule::in(['true', 'false'])],
            'is_deposit_invoice' => ['sometimes', Rule::in(['true', 'false'])],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function taxpayerHeaderSettings(): array
    {
        return [
            'taxpayer_id_base' => 'required|numeric|digits:8',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function sellerSettings(): array
    {
        return [
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:32',
            'email_replyto' => 'required|email',
            'email_subject' => 'sometimes|string|max:255',
            'email_body' => 'sometimes|string|max:16384',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function buyerSettings(): array
    {
        return [
            'name' => 'required|string|max:255',
            'country' => 'sometimes|string|max:127',
            'postal_code' => 'required|string|max:16',
            'city' => 'required|string|max:127',
            'address' => 'required|string|max:255',
            'email' => 'required|email',
            'send_email' => ['sometimes', Rule::in(['true', 'false'])],
            'is_taxpayer' => ['sometimes', Rule::in(['0', '1', '-1', '6', '7'])],
            'taxpayer_id' => 'sometimes|required_if:is_taxpayer,1|string|max:13',
            'eutaxid' => 'sometimes|required_if:is_taxpayer,6|string|max:20',
            'postal_name' => 'sometimes|string|max:255',
            'postal_zipcode' => 'sometimes|string|max:16',
            'postal_city' => 'sometimes|string|max:127',
            'postal_address' => 'sometimes|string|max:255',
            'buyer_comment' => 'sometimes|string|max:255',
            'buyer_id' => 'sometimes|string|max:36',
            'phone' => 'sometimes|string|max:24',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function itemsSettings(): array
    {
        return [
            '*.name' => 'required|string|max:127',
            '*.desc' => 'required|string|max:255',
            '*.quantity' => 'required|numeric',
            '*.quantity_unit' => 'required|string|max:30',
            '*.vat_rate' => ['required', 'string', Rule::in(SupportedVatRates::forInvoice())],
            '*.unite_price' => 'sometimes|required_without:brutto_price|numeric',
            '*.brutto_price' => 'sometimes|required_without:unite_price|numeric',
            '*.net_price' => 'sometimes|numeric',
            '*.gross_amount' => 'sometimes|numeric',
            '*.vat_amount' => 'sometimes|numeric',
            '*.currency' => 'required|string|max:3',
            '*.icomment' => 'sometimes|string|max:255',
        ];
    }
}
