<?php
namespace framework\kernel\routing;

use framework\kernel\component\Kernel;
use framework\packages\SeoPackage\service\KeywordBuilder;
use framework\packages\SeoPackage\service\DescriptionBuilder;
use framework\component\entity\Route;

class MetaCollector extends Kernel
{
	private $route;
	private $meta;

	public function __construct(Route $route)
	{
		// dump($this->getDbManager()->getConnection());
		$this->route = $route;
        $this->adjustTitle();
        $this->createKeywords();
		$this->createDescription();
		// dump($this);exit;
	}

	public function adjustTitle()
	{

    }

	public function createKeywords()
	{
		$this->getContainer()->wireService('SeoPackage/service/KeywordBuilder');
		$keywordBuilder = new KeywordBuilder($this->route);
		$keywords = $keywordBuilder->getKeywords();
		$this->meta['keywords'] = $keywords;
    }

	public function createDescription()
	{
		$this->getContainer()->wireService('SeoPackage/service/DescriptionBuilder');
		$descriptionBuilder = new DescriptionBuilder($this->route);
		$description = $descriptionBuilder->getDescription();
		$this->meta['description'] = $description;
    }

	public function get()
	{
		return $this->meta;
    }
}
