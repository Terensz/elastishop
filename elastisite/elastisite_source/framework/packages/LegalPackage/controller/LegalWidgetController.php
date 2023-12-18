<?php
namespace framework\packages\LegalPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\kernel\utility\BasicUtils;
use framework\packages\LegalPackage\entity\VisitorConsent;
use framework\packages\LegalPackage\repository\VisitorConsentRepository;
use framework\packages\LegalPackage\service\ReaderDocuments;
use framework\packages\ToolPackage\service\TextAssembler;

class LegalWidgetController extends WidgetController
{
    /**
    * Route: [name: widget_UsersDocumentsWidget, paramChain: /widget/UsersDocumentsWidget]
    */
    public function usersDocumentsWidgetAction()
    {
        $viewPath = 'framework/packages/LegalPackage/view/widget/UsersDocumentsWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('UsersDocumentsWidget', $viewPath, [
                // 'container' => $this->getContainer()
                // 'httpDomain' => App::getContainer()->getUrl()->getHttpDomain()
            ]),
            'data' => null
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: document_reader_widget, paramChain: /documents/documentReader/widget]
    */
    public function documentReaderWidgetAction()
    {
        $referenceKey = BasicUtils::kebabToCamelCase(str_replace('documents/', '', $this->getContainer()->getRouting()->getPageRoute()->getParamChain()));
        // dump($referenceKey);exit;
        $this->wireService('ToolPackage/service/TextAssembler');
        $textAssembler = new TextAssembler();
        $textAssembler->setPackage('LegalPackage');
        $textAssembler->setDocumentType('article');
        $textAssembler->setReferenceKey($referenceKey);
        $textAssembler->setPlaceholdersAndValues([
            'name' => 'Alma Janos',
            'httpDomain' => App::getContainer()->getUrl()->getHttpDomain(),
            'relativeContactUrl' => App::getContainer()->getCompanyData('relativeContactUrl'),
            'activationLink' => App::getContainer()->getUrl()->getHttpDomain().'/regisztracio/aktivalas/almakod',
            'companyName' => App::getContainer()->getCompanyData('name')
        ]);

        $textAssembler->create();
        $textView = $textAssembler->getView();

        $viewPath = 'framework/packages/LegalPackage/view/widget/DocumentReaderWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('DocumentReaderWidget', $viewPath, [
                'title' => $this->getContainer()->getRouting()->getPageRoute()->getTitle(),
                'textView' => $textView
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    // public function documentReaderWidgetAction_OLD()
    // {
    //     $this->getContainer()->wireService('LegalPackage/service/ReaderDocuments');
    //     $readerDocuments = new ReaderDocuments();
    //     $readerDocuments->setViewsOfSlugs(array(
    //         'terms-of-use' => 'termsOfUse.php',
    //         'what-is-gdpr' => 'gdpr.php',
    //         'how-do-we-protect-personal-data' => 'dataProtect.php',
    //         'privacy-statement' => 'privacyStatement.php',
    //         'about-removing-personal-data' => 'removingPersonalData.php',
    //         'cookie-info' => 'cookieInfo.php',
    //         'terms-of-use' => 'termsOfUse.php',
    //         'faq' => 'faq.php'
    //     ));
    //     $readerDocuments->setViewPathBase('framework/packages/LegalPackage/view/readerDocuments/');
    //     $paramChain = $this->getUrl()->getParamChain();
    //     $readerDocuments->setSlug(BasicUtils::explodeAndGetElement($paramChain, '/', 'last'));
        
    //     $viewPath = 'framework/packages/LegalPackage/view/widget/DocumentReaderWidget/widget.php';
    //     $response = [
    //         'view' => $this->renderWidget('DocumentReaderWidget', $viewPath, [
    //             // 'container' => $this->getContainer(),
    //             'title' => $this->getContainer()->getRouting()->getPageRoute()->getTitle(),
    //             'readerDocument' => $readerDocuments->render()
    //         ]),
    //         'data' => null
    //     ];

    //     return $this->widgetResponse($response);
    // }
}
