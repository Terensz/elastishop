<?php
namespace framework\component\helper;

class MathHelper
{
    public static function isWholeNumber($data)
    {
        return (floor($data) == $data);
    }
}
