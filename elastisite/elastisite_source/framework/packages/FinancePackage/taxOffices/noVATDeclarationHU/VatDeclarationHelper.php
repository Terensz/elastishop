<?php
namespace framework\packages\FinancePackage\taxOffices\noVATDeclarationHU;

use framework\component\helper\DateUtils;
use framework\component\parent\Service;
use framework\packages\FinancePackage\service\InvoiceCreator;
use NavOnlineInvoice\Config as NavOnlineConfig;
use NavOnlineInvoice\Reporter as NavOnlineReporter;

/*
Docs:
-----
https://onlineszamla.nav.gov.hu/dokumentaciok
*/
class VatDeclarationHelper extends Service
{
    public NavOnlineReporter $reporter;

    public $APIUrl;

    public $APIUserData;

    public $APISoftwareData;

    public function createReporter()
    {
        // $config = $this->vatProfileHandler::getConfig($this->vatProfileHandler->taxOfficeName);
        $navOnlineConfig = new NavOnlineConfig($this->APIUrl, $this->APIUserData, $this->APISoftwareData);
        $this->reporter = new NavOnlineReporter($navOnlineConfig);
    }

    public function createInvoice($invoiceXml, $operation = "CREATE")
    {

    }

    // public function modifyInvoice()
    // {
    //     // return $this->reporter->manageInvoice($this->invoiceXml, "CREATE");
    //     return $this->reporter->manageInvoice($this->invoiceXml, "MODIFY");
    // }

    public function queryTransactionStatus($transactionId)
    {
        return $this->reporter->queryTransactionStatus($transactionId);
    }

    public function queryInvoice($invoiceNumber)
    {
        $invoiceQuery = [
            "invoiceNumber" => $invoiceNumber,
            "invoiceDirection" => "OUTBOUND",
        ];
        $queryResult = $this->reporter->queryInvoiceData($invoiceQuery);

        // return (!$queryResult->auditData) ? null : (string)$queryResult->auditData->transactionId;
        $queryResultXML = (!$queryResult->auditData) ? null : $queryResult;

        $invoiceData = null;
        if ($queryResult->invoiceData) {
            $invoiceData = simplexml_load_string(base64_decode((string)$queryResult->invoiceData));
        }

        return [
            'queryResultXML' => $queryResultXML,
            'decodedInvoiceXML' => $invoiceData
        ];
    }

    public function handleInvoice(InvoiceCreator $invoiceCreator, $invoiceNumber = 'default', $transactionId = null, $loop = 1, $transactionStatusXML = null)
    {
        if ($loop > 1) {
            sleep(1);
        }

        if ($invoiceNumber == 'default') {
            $invoiceNumber = (string)$invoiceCreator->invoiceXml->invoiceNumber;
        }

        $queryResult = $this->queryInvoice($invoiceNumber);
        $queryResultXML = $queryResult['queryResultXML'];

        if ($queryResultXML && isset($queryResultXML->auditData)) {
            $transactionId = self::getAuditData('transactionId', $queryResultXML);
            $transactionStatusXML = $this->queryTransactionStatus($transactionId);
            $processingResult = self::analyzeProcessingResult($transactionStatusXML);

            if ($processingResult['validationErrorCode']) {
                $this->processError($invoiceCreator, $processingResult);
                return $invoiceCreator;
            }

            // dump($processingResult);
            // dump($queryResult);
            // // $queryResult1 = $this->queryInvoice('ESWM202316500004530');
            // // dump($queryResult1);
            // dump('========');exit;
        }
        // dump('========');exit;



        // dump($queryResult);exit;
        // if ($queryResultXML) {
        //     dump($queryResult);exit;
        // }

        if ($queryResultXML && isset($queryResultXML->auditData)) {
            $invoiceCreator = $this->processQueryResult($invoiceCreator, $queryResultXML);
        } else {
            if ($loop < 3) {
                $operation = $invoiceCreator->invoiceHeader->getCorrectedInvoiceNumber() ? "STORNO" : "CREATE";

                if ($loop == 1) {
                    $transactionId = $this->createInvoice($invoiceCreator->invoiceXml, $operation);
                }

                // $transactionStatusXML = $this->queryTransactionStatus($transactionId);
                // $processingResult = self::analyzeProcessingResult($transactionStatusXML);
                // $queryResult = $this->queryInvoice($invoiceNumber);


                return $this->handleInvoice($invoiceCreator, $invoiceNumber, $transactionId, ($loop + 1), $transactionStatusXML);
            } else {
                $transactionStatusXML = $this->queryTransactionStatus($transactionId);
                dump($transactionStatusXML);
                dump('Bad!');
                dump($queryResult);exit;
                /**
                 * It means: something went wrong creating the invoice
                */
            }
        }

        // dump($queryResult->auditData);exit;

        return $invoiceCreator;
        // return [
        //     // 'queryResult' => $queryResult,
        //     'transactionId' => $transactionId,
        //     'transactionStatus' => $transactionStatus
        // ];
    }

    public static function analyzeProcessingResult($transactionStatusXML)
    {
        $processingResultXML = $transactionStatusXML->processingResults->processingResult;
        $businessValidationMessages = $processingResultXML->businessValidationMessages;
        $validationResultCode = (string)$businessValidationMessages->validationResultCode;
        $validationErrorCode = (string)$businessValidationMessages->validationErrorCode;
        $validationMessage = (string)$businessValidationMessages->message;

        $processingResult = [
            'commStatus' => (string)$processingResultXML->invoiceStatus,
            'validationResultCode' => !empty($validationResultCode) ? $validationResultCode : null,
            'validationErrorCode' => !empty($validationErrorCode) ? $validationErrorCode : null,
            'validationMessage' => !empty($validationMessage) ? $validationMessage : null,
        ];
        // dump($processingResult);exit;

        return $processingResult;
    }

    public function processError($invoiceCreator, $processingResult)
    {
        $invoiceHeader = $invoiceCreator->invoiceHeader;
        $invoiceHeader->setTaxOfficeCommStatus($processingResult['commStatus']);
        $invoiceHeader->setTaxOfficeErrorCode($processingResult['validationErrorCode']);
        $invoiceHeader->setTaxOfficeErrorMessage($processingResult['validationMessage']);
        $invoiceCreator->invoiceHeader = $invoiceHeader->getRepository()->store($invoiceHeader);

        return $invoiceCreator;
    }

    /**
     * insCusUser:
    */
    public function processQueryResult($invoiceCreator, $queryResultXML)
    {
        $invoiceHeader = $invoiceCreator->invoiceHeader;
        // $invoiceHeader = $repo->findOneBy(['conditions' => [['key' => 'invoice_number', 'value' => $invoiceNumber]]]);
        // $reportedAt = DateUtils::getFormattedDate(self::getAuditData('insdate', $queryResult));
        $transactionId = self::getAuditData('transactionId', $queryResultXML);
        $invoiceHeader->setTaxOfficeTransactionId($transactionId);
        $reportedAt = DateUtils::getDate(self::getAuditData('insdate', $queryResultXML));
        $invoiceHeader->setReportedAt($reportedAt);
        $transactionStatusObject = $this->queryTransactionStatus($transactionId);
        $commStatus = (string)$transactionStatusObject->result->funcCode;
        $invoiceHeader->setTaxOfficeCommStatus($invoiceCreator->taxOffice::convertCommStatus($commStatus));

        $invoiceCreator->invoiceHeader = $invoiceHeader->getRepository()->store($invoiceHeader);
        
        return $invoiceCreator;
    }

    public static function getAuditData($property, $queryResultXML)
    {
        return (string)$queryResultXML->auditData->$property;
    }
}
