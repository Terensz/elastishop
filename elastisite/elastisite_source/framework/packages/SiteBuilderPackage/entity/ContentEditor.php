<?php
namespace framework\packages\SiteBuilderPackage\entity;

use App;
use framework\component\parent\DbEntity;
use framework\kernel\utility\FileHandler;

class ContentEditor extends DbEntity
{
    const BOX_SHADOW_STYLES = [
        'none' => 'box-shadow: none;',
        'type1' => 'box-shadow: inset -5px -5px 30px 5px red, inset 5px 5px 30px 5px blue;',
        'type2' => 'box-shadow: inset 0 60px 80px rgba(0,0,0,0.60), inset 0 45px 26px rgba(0,0,0,0.14);',
        'type3' => 'box-shadow: inset -10px 10px 0px rgba(33, 33, 33, 1), inset -20px 20px 0px rgba(33, 33, 33, 0.7), inset -30px 30px 0px rgba(33, 33, 33, 0.4), inset -40px 40px 0px rgba(33, 33, 33, 0.1);',
        'type4' => 'box-shadow: inset 93px 71px 127px rgba(0,0,0,0.7), inset 0px -42px 10px rgba(255,255,255,0.7);',
        'type5' => 'box-shadow: inset 10px 10px 25px rgb(81,81,81),
            inset -10px 10px 25px rgb(81,81,81),
            inset -10px -10px 25px rgb(81,81,81),
            inset 10px -10px 25px rgb(81,81,81);',
        'type6' => 'box-shadow: inset 35px 27px 58px rgba(81,81,81,0.8),
            -31px 34px 71px rgba(81,81,81,0.8),
            20px 41px 74px rgba(81,81,81,0.8);'
    ];

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `content_editor` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `route_name` varchar(250) DEFAULT NULL,
        `height` int(10) DEFAULT 0,
        `box_shadow_style` varchar(250) DEFAULT NULL,
        `box_shadow_color` varchar(15) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `status` int(2) DEFAULT 1,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=80000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $website;
    protected $contentEditorUnitCase = [];
    protected $routeName;
    protected $contentEditorBackgroundImage;
    protected $height;
    protected $boxShadowStyle;
    protected $boxShadowColor;
    protected $createdAt;
    protected $status;

	public function __construct()
	{
        $this->status = 0;
        $this->height = 0;
        $this->createdAt = $this->getCurrentTimestamp();
		$this->website = App::getWebsite();
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    public function getWebsite()
    {
        return $this->website;
    }

	public function addContentEditorUnitCase(ContentEditorUnitCase $contentEditorUnitCase)
	{
		$this->contentEditorUnitCase[] = $contentEditorUnitCase;
	}

	public function getContentEditorUnitCase()
	{
		return $this->contentEditorUnitCase;
	}

    public function getSortedContentEditorUnitCases()
    {
        $sorted = [];
        // dump($this->contentEditorUnitCase);
        foreach ($this->contentEditorUnitCase as $contentEditorUnitCase) {
            $sorted[$contentEditorUnitCase->getSequenceNumber()] = $contentEditorUnitCase;
        }
        ksort($sorted);

        return $sorted;
    }

    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }

	public function setContentEditorBackgroundImage(ContentEditorBackgroundImage $plashBackgroundImage = null)
	{
		$this->contentEditorBackgroundImage = $plashBackgroundImage;
	}

	public function getContentEditorBackgroundImage() : ? ContentEditorBackgroundImage
	{
		return $this->contentEditorBackgroundImage;
	}

    public function getPathToBackgroundImage()
    {
        if ($this->contentEditorBackgroundImage) {
            $file = $this->contentEditorBackgroundImage->getImageHeader()->getFullSizeImageFile()->getFile();
            $imagePath = ltrim($file->getPath(), '/');
            $pathToFile = FileHandler::completePath($imagePath, $file->getPathBaseType()).'/'.$file->getFileName().'.'.$file->getExtension();

            return $pathToFile;
        }
    }

    public function getBackgroundImageFileName()
    {
        if ($this->contentEditorBackgroundImage) {
            $file = $this->contentEditorBackgroundImage->getImageHeader()->getFullSizeImageFile()->getFile();

            return $file->getFileName().'.'.$file->getExtension();
        }
    }

	public function setHeight($height)
	{
		$this->height = $height;
	}

	public function getHeight()
	{
		return $this->height;
	}

	public function setBoxShadowStyle($boxShadowStyle)
	{
		$this->boxShadowStyle = $boxShadowStyle;
	}

	public function getBoxShadowStyle()
	{
		return $this->boxShadowStyle;
	}

	public function setBoxShadowColor($boxShadowColor)
	{
		$this->boxShadowColor = $boxShadowColor;
	}

	public function getBoxShadowColor()
	{
		return $this->boxShadowColor;
	}

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
