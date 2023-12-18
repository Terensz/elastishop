<?php 
namespace framework\packages\FrameworkPackage\entity;

use App;
use framework\component\parent\TechnicalEntity;

class Language extends TechnicalEntity
{
    public $code;
    public $translationReference;
}