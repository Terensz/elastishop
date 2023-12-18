<?php
namespace framework\packages\SiteBuilderPackage\entity;

use framework\component\helper\StringHelper;
use framework\component\parent\DbEntity;

class ContentEditorUnitCase extends DbEntity
{
	const VERTICAL_POSITIONING_DIRECTION_TOP = 'top';

	const VERTICAL_POSITIONING_DIRECTION_BOTTOM = 'bottom';

	const VERTICAL_POSITIONING_DIRECTION_BOTH = 'both';

	const HORIZONTAL_POSITIONING_DIRECTION_LEFT = 'left';

	const HORIZONTAL_POSITIONING_DIRECTION_RIGHT = 'right';

	const HORIZONTAL_POSITIONING_DIRECTION_BOTH = 'both';

	const CLASSES = [
		[
			'class' => 'textBox-dark textBox-black-boxShadow',
			'translationReference' => 'black.obscured.textbox',
		],
		[
			'class' => 'textBox-dark textBox-black-boxShadow textBox-roundedCorners',
			'translationReference' => 'black.rounded.obscured.textbox',
		],
		[
			'class' => 'textBox-midDark textBox-black-boxShadow',
			'translationReference' => 'black.clear.textbox',
		],
		[
			'class' => 'textBox-midDark textBox-black-boxShadow textBox-roundedCorners',
			'translationReference' => 'black.rounded.clear.textbox',
		],
		[
			'class' => 'textBox-light textBox-light-boxShadow',
			'translationReference' => 'light.clear.textbox',
		],
		[
			'class' => 'textBox-light textBox-light-boxShadow textBox-roundedCorners',
			'translationReference' => 'light.rounded.clear.textbox',
		]
	];

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `content_editor_unit_case` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `content_editor_id` int(11) DEFAULT NULL,
		`sequence_number` int(5) DEFAULT NULL,
        `background_color` varchar(15) DEFAULT NULL,
		`shadow_style` varchar(250) DEFAULT NULL,
        `vertical_positioning_direction` varchar(6) DEFAULT NULL,
		`vertical_position` int(4) DEFAULT NULL,
        `horizontal_positioning_direction` varchar(5) DEFAULT NULL,
		`horizontal_position` int(4) DEFAULT NULL,
		`height` int(4) DEFAULT NULL,
		`width` int(4) DEFAULT NULL,
		`class` varchar(250) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=82500 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
	protected $contentEditor;
	protected $contentEditorUnit = [];
	protected $sequenceNumber;
	protected $backgroundColor;
	protected $shadowStyle;
	// protected $positionTop;
	protected $verticalPositioningDirection;
	protected $verticalPosition;
	protected $horizontalPositioningDirection;
	protected $horizontalPosition;
	protected $height;
	protected $width;
	protected $class;

	public function __construct()
	{
	}

	public function getContainerStyleString()
	{
		$styleString = '';
		if (!empty($this->verticalPositioningDirection) && !empty($this->verticalPosition)) {
			if ($this->verticalPositioningDirection == self::VERTICAL_POSITIONING_DIRECTION_BOTH) {
				$styleString .= '';
				// $styleString .= 'top: '.$this->verticalPosition.'px;bottom: '.$this->verticalPosition.'px;';
				// $styleString .= 'float: right; left: 50%; text-align: left;';
			} else {
				$styleString .= $this->verticalPositioningDirection.': '.$this->verticalPosition.'px;';
			}
		}
		if (!empty($this->horizontalPositioningDirection) && !empty($this->horizontalPosition)) {
			if ($this->horizontalPositioningDirection == self::HORIZONTAL_POSITIONING_DIRECTION_BOTH) {
				// $styleString .= 'left: '.$this->horizontalPosition.'px;right: '.$this->horizontalPosition.'px;';
				// $styleString .= 'float: right; left: 50%; text-align: left;';
				$styleString .= '';
			} else {
				$styleString .= $this->horizontalPositioningDirection.': '.$this->horizontalPosition.'px;';
			}
		}

		return $styleString;
	}

	public function getWrapperStyleString()
	{
		$styleString = '';
		if (!empty($this->height)) {
			$styleString .= 'height: '.$this->height.'px;';
		}
		if (!empty($this->width)) {
			$styleString .= 'width: '.$this->width.'px;';
		}

		return $styleString;
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

    public function addContentEditorUnit(ContentEditorUnit $contentEditorUnit)
    {
        $this->contentEditorUnit[] = $contentEditorUnit;
    }

    public function getContentEditorUnit() : array
    {
        return $this->contentEditorUnit;
    }

    public function getSortedContentEditorUnits()
    {
        $sorted = [];
        foreach ($this->contentEditorUnit as $contentEditorUnit) {
            $sorted[$contentEditorUnit->getSequenceNumber()] = $contentEditorUnit;
        }
        ksort($sorted);

        return $sorted;
    }

    public function setSequenceNumber($sequenceNumber)
    {
        $this->sequenceNumber = $sequenceNumber;
    }

    public function getSequenceNumber()
    {
        return $this->sequenceNumber;
    }


	public function setBackgroundColor($backgroundColor)
	{
		$this->backgroundColor = $backgroundColor;
	}

	public function getBackgroundColor()
	{
		return $this->backgroundColor;
	}

	public function setShadowStyle($shadowStyle)
	{
		$this->shadowStyle = $shadowStyle;
	}

	public function getShadowStyle()
	{
		return $this->shadowStyle;
	}

	public function setVerticalPositioningDirection($verticalPositioningDirection)
	{
		$this->verticalPositioningDirection = $verticalPositioningDirection;
	}

	public function getVerticalPositioningDirection()
	{
		return $this->verticalPositioningDirection;
	}

	public function setVerticalPosition($verticalPosition)
	{
		if ($verticalPosition == '') {
			$verticalPosition = null;
		}
		$this->verticalPosition = $verticalPosition;
	}

	public function getVerticalPosition()
	{
		return $this->verticalPosition;
	}

	// public function setPositionTop($positionTop)
	// {
	// 	if ($positionTop == '') {
	// 		$positionTop = null;
	// 	}
	// 	$this->positionTop = $positionTop;
	// }

	// public function getPositionTop()
	// {
	// 	return $this->positionTop;
	// }

	public function setHorizontalPositioningDirection($horizontalPositioningDirection)
	{
		$this->horizontalPositioningDirection = $horizontalPositioningDirection;
	}

	public function getHorizontalPositioningDirection()
	{
		return $this->horizontalPositioningDirection;
	}

	public function setHorizontalPosition($horizontalPosition)
	{
		if ($horizontalPosition == '') {
			$horizontalPosition = null;
		}
		$this->horizontalPosition = $horizontalPosition;
	}

	public function getHorizontalPosition()
	{
		return $this->horizontalPosition;
	}

	// public function setPositionRight($positionRight)
	// {
	// 	if ($positionRight == '') {
	// 		$positionRight = null;
	// 	}
	// 	$this->positionRight = $positionRight;
	// }

	// public function getPositionRight()
	// {
	// 	return $this->positionRight;
	// }

	public function setHeight($height)
	{
		if ($height == '') {
			$height = null;
		}
		$this->height = $height;
	}

	public function getHeight()
	{
		return $this->height;
	}

	public function setWidth($width)
	{
		if ($width == '') {
			$width = null;
		}
		$this->width = $width;
	}

	public function getWidth() : ? int
	{
		return $this->width;
	}

	public function setClass($class)
	{
		$this->class = $class;
	}

	public function getClass()
	{
		return $this->class;
	}
}
