<?php
namespace framework\packages\WebshopPackage\entity;

use App;
use framework\component\parent\DbEntity;

class CartTrigger extends DbEntity
{
    const DIRECTION_OF_CHANGE_APPLY = 'Apply';
    const DIRECTION_OF_CHANGE_DISCARD = 'Discard';
    const PERMITTED_DIRECTIONS_OF_CHANGE = [self::DIRECTION_OF_CHANGE_APPLY, self::DIRECTION_OF_CHANGE_DISCARD];

    const EFFECT_CAUSING_STUFF_COUNTRY_ALPHA2 = 'CountryAlpha2';
    const EFFECT_CAUSING_STUFF_ZIP_CODE_MASK = 'ZipCodeMask';
    const EFFECT_CAUSING_STUFF_GROSS_TOTAL_PRICE = 'GrossTotalPrice';
    const EFFECT_CAUSING_STUFF_AUTOMATIC = 'Automatic';
    const PERMITTED_EFFECT_CAUSING_STUFFS = [self::EFFECT_CAUSING_STUFF_COUNTRY_ALPHA2, self::EFFECT_CAUSING_STUFF_ZIP_CODE_MASK, self::EFFECT_CAUSING_STUFF_GROSS_TOTAL_PRICE, self::EFFECT_CAUSING_STUFF_AUTOMATIC];

    const EFFECT_OPERATOR_EQUALS = 'Equals';
    const EFFECT_OPERATOR_NOT_EQUALS = 'NotEquals';
    const EFFECT_OPERATOR_LESS_THAN = 'LessThan';
    const EFFECT_OPERATOR_MORE_THAN = 'MoreThan';
    const PERMITTED_EFFECT_OPERATORS = [self::EFFECT_OPERATOR_EQUALS, self::EFFECT_OPERATOR_NOT_EQUALS, self::EFFECT_OPERATOR_LESS_THAN, self::EFFECT_OPERATOR_MORE_THAN];

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `cart_trigger` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `name` varchar(250) DEFAULT NULL,
        `product_id` int(11) DEFAULT NULL,
        `direction_of_change` varchar(100) DEFAULT NULL,
        `effect_causing_stuff` varchar(100) DEFAULT NULL,
        `effect_causing_value` varchar(250) DEFAULT NULL,
        `effect_operator` varchar(100) DEFAULT NULL,
        `status` smallint(2) DEFAULT 0,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=270000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $website;
    protected $name;

    /**
     * The trigger will cause this product to get applied or dismissed on the cart.
    */
    protected $product;

    /**
     * Describes if the trigger will apply or discard the product to the cart.
    */
    protected $directionOfChange;

    /**
     * The change can be caused by a value of a zipcode, or a gross total payable amount. 
     * E.g., the delivery fee will be dismissed if the zip code changes to a certain value, or the gross total payable reaches a certain sum.
    */
    protected $effectCausingStuff;

    /**
     * Describes what is the value the causing stuff has to take, in order to apply the effect.
     * E.g., the delivery fee will be dismissed if the zip code changes to "113*" (Budapest, 13rd district.), or the gross total payable reaches 6.000 HUF.
    */
    protected $effectCausingValue;

    /**
     * Describes if the trigger applies when the value is set to, or the value differs from.
    */
    protected $effectOperator;

    protected $status;

    public function __construct()
    {
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

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setDirectionOfChange($directionOfChange)
    {
        $this->directionOfChange = $directionOfChange;
    }

    public function getDirectionOfChange()
    {
        return $this->directionOfChange;
    }

    public function setEffectCausingStuff($effectCausingStuff)
    {
        $this->effectCausingStuff = $effectCausingStuff;
    }

    public function getEffectCausingStuff()
    {
        return $this->effectCausingStuff;
    }

    public function setEffectCausingValue($effectCausingValue)
    {
        $this->effectCausingValue = $effectCausingValue;
    }

    public function getEffectCausingValue()
    {
        return $this->effectCausingValue;
    }

    public function setEffectOperator($effectOperator)
    {
        $this->effectOperator = $effectOperator;
    }

    public function getEffectOperator()
    {
        return $this->effectOperator;
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
