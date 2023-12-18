<?php
namespace framework\packages\ContentPackage\controller;

use framework\component\parent\WidgetController;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\ContentPackage\service\ContentTextService;

class ContentTextWidgetController extends WidgetController
{
    const ROUTE_CONFIG = [
        'admin_emailContentTexts' => [
            'documentType' => 'email',
            'containsFrames' => true,
            'frameTextsTitle' => 'email.frames',
            'frameTextsInfo' => 'email.frames.info',
            'contentTextsTitle' => 'email.contents',
            'contentTextsInfo' => 'email.contents.info',
        ],
        'admin_entryContentTexts' => [
            'documentType' => 'entry',
            'containsFrames' => true,
            'frameTextsTitle' => 'entry.frames',
            'frameTextsInfo' => 'entry.frames.info',
            'contentTextsTitle' => 'entry.contents',
            'contentTextsInfo' => 'entry.contents.info',
        ],
        'admin_articleContentTexts' => [
            'documentType' => 'article',
            'containsFrames' => true,
            'frameTextsTitle' => 'article.frames',
            'frameTextsInfo' => 'article.frames.info',
            'contentTextsTitle' => 'article.contents',
            'contentTextsInfo' => 'article.contents.info',
        ]
    ];

    public function getContentTextService() : ContentTextService
    {
        $this->setService('ContentPackage/service/ContentTextService');

        return $this->getService('ContentTextService');
    }
    
    /**
    * Route: [name: admin_contentTexts_widget}, paramChain: /admin/contentTexts/widget]
    */
    public function adminContentTextsWidgetAction()
    {
        $pageRouteName = $this->getRouting()->getPageRoute()->getName();
        $defaultLocale = $this->getContainer()->getDefaultLocale();
        $service = $this->getContentTextService();
        $documentType = self::ROUTE_CONFIG[$pageRouteName]['documentType'];

        $frameTexts = null;
        if (self::ROUTE_CONFIG[$pageRouteName]['containsFrames']) {
            $frameTexts = $service->getAllContentTextParams($documentType, 'frame');
        }
        $contentTexts = $service->getAllContentTextParams($documentType, 'content');

        $viewPath = 'framework/packages/ContentPackage/view/widget/AdminContentTextsWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminContentTextsWidget', $viewPath, [
                'container' => $this->getContainer(),
                'documentType' => self::ROUTE_CONFIG[$pageRouteName]['documentType'],
                'defaultLocale' => $defaultLocale,
                'frameTexts' => $frameTexts,
                'contentTexts' => $contentTexts,
                'frameTextsTitle' => trans(self::ROUTE_CONFIG[$pageRouteName]['frameTextsTitle']),
                'frameTextsInfo' => trans(self::ROUTE_CONFIG[$pageRouteName]['frameTextsInfo']),
                'contentTextsTitle' => trans(self::ROUTE_CONFIG[$pageRouteName]['contentTextsTitle']),
                'contentTextsInfo' => trans(self::ROUTE_CONFIG[$pageRouteName]['contentTextsInfo'])
            ]),
            'data' => []
        ];
        
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_contentText_edit}, paramChain: /admin/contentText/edit]
    */
    public function adminContentTextEditAction()
    {
        $this->wireService('FormPackage/service/FormBuilder');
        $service = $this->getContentTextService();

        $uniqueId = $this->getContainer()->getRequest()->get('uniqueId');
        $isSubmitted = $this->getContainer()->getRequest()->get('submitted');

        $contentTextParams = $service->getContentTextParams($uniqueId);
        $id = null;
        if ($contentTextParams && $contentTextParams['storedContentText']) {
            $id = ($contentTextParams['storedContentText'])->getId();
        }

        $phrase = null;
        if ($contentTextParams && $contentTextParams['phrase']) {
            $phrase = $contentTextParams['phrase'];
        }

        // $submitted = $this->getContainer()->getRequest()->get('submitted');
        // dump($submitted);exit;

        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('ContentPackage');
        $formBuilder->setSubject('contentTextEdit');
        $formBuilder->setPrimaryKeyValue($id);
        $formBuilder->addExternalPost('id');
        $formBuilder->setSaveRequested(false);
        $formBuilder->setAutoSubmit(false);
        $formBuilder->setSubmitted($isSubmitted ? : false);
        $form = $formBuilder->createForm();

        // dump($form->isSubmitted());exit;
        if ($form->isSubmitted() && $form->isValid()) {
            $phrase = $this->getContainer()->getRequest()->get('phrase');
            // dump($this->getContainer()->getRequest()->getAll());exit;
        }

        $attributes = $service->getAttributes($uniqueId);
        // dump($contentTextParams['phrase']);exit;
        if ($contentTextParams['storedContentText']) {
            $form->setEntity($contentTextParams['storedContentText']);
        } else {
            $contentText = $form->getEntity();
            $contentText->setCode($attributes['code']);
            $contentText->setDocumentType($attributes['documentType']);
            $contentText->setLocale($attributes['locale']);
            $contentText->setDocumentPart($attributes['documentPart']);
            // $contentText->setPhrase($phrase);
            $form->setEntity($contentText);
        }

        $form->getEntity()->setPhrase($phrase);

        if ($form->isSubmitted() && $form->isValid()) {
            // dump($form->getEntity());exit;
            $form->getEntity()->getRepository()->store($form->getEntity());
        }

        // dump($this->getContainer()->getRequest()->getAll());
        // dump($phrase);
        // dump($contentTextParams);
        // dump($form->getEntity());exit;
        $titleAndPackageName = $service->getTitleAndPackageName($attributes['code']);

        $form->getValueCollector()->addValue($phrase, 'phrase', 'displayed', 'ContentPackage_contentTextEdit_phrase');

        $contentText = $form->getEntity();

        $viewPath = 'framework/packages/ContentPackage/view/widget/AdminContentTextsWidget/editForm.php';
        $response = [
            'view' => $this->renderWidget('adminContentTextEdit', $viewPath, [
                'form' => $form,
                'contentText' => $contentText,
                'container' => $this->getContainer(),
                'uniqueId' => $uniqueId
            ]),
            'data' => [
                'modalLabel' => $titleAndPackageName['packageName'].': '.$titleAndPackageName['title'].' ('.$attributes['locale'].')'
            ]
        ];
        
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_contentText_reset}, paramChain: /admin/contentText/reset]
    */
    public function adminContentTextResetAction()
    {
        $uniqueId = $this->getContainer()->getRequest()->get('uniqueId');

        $service = $this->getContentTextService();
        $result = $service->removeContentTextByUniqueId($uniqueId);

        $response = [
            'view' => '',
            'data' => [
                'uniqueId' => $uniqueId,
                'result' => $result
            ]
        ];
        
        return $this->widgetResponse($response);
    }

    // private function completeSecondaryArray($primaryArray, $secondaryArray)
    // {
    //     // dump($primaryArray);
    //     $mendedSecondaryArray = [];
    //     foreach ($primaryArray as $primaryKey => $primaryValue) {
    //         if (!isset($secondaryArray[$primaryKey])) {
    //             $mendedSecondaryArray[$primaryKey] = null;
    //         }
    //     }

    //     return $mendedSecondaryArray;
    // }
}