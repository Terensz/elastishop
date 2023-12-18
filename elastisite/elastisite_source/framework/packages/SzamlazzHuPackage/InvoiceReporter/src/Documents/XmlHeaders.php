<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents;

class XmlHeaders
{
    /**
     * All the available actions.
     */
    final public const ACTIONS = [

        // Used for cancelling (existing) invoices
        'CANCEL_INVOICE' => [
            'name' => 'action-szamla_agent_st',
            'schema' => [
                /**
                 * Important! Please always note order
                 */
                'xmlszamlast', // Action name
                'http://www.szamlazz.hu/xmlszamlast', // Namespace
                'http://www.szamlazz.hu/xmlszamlast xmlszamlast.xsd', // Schema location
            ],
        ],

        // Used for deleting (existing) proforma invoices
        'DELETE_PROFORMA_INVOICE' => [
            'name' => 'action-szamla_agent_dijbekero_torlese',
            'schema' => [
                'xmlszamladbkdel',
                'http://www.szamlazz.hu/xmlszamladbkdel',
                'http://www.szamlazz.hu/xmlszamladbkdel http://www.szamlazz.hu/docs/xsds/szamladbkdel/xmlszamladbkdel.xsd',
            ],
        ],

        // Used for obtaining (both) invoices and proforma invoices
        'GET_COMMON_INVOICE' => [
            'name' => 'action-szamla_agent_xml',
            'schema' => [
                'xmlszamlaxml',
                'http://www.szamlazz.hu/xmlszamlaxml',
                'http://www.szamlazz.hu/xmlszamlaxml http://www.szamlazz.hu/docs/xsds/agentpdf/xmlszamlaxml.xsd',
            ],
        ],

        // Used for obtaining PDF invoices
        'GET_PDF_INVOICE' => [
            'name' => 'action-szamla_agent_pdf',
            'schema' => [
                'xmlszamlapdf',
                'http://www.szamlazz.hu/xmlszamlapdf',
                'http://www.szamlazz.hu/xmlszamlapdf https://www.szamlazz.hu/szamla/docs/xsds/agentpdf/xmlszamlapdf.xsd',
            ],
        ],

        // Used to upload (create) new common and proforma invoice
        'UPLOAD_COMMON_INVOICE' => [
            'name' => 'action-xmlagentxmlfile',
            'schema' => [
                'xmlszamla',
                'http://www.szamlazz.hu/xmlszamla',
                'http://www.szamlazz.hu/xmlszamla http://www.szamlazz.hu/docs/xsds/agent/xmlszamla.xsd',
            ],
        ],

        // Used to create / update receipt
        'UPLOAD_RECEIPT' => [
            'name' => 'action-szamla_agent_nyugta_create',
            'schema' => [
                'xmlnyugtacreate',
                'http://www.szamlazz.hu/xmlnyugtacreate',
                'http://www.szamlazz.hu/xmlnyugtacreate http://www.szamlazz.hu/docs/xsds/nyugta/xmlnyugtacreate.xsd',
            ],
        ],

        // Cancelling receipt
        'CANCEL_RECEIPT' => [
            'name' => 'action-szamla_agent_nyugta_storno',
            'schema' => [
                'xmlnyugtast',
                'http://www.szamlazz.hu/xmlnyugtast',
                'http://www.szamlazz.hu/xmlnyugtast http://www.szamlazz.hu/docs/xsds/nyugtast/xmlnyugtast.xsd',
            ],
        ],

        // Obtaining a single receipt
        'GET_RECEIPT' => [
            'name' => 'action-szamla_agent_nyugta_get',
            'schema' => [
                'xmlnyugtaget',
                'http://www.szamlazz.hu/xmlnyugtaget',
                'http://www.szamlazz.hu/xmlnyugtaget http://www.szamlazz.hu/docs/xsds/nyugtaget/xmlnyugtaget.xsd',
            ],
        ],

        // Querying taxpayers
        // This interface is used to query the validity of a VAT number.
        // The data is from the Online Invoice Platform of NAV, the Hungarian National Tax and Customs Administration.
        'GET_TAXPAYER' => [
            'name' => 'action-szamla_agent_taxpayer',
            'schema' => [
                'xmltaxpayer',
                'http://www.szamlazz.hu/xmltaxpayer',
                'http://www.szamlazz.hu/xmltaxpayer http://www.szamlazz.hu/docs/xsds/agent/xmltaxpayer.xsd',
            ],
        ],
    ];
}
