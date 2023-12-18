<?php
namespace framework\packages\SiteBuilderPackage\entity;

use framework\component\helper\StringHelper;
use framework\component\parent\DbEntity;

class ContentEditorUnit extends DbEntity
{
	const TEXT_ALIGN_LEFT = 'left';

	const TEXT_ALIGN_RIGHT = 'right';

	const TEXT_ALIGN_CENTER = 'center';

	const TEXT_ALIGN_OPTIONS = [
		self::TEXT_ALIGN_LEFT => 'align.to.left',
		self::TEXT_ALIGN_RIGHT => 'align.to.right',
		self::TEXT_ALIGN_CENTER => 'align.to.center',
	];

	const TEXT_SHADOW_STYLES = [
		'none' => '',
		'GreyBrick' => 'text-shadow: 0 1px 0 #ccc,
			0 2px 0 #c9c9c9,
			0 3px 0 #bbb,
			0 4px 0 #b9b9b9,
			0 5px 0 #aaa,
			0 6px 1px rgba(0,0,0,.1),
			0 0 5px rgba(0,0,0,.1),
			0 1px 3px rgba(0,0,0,.3),
			0 3px 5px rgba(0,0,0,.2),
			0 5px 10px rgba(0,0,0,.25),
			0 10px 10px rgba(0,0,0,.2),
			0 20px 20px rgba(0,0,0,.15);',
		'Tiny' => 'text-shadow: 2px 8px 6px rgba(0,0,0,0.2),
			0px -5px 35px rgba(255,255,255,0.3);',
		'TransparentWhite' => '-webkit-background-clip: text;
			-moz-background-clip: text;
			background-clip: text;
			color: transparent;
			text-shadow: rgba(255,255,255,0.5) 0px 3px 3px;',
		'Smooth' => 'text-shadow: 2px 7px 5px rgba(0,0,0,0.3),
			0px -4px 10px rgba(255,255,255,0.3);',
		'Smoother' => 'text-shadow: 3px 4px 7px rgba(81,67,21,0.8);',
		// Ultraool! Retro.
		'RetroOne' => 'text-shadow: 3px 0px 7px rgba(81,67,21,0.8),
			-3px 0px 7px rgba(81,67,21,0.8),
			0px 4px 7px rgba(81,67,21,0.8);',
		// Cool!
		'RetroTwo' => 'text-shadow: -1px -1px 3px #020202,
			2px 2px 4px #1b1b1b;',
		// Pink
		'Pinky' => 'text-shadow: 3px 3px 20px #ff99cc,
			-2px 1px 30px #ff99cc;',
		'Juicy' => 'text-shadow: 16px 22px 11px rgba(168,158,32,0.8);',
		// Glow
		'DarkGreenGlow' => 'text-shadow: 10px 10px 25px rgb(81,67,21),
			-10px 10px 25px rgb(81,67,21),
			-10px -10px 25px rgb(81,67,21),
			10px -10px 25px rgb(81,67,21);',
		// perfect
		'Perfection' => '-webkit-text-stroke: 1px #282828;
			text-shadow: 0px 4px 4px #282828;',
		'BlurredOcean' => 'color: transparent; text-shadow: 0 0 8px #316472;',
		'LightRedGlow' => 'text-shadow: 10px 10px 25px rgb(81,67,21),
			-10px 10px 25px rgb(255,36,36),
			-10px -10px 25px rgb(255,36,36),
			10px -10px 25px rgb(255,36,36);',
		'DarkRedGlow' => 'text-shadow: 10px 10px 25px rgb(81,67,21),
			-10px 10px 25px rgb(141,27,27),
			-10px -10px 25px rgb(141,27,27),
			10px -10px 25px rgb(141,27,27);',
		'ScoobyDoo' => 'text-shadow: 4px 4px 0px #000,
			-4px 0 0px #000,
			7px 4px 0 #fff;',
		'Knockout' => '
			mix-blend-mode: multiply;
			text-shadow: 5px 4px 11px rgb(0,0,0), 0 2px 5px rgb(0,0,0);',
		'Mystic' => 'text-shadow: 20px 0px 10px rgb(0, 0, 0);',
		'Monoton' => 'opacity: 0.5;
			text-shadow: 0px -78px rgb(255, 196, 0);',
		'Bungee' => 'opacity: 0.9;
			text-shadow: -18px 18px 0 rgb(66, 45, 45);',
		'Radioactive' => '
			opacity: 0.6;
			text-shadow: -18px -18px 20px rgb(87, 255, 9);',
		'DuplicatedWhite' => 'text-shadow: -20px -108px 0px rgba(255, 255, 255, 0.445);
			letter-spacing: 1rem;',
		'Prickly' => 'text-shadow: -18px -18px 2px #777;',
		'CodyStar' => 'font-weight: bold;
			text-shadow: 1px 1px 10px rgb(16, 72, 255), 1px 1px 10px rgb(0, 195, 255);',
		'Sunshine' => 'text-shadow: 5px 5px #ffff00;
			filter: drop-shadow(-10px 10px 20px #fff000);',
		'Cartoon' => 'text-shadow: 0px -6px 0 #212121, 0px -6px 0 #212121, 0px 6px 0 #212121, 0px 6px 0 #212121, -6px 0px 0 #212121, 6px 0px 0 #212121, -6px 0px 0 #212121, 6px 0px 0 #212121, -6px -6px 0 #212121, 6px -6px 0 #212121, -6px 6px 0 #212121, 6px 6px 0 #212121, -6px 18px 0 #212121, 0px 18px 0 #212121, 6px 18px 0 #212121, 0 19px 1px rgb(0 0 0 / 10%), 0 0 6px rgb(0 0 0 / 10%), 0 6px 3px rgb(0 0 0 / 30%), 0 12px 6px rgb(0 0 0 / 20%), 0 18px 18px rgb(0 0 0 / 25%), 0 24px 24px rgb(0 0 0 / 20%), 0 36px 36px rgb(0 0 0 / 15%);',
		'Misty' => 'text-shadow: 0 13.36px 8.896px #c4b59d, 0 -2px 1px #fff;
			letter-spacing: -4px;',
		'Blurred' => '
			text-shadow: 20px 20px 40px black, 20px 20px 40px pink;
			transition: all 1s ease-out;',
		// '' => '',
		// '' => '',
		// '' => '',
		// '' => '',
	];

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `content_editor_unit` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `content_editor_unit_case_id` int(11) DEFAULT NULL,
		`sequence_number` int(5) DEFAULT NULL,
        `description` text DEFAULT NULL,
        `font` varchar(30) DEFAULT NULL,
		`font_size` int(3) DEFAULT NULL,
        `font_color` varchar(15) DEFAULT NULL,
		`font_weight` varchar(20) DEFAULT NULL,
		`text_align` varchar(10) DEFAULT NULL,
		`text_decoration` varchar(60) DEFAULT NULL,
        `text_shadow_style` varchar(250) DEFAULT NULL,
        `text_shadow_color` varchar(15) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=82000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
	protected $contentEditorUnitCase;
	protected $sequenceNumber;
	protected $description;
	protected $font;
	protected $fontSize;
	protected $fontColor;
	protected $fontWeight;
	protected $textAlign;
	protected $textDecoration;
	protected $textShadowStyle;
	protected $textShadowColor;

	public function __construct()
	{
		$this->textAlign = self::TEXT_ALIGN_LEFT;
	}

	public function getWrapperStyleString()
	{
		$styleString = '';
		if (!empty($this->textAlign)) {
			$styleString .= 'text-align: '.$this->textAlign.';';
		}
		if (!empty($this->font)) {
			$styleString .= 'font-family: '.$this->font.';';
		}
		// if (!empty($this->fontSize)) {
		// 	$styleString .= 'font-size: '.$this->fontSize.'px;';
		// }
		if (!empty($this->fontColor)) {
			$styleString .= 'color: '.StringHelper::hexToRgb($this->fontColor, 1).';';
		}
		if (!empty($this->textShadowStyle)) {
			$styleString .= isset(self::TEXT_SHADOW_STYLES[$this->textShadowStyle]) ? self::TEXT_SHADOW_STYLES[$this->textShadowStyle] : '';
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

    public function setContentEditorUnitCase(ContentEditorUnitCase $contentEditorUnitCase)
    {
        $this->contentEditorUnitCase = $contentEditorUnitCase;
    }

    public function getContentEditorUnitCase()
    {
        return $this->contentEditorUnitCase;
    }

    public function setSequenceNumber($sequenceNumber)
    {
        $this->sequenceNumber = $sequenceNumber;
    }

    public function getSequenceNumber()
    {
        return $this->sequenceNumber;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

	public function setFont($font)
	{
		$this->font = $font;
	}

	public function getFont()
	{
		return $this->font;
	}

	public function setFontSize($fontSize)
	{
		if ($fontSize === '') {
			$fontSize = null;
		}
		$this->fontSize = $fontSize;
	}

	public function getFontSize()
	{
		return $this->fontSize;
	}

	public function setFontColor($fontColor)
	{
		$this->fontColor = $fontColor;
	}

	public function getFontColor()
	{
		return $this->fontColor;
	}

	public function setFontWeight($fontWeight)
	{
		$this->fontWeight = $fontWeight;
	}

	public function getFontWeight()
	{
		return $this->fontWeight;
	}

	public function setTextAlign($textAlign)
	{
		$this->textAlign = $textAlign;
	}

	public function getTextAlign()
	{
		return $this->textAlign;
	}

	public function setTextDecoration($textDecoration)
	{
		$this->textDecoration = $textDecoration;
	}

	public function getTextDecoration()
	{
		return $this->textDecoration;
	}

	public function setTextShadowStyle($textShadowStyle)
	{
		$this->textShadowStyle = $textShadowStyle;
	}

	public function getTextShadowStyle()
	{
		return $this->textShadowStyle;
	}

	public function setTextShadowColor($textShadowColor)
	{
		$this->textShadowColor = $textShadowColor;
	}

	public function getTextShadowColor()
	{
		return $this->textShadowColor;
	}
}
