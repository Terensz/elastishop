<?php
namespace framework\kernel\routing;

use framework\kernel\component\Kernel;

class Url extends Kernel
{
    private $isAjax = false;
    private $ajaxUrl;
    private $ajaxParamChain;
    private $command;
    private $protocol;
    private $fullUrl;
    private $pseudoDomain;
    private $elastiSiteRoot;
    private $fullDomain;
    private $mainDomain;
    private $httpDomain;
    private $paramChain;
    private $mainRouteRequest;
    private $subRouteRequest;
    private $mainRoute;
    private $subRoute;
    private $pageRoute;
    private $details = array();

    public function __construct()
    {

    }

    public function setIsAjax($isAjax)
    {
        $this->isAjax = $isAjax;
    }

    public function getIsAjax()
    {
        return $this->isAjax;
    }

    /**
	* login_widget
    */
    public function setAjaxUrl($ajaxUrl)
    {
        $this->ajaxUrl = $ajaxUrl;
    }

    public function getAjaxUrl()
    {
        return $this->ajaxUrl;
    }

    /**
	* teaser_widget/homepage
    */
    public function setAjaxParamChain($ajaxParamChain)
    {
        $this->ajaxParamChain = $ajaxParamChain;
    }

    public function getAjaxParamChain()
    {
        return $this->ajaxParamChain;
    }

    /**
	* pageadmin
    */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    public function getCommand()
	{
        return $this->command;
    }

    /**
	* http://
    */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }

    public function getProtocol()
	{
        return $this->protocol;
    }

    /**
    * tidy: http://terence.tendero.hu/page_2/developer/recovery_useradmin
	* untidy: http://terence.tendero.hu/index.php?r=page_2/developer/recovery_useradmin
    */
    public function setFullUrl($fullUrl)
    {
        $this->fullUrl = $fullUrl;
    }

    public function getFullUrl()
	{
        return $this->fullUrl;
    }

    /**
    * localhost/testsite
    */
    public function setPseudoDomain($pseudoDomain)
    {
        $this->pseudoDomain = $pseudoDomain;
    }

    public function getPseudoDomain()
    {
        return $this->pseudoDomain;
    }

    public function setElastiSiteRoot($elastiSiteRoot)
    {
        $this->elastiSiteRoot = $elastiSiteRoot;
    }

    public function getElastiSiteRoot()
    {
        return $this->elastiSiteRoot;
    }

    /**
	* terence.tendero.hu
    */
    public function setFullDomain($fullDomain)
    {
        $this->fullDomain = $fullDomain;
    }

    public function getFullDomain()
	{
        return $this->fullDomain;
    }

    /**
	* tendero.hu
    */
    public function setMainDomain($mainDomain)
    {
        $this->mainDomain = $mainDomain;
    }

    public function getMainDomain()
	{
        return $this->mainDomain;
    }

    /**
	* http://terence.tendero.hu/
    */
    public function setHttpDomain($httpDomain)
    {
        $this->httpDomain = $httpDomain;
    }

    public function getHttpDomain()
	{
        return $this->httpDomain;
    }

    /**
	* news/article/325
    */
    public function setParamChain($paramChain)
    {
        $this->paramChain = $paramChain;
    }

    public function getParamChain()
	{
        return $this->paramChain;
    }

    /**
	* news (request)
    */
    public function setMainRouteRequest($mainRouteRequest)
    {
        $this->mainRouteRequest = $mainRouteRequest;
    }

    public function getMainRouteRequest()
    {
        return $this->mainRouteRequest;
    }

    /**
	* article (request)
    */
    public function setSubRouteRequest($subRouteRequest)
    {
        $this->subRouteRequest = $subRouteRequest;
    }

    public function getSubRouteRequest()
    {
        return $this->subRouteRequest;
    }

    /**
	* news
    */
    public function setMainRoute($mainRoute)
    {
        $this->mainRoute = $mainRoute;
    }

    public function getMainRoute()
    {
        return $this->mainRoute;
    }

    /**
	* article
    */
    public function setSubRoute($subRoute)
    {
        $this->subRoute = $subRoute;
    }

    public function getSubRoute()
    {
        return $this->subRoute;
    }


    /**
	* Route object (Ajax valasz is meg tudja mondani az oldal Route-jat)
    */
    public function setPageRoute($pageRoute)
    {
        $this->pageRoute = $pageRoute;
    }

    public function getPageRoute()
    {
        return $this->pageRoute;
    }

    public function setDetails($details)
    {
        $this->details = $details;
    }

    public function getDetails()
    {
        return $this->details;
    }
}
