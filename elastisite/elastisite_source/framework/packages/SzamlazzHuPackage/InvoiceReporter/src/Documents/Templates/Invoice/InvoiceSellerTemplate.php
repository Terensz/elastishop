<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents\Templates\Invoice;

use DOMDocument;
use DOMElement;
use Trianity\SzamlazzHu\Documents\Templates\SellerTemplate;

/**
 * <elado>
 * <!-- Details of the merchant-->
 *   <bank>BB</bank>
 *   <!-- bank name -->
 *   <bankszamlaszam>11111111-22222222-33333333</bankszamlaszam>
 *   <!-- bank account -->
 *   <emailReplyto></emailReplyto>
 *   <!-- reply e-mail address -->
 *   <emailTargy>Invoice notification</emailTargy>
 *   <!-- e-mail subject -->
 *   <emailSzoveg>mail text</emailSzoveg>
 *   <!-- text of e-mail -->
 * </elado>
 */
class InvoiceSellerTemplate implements SellerTemplate
{
    /**
     * @param  array<string>  $seller
     */
    public static function create(array $seller, DOMDocument $xml): DOMElement
    {
        $base = $xml->createElement('elado');
        $bankname = $xml->createElement('bank');
        $cdata_bankname = $xml->createCDATASection($seller['bank_name']);
        $bankname->appendChild($cdata_bankname);
        $base->appendChild($bankname);

        $base->appendChild($xml->createElement('bankszamlaszam', $seller['bank_account_number']));
        $base->appendChild($xml->createElement('emailReplyto', ($seller['email_replyto'] ?? '')));

        $emailsubject = $xml->createElement('emailTargy');
        $cdata_esubject = $xml->createCDATASection($seller['email_subject'] ?? '');
        $emailsubject->appendChild($cdata_esubject);
        $base->appendChild($emailsubject);

        $emailbody = $xml->createElement('emailSzoveg');
        $cdata_ebody = $xml->createCDATASection($seller['email_body'] ?? '');
        $emailbody->appendChild($cdata_ebody);
        $base->appendChild($emailbody);

        return $base;
    }
}
