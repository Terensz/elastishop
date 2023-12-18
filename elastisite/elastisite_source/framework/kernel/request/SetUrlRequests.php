<?php
namespace framework\kernel\request;

use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;

class SetUrlRequests extends Kernel
{
    private $isAjax = false;

    public function __construct()
    {
        $this->setIsAjax();
        $this->setProtocol();
        $this->setAjaxUrl();
        $this->setFullUrl();
        $this->setPseudoDomain();
        $this->setElastiSiteRoot();
        $this->setFullDomain();
        $this->setMainDomain();
        $this->setHttpDomain();
        $this->setAjaxParamChain();
        $this->setParamChain();
        // $this->setEnv();
        $this->collectAndSetUrlRequests();
    }

    public function getRefererPart($part)
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            if ($part == 'fullUrl') {
                return $_SERVER['HTTP_REFERER'];
            }

            $referer = explode('://', $_SERVER['HTTP_REFERER']);
            if ($part == 'protocol') {
                return $referer[0].'://';
            }
        } else {
            return '!!!';
        }
    }

    public function setIsAjax()
    {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->isAjax = true;
        }
        $this->getContainer()->getUrl()->setIsAjax($this->isAjax);
    }

    public function setProtocol()
	{
        if ($this->isAjax) {
            $this->getContainer()->getUrl()->setProtocol($this->getRefererPart('protocol'));
        } else {
            $this->getContainer()->getUrl()->setProtocol(
                ((isset($_SERVER['HTTPS']) and ($_SERVER['HTTPS'] == 'on')) ? 'https' : 'http') . '://'
            );
        }
	}

    public function setAjaxUrl()
    {
        if ($this->isAjax) {
            $this->getContainer()->getUrl()->setAjaxUrl(
                $this->getContainer()->getUrl()->getProtocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
            );
        }
    }

    public function setFullUrl()
	{
        if ($this->isAjax) {
            $this->getContainer()->getUrl()->setFullUrl($this->getRefererPart('fullUrl'));
        } else {
            $this->getContainer()->getUrl()->setFullUrl(
                $this->getContainer()->getUrl()->getProtocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
            );
        }
	}

    public function setPseudoDomain()
    {
        $pseudoDomain = $_SERVER['SCRIPT_NAME'];
        $pseudoDomain = str_replace('index.php?r=', '', $pseudoDomain);
        $pseudoDomain = str_replace('index.php', '', $pseudoDomain);
        $pseudoDomain = trim($pseudoDomain, '/');
        $this->getContainer()->getUrl()->setPseudoDomain('/'.$pseudoDomain);
    }

    public function setElastiSiteRoot()
    {
        // dump($this->getContainer()->getUrl()->getPseudoDomain());
        // dump($_SERVER['SCRIPT_NAME']);
        // dump($_SERVER['DOCUMENT_ROOT']);exit;
        $siteRoot = rtrim($_SERVER['DOCUMENT_ROOT'], '/').'/'.trim($this->getContainer()->getUrl()->getPseudoDomain(), '/');
        $siteRootParts = explode('/', $siteRoot);
        $elastiSiteRoot = '';
        foreach ($siteRootParts as $siteRootPart) {
            if ($siteRootPart && $siteRootPart != 'webroot' && $siteRootPart != '') {
                $elastiSiteRoot .= '/'.$siteRootPart;
            }
        }
        $this->getContainer()->getUrl()->setElastiSiteRoot($elastiSiteRoot); 
    }

    public function setFullDomain()
	{
        $fullDomain = $_SERVER['SERVER_NAME'] . (($_SERVER['SERVER_PORT'] != '80')
            ? (':' . $_SERVER['SERVER_PORT']).$this->getContainer()->getUrl()->getPseudoDomain()
            : ''.$this->getContainer()->getUrl()->getPseudoDomain());
        $this->getContainer()->getUrl()->setFullDomain(str_replace(':443', '', trim($fullDomain, '/')));
	}

    public function setMainDomain()
	{
        $a = explode('.', $this->getContainer()->getUrl()->getFullDomain());
        $this->getContainer()->getUrl()->setMainDomain(
            (count($a) >= 2) ? $a[count($a)-2].'.'.$a[count($a)-1] : $this->getMainDomainFromLocalAddress()
        );
	}

    /**
	* localhost/websiteName , websiteName
    */
	public function getMainDomainFromLocalAddress()
	{
        $a = explode('/', $this->getContainer()->getUrl()->getFullDomain());
        return (count($a) > 1)
            ? (($a[0] == 'localhost') ? $a[1] : $a[0])
            : $a[0];
    }

    public function setHttpDomain()
	{
		$this->getContainer()->getUrl()->setHttpDomain(
            $this->getContainer()->getUrl()->getProtocol() . $this->getContainer()->getUrl()->getFullDomain()
        );
	}

    public function setAjaxParamChain()
	{
		$paramChain = str_replace(
            $this->getContainer()->getUrl()->getHttpDomain() . '/' ,
            '' ,
            ($this->getContainer()->getUrl()->getAjaxUrl() ? : '')
        );
        $paramChain = str_replace(
            $this->getContainer()->getUrl()->getHttpDomain() ,
            '' ,
            ($this->getContainer()->getUrl()->getAjaxUrl() ? : '')
        );
		$paramChain = str_replace('index.php?r=' , '' , $paramChain);
		$paramChain = str_replace('index.php?' , '' , $paramChain);
        $paramChain = $this->removeConventionalParameteringFromParamChain($paramChain);
		$this->getContainer()->getUrl()->setAjaxParamChain(
            trim(str_replace('index.php' , '' , $paramChain), '/')
        );
    }

    public function removeConventionalParameteringFromParamChain($paramChain)
	{
        $parts = explode('?', $paramChain);
        return $parts[0];
    }

    /**
	* page_2/developer/recovery_useradmin
    */
	public function setParamChain()
	{
		$paramChain = str_replace($this->getContainer()->getUrl()->getHttpDomain() . '/' ,'' , $this->getContainer()->getUrl()->getFullUrl());
        $paramChain = str_replace($this->getContainer()->getUrl()->getHttpDomain() ,'' , $this->getContainer()->getUrl()->getFullUrl());
		$paramChain = str_replace('index.php?r=' , '' , $paramChain);
		$paramChain = str_replace('index.php?' , '' , $paramChain);
        $paramChain = $this->removeConventionalParameteringFromParamChain($paramChain);
		$this->getContainer()->getUrl()->setParamChain(trim(str_replace('index.php' , '' , $paramChain), '/'));
    }

    public function collectAndSetUrlRequests()
	{
        $paramArray = BasicUtils::explodeString('/', $this->getKernelObject('Url')->getParamChain(), 4);
        $command = $this->getCommandFromParameter($paramArray[0]);
        if ($command) {
            $this->getKernelObject('Url')->setCommand($command);
            array_splice($paramArray, 0, 1);
        }
        if ($paramArray[0] == '') {
            $paramArray[0] = 'homepage';
        }
        $this->getKernelObject('Url')->setMainRouteRequest($paramArray[0]);
        $this->getKernelObject('Url')->setSubRouteRequest($paramArray[1]);
        if (count($paramArray) > 2) {
            for ($i = 2; $i < count($paramArray); $i++) {
                $details[] = $paramArray[$i];
            }
        }
        $this->getKernelObject('Url')->setDetails($details);
    }

    public function getCommandFromParameter($parameter)
    {
        $commands = array('documentFrame');
        if (in_array($parameter, $commands)) {
            return $parameter;
        } else {
            return null;
        }
    }
}
