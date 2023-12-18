<?php
namespace framework\packages\VisitorPackage\service;

use App;
use framework\kernel\utility\BasicUtils;
use framework\kernel\component\Kernel;
use framework\packages\VisitorPackage\entity\Referer;
use framework\packages\SeoPackage\entity\SearchedKeyword;
use framework\packages\SeoPackage\repository\SearchedKeywordRepository;

class RefererUrlService extends Kernel
{
    public $referer;
    public $transferProtocol;
    public $host;
    public $path;
    public $fullSearchString;
    public $typedSearchString;
    public $rawKeywords = array();
    public $includingKeywords = array();
    public $excludingKeywords = array();
    // public $forcedKeywords = array();

    public function init()
    {
        // $url = 'https://www.google.com/search?q=%C3%A1rv%C3%ADzt%C5%B1r%C5%91+%2B%27t%C3%BCk%C3%B6r%27+-f%C3%BAr%C3%B3g%C3%A9p&sxsrf=AOaemvLLG0dSxz8fQnMB7lr4CBdKvdE6mg%3A1640640437976&ei=tS_KYfKHO9GMkgXXrrCYAw&ved=0ahUKEwiyton09YT1AhVRhqQKHVcXDDMQ4dUDCA4&uact=5&oq=%C3%A1rv%C3%ADzt%C5%B1r%C5%91+%2B%27t%C3%BCk%C3%B6r%27+-f%C3%BAr%C3%B3g%C3%A9p&gs_lcp=Cgdnd3Mtd2l6EANKBAhBGAFKBAhGGABQ2wlY0hlghR9oAXAAeACAAUuIAaACkgEBNJgBAKABAcABAQ&sclient=gws-wiz';
        // $url = 'https://www.bing.com/search?q=%c3%a1rv%c3%adzt%c5%b1r%c5%91+t%c3%bck%c3%b6rf%c3%bar%c3%b3g%c3%a9p&qs=CT&pq=%c3%a1rv%c3%adz&sk=CT2&sc=8-5&cvid=2C2D4CECFCE843DEB35C58402809BE48&FORM=QBLH&sp=3';
        // $url = 'https://search.yahoo.com/search?p=%C3%A1rv%C3%ADzt%C5%B1r%C5%91+t%C3%BCk%C3%B6rf%C3%BAr%C3%B3g%C3%A9p&fr=yfp-t-s&ei=UTF-8&fp=1';
        // $url = 'https://search.aol.com/aol/search?q=%C3%A1rv%C3%ADzt%C5%B1r%C5%91+t%C3%BCk%C3%B6rf%C3%BAr%C3%B3g%C3%A9p&s_qt=ac&rp=&s_chn=prt_bon&s_it=comsearch';

        // if (isset($_SERVER['HTTP_REFERER']) || $url) {
        if (isset($_SERVER['HTTP_REFERER'])) {
            // $this->referer = $url;
            $this->referer = $_SERVER['HTTP_REFERER'];
            $urlParts = BasicUtils::mbParseUrl($this->referer);
            $this->transferProtocol = $urlParts['scheme'];
            $this->host = isset($urlParts['host']) ? $urlParts['host'] : null;
            $this->path = isset($urlParts['path']) ? $urlParts['path'] : null;

            if (isset($urlParts['query'])) {
                $this->processQuery($urlParts['query']);
                $this->setKeywords();
                $this->sortKeywords();
                $this->storeKeywords();
            } else {
                $this->fullSearchString = $this->referer;
            }
            $this->storeReferer();
            // dump($urlParts);
            // dump($this);exit;
        }
        // $this->saveReferer();
        // dump($urlParts);
        // dump($this);exit;
    }

    public function processQuery($query)
    {
        parse_str($query, $parsed);
        if (isset($parsed['q'])) {
            $this->fullSearchString = $parsed['q'];
            if (!isset($parsed['pq']) && isset($parsed['oq'])) { // It's a google search format. "oq": where the user stopped typing and chose from browser's suggestions.
                $this->typedSearchString = $parsed['oq'];
            } elseif (!isset($parsed['oq']) && isset($parsed['pq'])) { // It's a Bing format. "pq" is the typed search string.
                $this->typedSearchString = $parsed['pq'];
            } elseif (!isset($parsed['oq']) && !isset($parsed['pq'])) { // It's a standard format. Yahoo, AOL and some others comes here. No typed and stopped search string known.
                $this->typedSearchString = $parsed['q'];
            }
        } else {
            if (isset($parsed['i'])) { // It's a WolframAlpha format.
                $this->fullSearchString = $parsed['i'];
                $this->typedSearchString = $parsed['i'];
            } elseif (isset($parsed['text'])) { // It's a Yandex format.
                $this->fullSearchString = $parsed['text'];
                $this->typedSearchString = $parsed['text'];
            }
        }
        // dump($parsed);
    }

    public function setKeywords()
    {
        $this->fullSearchString = !$this->fullSearchString ? null : str_replace('[separator]', ' ', $this->fullSearchString);
        $this->typedSearchString = !$this->typedSearchString ? null : str_replace('[separator]', ' ', $this->typedSearchString);
        $this->typedSearchString = !$this->typedSearchString ? null : str_replace('_', ' ', $this->typedSearchString);
        if ($this->typedSearchString) {
            $rawKeywords = explode(' ', $this->typedSearchString);
            foreach ($rawKeywords as $rawKeyword) {
                if ($rawKeyword != '') {
                    $rawKeyword = trim($rawKeyword);
                    $rawKeyword = trim($rawKeyword, '"');
                    $rawKeyword = trim($rawKeyword, "'");
                    $this->rawKeywords[] = $rawKeyword;
                }
            }
        }
    }

    public function sortKeywords()
    {
        foreach ($this->rawKeywords as $keyword) {
            $firstChar = substr($keyword, 0, 1);
            if ($firstChar == '-') {
                $keyword = ltrim($keyword, '-');
                $this->excludingKeywords[] = $keyword;
            } else {
                $keyword = ltrim($keyword, '+');
                $this->includingKeywords[] = $keyword;
            }
        }
    }

    public function storeKeywords()
    {
        $this->wireService('SeoPackage/repository/SearchedKeywordRepository');
        $keywordRepo = new SearchedKeywordRepository();
        $this->wireService('SeoPackage/entity/SearchedKeyword');
        // $keyword = new Keyword();
        $this->iterateAndStoreKeywords('including', $keywordRepo);
        $this->iterateAndStoreKeywords('excluding', $keywordRepo);
    }

    public function iterateAndStoreKeywords($varietyName, $keywordRepo)
    {
        $keywords = $varietyName == 'including' ? $this->includingKeywords : $this->excludingKeywords;
        $variety = $varietyName == 'including' ? SearchedKeyword::KEYWORD_VARIETY_INCLUDING : SearchedKeyword::KEYWORD_VARIETY_EXCLUDING;

        foreach ($keywords as $name) {
            $foundKeyword = $keywordRepo->findOneBy(['conditions' => [
                ['key' => 'website', 'value' => App::getWebsite()],
                ['key' => 'name', 'value' => $name],
                ['key' => 'variety', 'value' => $variety],
                ['key' => 'search_string', 'value' => $this->fullSearchString]
            ]]);
            $keyword = $foundKeyword ? : new SearchedKeyword();
            $keyword->setRouteName($this->getRouting()->getPageRoute()->getName());
            $keyword->setName($name);
            $keyword->setSearchString($this->fullSearchString);
            $keyword->setVariety($variety);
            $keyword->setQuantity($keyword->getQuantity() + 1);
            $keywordRepo->store($keyword);
        }
    }

    public function storeReferer()
    {
        if ($this->referer) {
            $routeParamChain = $this->getContainer()->getFailedRoute() ? $this->getContainer()->getFailedRoute().' ('.$this->getContainer()->getUrl()->getPageRoute()->getParamChain().')' : $this->getContainer()->getUrl()->getPageRoute()->getParamChain();
            // dump($this->getContainer()->getUrl());exit;
            $this->wireService('VisitorPackage/entity/Referer');
            $referer = new Referer();
            $referer->setEsRouteParamChain($routeParamChain);
            $referer->setTransferProtocol($this->transferProtocol);
            $referer->setHost($this->host);
            $referer->setPath($this->path);
            $referer->setSearchString($this->fullSearchString);
            $referer->setVisitorCode($this->getContainer()->getSession()->get('visitorCode'));
            if ($this->getContainer()->getUser()->getUserAccount()->getId()) {
                $referer->setUserAccount($this->getContainer()->getUser()->getUserAccount());
            }
            $refererRepo = $referer->getRepository();
            // dump($repo);exit;
            $refererRepo->store($referer);
        }
    }
}
