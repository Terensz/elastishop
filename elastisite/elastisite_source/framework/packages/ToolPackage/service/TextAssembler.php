<?php
namespace framework\packages\ToolPackage\service;

// use PHPMailer\PHPMailer\Exception;

use framework\component\exception\ElastiException;
use framework\kernel\utility\FileHandler;
use framework\component\parent\AccessoryController;
use framework\packages\ContentPackage\service\ContentTextService;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Content;

class TextAssembler extends AccessoryController
{
    const FRAMED_DOCUMENT_TYPES = [
        'email'
    ];

    protected $contentTextService;

    /**
     * E.g. the original email frame uses this, if it's "WebshopPackage", and in the project_config.txt file: webshopEnabled=true, 
     * than the predefined text will also delivered to the Webshop users. (check the frame/{locale}/default.php)
     * --------------
     * Not obligatory
     * User defined
    */
    protected $package;

    /**
     * You can make the body file in the project, and set the filename here.
     * --------------
     * Essential 
     * User defined
    */
    // protected $code;

    /**
     * The "code" can be exploded to "packageName" and "referenceKey". The "referenceKey" refers to the function of the text.
     * --------------
     * Essential 
     * User defined
    */
    protected $referenceKey;

    /**
     * @todo email, article, notice
     * --------------
     * Essential
     * User defined
    */
    protected $documentType;

    protected $framePackageName = 'FrameworkPackage';

    /**
     * If text uses a frame (like emails), than you can make the frame file in the preject, and set the filename here.
     * --------------
     * Not obligatory
     * User defined
    */
    protected $frameReferenceKey = 'defaultFrame';

    /**
     * Changes [placeHolder] in text to $key of this array
     * --------------
     * Not obligatory (not required for static texts without any variables)
     * User defined
    */
    protected $placeholdersAndValues = array();

    /**
     * List of view filenames which are coming as placeholders. These will be replaced to embedded content of the files.
     * --------------
     * Not obligatory
     * User defined
    */
    protected $embeddedViewKeys = array();

    /**
     * It's a temporary variable, used by the rendering process
     * --------------
     * Class defined
    */
    private $textContent;

    /**
     * This variable is used by the rendering process, makes unnecessary the use of the create() method.
     * --------------
     * Class defined
    */
    private $contentRendered = false;

    /**
     * The result of the rendering process
     * --------------
     * Class defined
     * Output of the class
    */
    private $view;

    public function setPackage($package) 
    {
        $this->package = $package;
    }

    // public function setCode($code)
    // {
    //     $this->code = $code;
    // }

    public function setReferenceKey($referenceKey)
    {
        $this->referenceKey = $referenceKey;
    }

    public function getCode()
    {
        return $this->package.'_'.$this->referenceKey;
    }

    public function setDocumentType($documentType)
    {
        $this->documentType = $documentType;
    }

    public function setFramePackageName($framePackageName) 
    {
        $this->framePackageName = $framePackageName;
    }

    public function setFrameReferenceKey($frameReferenceKey) 
    {
        $this->frameReferenceKey = $frameReferenceKey;
    }

    public function setPlaceholdersAndValues($placeholdersAndValues) 
    {
        $this->placeholdersAndValues = $placeholdersAndValues;
    }

    public function setEmbeddedViewKeys($embeddedViewKeys)
    {
        $this->embeddedViewKeys = $embeddedViewKeys;
    }

    /**
     * Obsolete method. In the future, you should only use the getView() method, and that will also do the rendering process.
    */
    public function create()
    {
        $this->render();
    }

    /**
     * 1.: renders body content
     * 2.: if it's framed: renders the frame, also puts the body content inside, and sets the whole as the view
     * 3.: it it's NOT framed: sets the body content to the view
    */
    private function render()
    {
        $this->renderContent($this->getCode());

        if (in_array($this->documentType, self::FRAMED_DOCUMENT_TYPES)) {
            $this->renderFrame();
        } else {
            $this->view = $this->textContent;
        }

        $this->contentRendered = true;
    }

    /**
     * Returns the output of the class
    */
    public function getView()
    {
        if ($this->contentRendered == false) {
            $this->contentRendered = true;
            $this->render();
        }
        return $this->view;
    }

    public function setContentTextService(ContentTextService $contentTextService)
    {
        $this->contentTextService = $contentTextService;
    }

    public function getContentTextService() : ContentTextService
    {
        if ($this->contentTextService) {
            return $this->contentTextService;
        }
        $this->setService('ContentPackage/service/ContentTextService');

        return $this->getService('ContentTextService');
    }

    public function createUniqueId($documentPart, $code)
    {
        return $this->documentType.'-'.$documentPart.'-'.$this->getSession()->getLocale().'-'.$code;
    }

    private function renderFrame()
    {
        $contentTextParams = $this->getContentTextService()->getContentTextParams($this->createUniqueId('frame', $this->framePackageName.'_'.$this->frameReferenceKey));
        $renderedFrame = html_entity_decode($contentTextParams['phrase']);

        $renderedFrameIncudesTextContent = str_replace('[textContent]', $this->textContent, $renderedFrame);

        $this->view = $renderedFrameIncudesTextContent;
    }

    private function renderContent($code, $placeholderKey = null, $return = false)
    {
        $contentTextParams = $this->getContentTextService()->getContentTextParams($this->createUniqueId('content', $code));
        $view = html_entity_decode($contentTextParams['phrase']);

        $view = $this->processPlaceholders($view, $placeholderKey);

        if ($return) {
            return $view;
        } else {
            $this->textContent = $view;
        }
    }

    public function processPlaceholders($text, $placeholderKeyRequest = null, $placeholderValueRequest = null)
    {
        // dump($text);
        $placeholders = $placeholderKeyRequest ? $this->placeholdersAndValues[$placeholderKeyRequest] : $placeholders = $this->placeholdersAndValues;
        // $embeddedViewKeys = $this->embeddedViewKeys;

        foreach ($placeholders as $placeholderKey => $placeholderValue) {
            $placeholder = '['.$placeholderKey.']';
            // if (!is_string($text)) {
            //     dump($text);exit;
            // }
            $placeholderFound = strpos($text, $placeholder) === false ? false : true;

            if ($placeholderFound) {
                if (in_array($placeholderKey, $this->embeddedViewKeys)) {
                    $embeddedContent = '';
                    $embeddedLoopCounter = 0;
                    foreach ($placeholderValue as $loop) {
                        $embeddedContent .= ($embeddedLoopCounter > 0 ? "\n" : "").$this->renderContent($this->package.'_'.$placeholderKey, $placeholderKey, true);
                        foreach ($loop as $key => $value) {
                            $embeddedContent = str_replace('['.$key.']', $value, $embeddedContent);
                        }
                        $embeddedContent .= '<br>';
                        $embeddedLoopCounter++;
                    }
                    $text = str_replace($placeholder, $embeddedContent, $text);
                } else {
                    // dump($placeholder);
                    // dump($placeholderValue);
                    if (is_array($placeholderValue)) {
                        throw new ElastiException('alma');
                    }
                    if ($placeholder && $placeholderValue && $text) {
                        $text = str_replace($placeholder, $placeholderValue, $text);
                    }
                }
            }
        }
        return $text;
    }
}