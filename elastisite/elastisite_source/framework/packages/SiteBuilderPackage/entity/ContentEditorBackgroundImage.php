<?php
namespace framework\packages\SiteBuilderPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\ToolPackage\entity\ImageHeader;

class ContentEditorBackgroundImage extends DbEntity
{
    const GALLERY_NAME = 'contentEditorBackgroundImage';
	const KEYWORD_VARIETY_INCLUDING = 1;
	const KEYWORD_VARIETY_EXCLUDING = 2;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `content_editor_background_image` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `content_editor_id` int(11) DEFAULT NULL,
        `image_header_id` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=81000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
    protected $contentEditor;
    protected $imageHeader;

	public function __construct()
	{
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

    public function setContentEditor(ContentEditor $contentEditor)
    {
        $this->contentEditor = $contentEditor;
    }

    public function getContentEditor()
    {
        return $this->contentEditor;
    }

    public function setImageHeader(ImageHeader $imageHeader)
    {
        $this->imageHeader = $imageHeader;
    }

    public function getImageHeader()
    {
        return $this->imageHeader;
    }
}
