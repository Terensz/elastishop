<?php
namespace framework\kernel\component;

use App;
use framework\kernel\base\Container;
use framework\kernel\DbManager\manager\DbManager;
use framework\kernel\EntityManager\EntityManager;
use framework\kernel\EntityRelationMapper\EntityRelationMapper;
use framework\kernel\request\Request;
use framework\kernel\request\Session;
use framework\kernel\request\UploadRequest;
use framework\kernel\routing\Url;
use framework\kernel\security\Security;
use framework\packages\FrameworkPackage\service\SettingsService;

class Kernel
{
    public function __construct()
    {
        // $this->container = Container::getSelfObject();
    }

    public function getCurrentTimestamp()
    {
        return Container::getSelfObject()->getCurrentTimestamp();
    }

    public function wrapExceptionParams($placeholderParams = array())
    {
        // return array(
        //     'container' => $this->getContainer(),
        //     'placeholderParams' => $placeholderParams
        // );
        return Container::getSelfObject()->wrapExceptionParams($placeholderParams);
    }

    public function getCompanyData($key = null)
    {
        return Container::getSelfObject()->getKernelObject('Config')->getCompanyData($key);
    }

    public function getProjectData($key = null)
    {
        return Container::getSelfObject()->getKernelObject('Config')->getProjectData($key);
    }

    /**
     * @var $viewFilePath: The full path to the view-file on the server, e.g.: /var/www/html/projects/MyProject/views/main.html
     * @var $viewData: an associative array, where the keys are the variable-names you want to use in the view-file. 
     * E.g.: $viewData = ['name' => 'John Doe', 'username' => 'doe123'] will result that you can use $name and $username variables 
     * inside the view-file, with the passed values.
     * @return string: method will return the full rendered document as a string, without echoing it.
     */
    public function renderView($viewFilePath, $viewData = []) : string
    {
        return App::renderView($viewFilePath, $viewData);
    }

    public function isGranted($permission, $userObject = null)
    {
        return $this->getContainer()->isGranted($permission, $userObject);
    }

    public function encrypt($data)
    {
        $crypter = $this->getContainer()->getService('Crypter');

        return $crypter->encrypt($data);
    }

    public function decrypt($data, $oldMethod = false, $debug = false)
    {
        $crypter = $this->getContainer()->getService('Crypter');

        return $crypter->decrypt($data, $oldMethod, $debug);
    }

    public function isEncrypted($data)
    {
        $crypter = $this->getContainer()->getService('Crypter');
        
        return $crypter->isEncrypted($data);
    }

    public function getSettings() : ? SettingsService
    {
        $this->getContainer()->setService('FrameworkPackage/service/SettingsService');

        return $this->getContainer()->getService('SettingsService');
    }

    public function getERM() : EntityRelationMapper
    {
        return (object)Container::getSelfObject()->getKernelObject('EntityRelationMapper');
    }

    public function getEntityManager() : EntityManager
    {
        return (object)Container::getSelfObject()->getKernelObject('EntityManager');
    }

    public function getDbManager() : DbManager
    {
        return (object)Container::getSelfObject()->getKernelObject('DbManager');
    }

    public function getSecurity() : Security
    {
        return (object)Container::getSelfObject()->getKernelObject('Security');
    }

    // public function getWebsite()
    // {
    //     return Container::getSelfObject()->getWebsite();
    // }

    public function getContainer() : Container
    {
        return (object)Container::getSelfObject();
    }

    public function getSession() : Session
    {
        return (object)Container::getSelfObject()->getKernelObject('Session');
    }

    public function getUrl() : Url
    {
        return (object)Container::getSelfObject()->getKernelObject('Url');
    }

    public function getKernelObject($objectName)
    {
        return (object)Container::getSelfObject()->getKernelObject($objectName);
    }

    public function wireService($serviceNamespace)
    {
        return Container::getSelfObject()->wireService($serviceNamespace);
    }

    public function wireServiceDir($serviceNamespace)
    {
        return Container::getSelfObject()->wireServiceDir($serviceNamespace);
    }

    public function setService($serviceNamespace, $alias = null)
    {
        return Container::getSelfObject()->setService($serviceNamespace, $alias);
    }

    public function getService($alias)
    {
        return Container::getSelfObject()->getService($alias);
    }

    public function addSystemMessage($text, $level, $subject)
    {
        return Container::getSelfObject()->addSystemMessage($text, $level, $subject);
    }

    public function getSystemMessages($args = [])
    {
        return Container::getSelfObject()->getSystemMessages($args);
    }

    public function getRequest() : Request
    {
        return Container::getSelfObject()->getKernelObject('Request');
    }

    public function getRouting()
    {
        return Container::getSelfObject()->getRouting();
    }

    public function getUploadRequest() : UploadRequest
    {
        return Container::getSelfObject()->getKernelObject('UploadRequest');
    }

    public function getGlobal($paramName)
    {
        return Container::getSelfObject()->getKernelObject('Config')->getGlobal($paramName);
    }

    public function setGlobal($paramName, $paramValue)
    {
        return Container::getSelfObject()->getKernelObject('Config')->setGlobal($paramName, $paramValue);
    }

    public function isSetGlobal($paramName)
    {
        return Container::getSelfObject()->getKernelObject('Config')->isSetGlobal($paramName);
    }
}
