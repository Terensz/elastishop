<?php
namespace framework\packages\FrameworkPackage\service;

use App;

/**
 * As the class name says NEVER put any method into this class!
 * Only the most basics come here.
*/
class BasicConstants
{
    const OPTION_TRUE = [
        'rawValue' => 'true',
        'translateDisplayedValue' => true,
        'optionKey' => 'true'
    ];
    
    const OPTION_FALSE = [
        'rawValue' => 'false',
        'translateDisplayedValue' => true,
        'optionKey' => 'false'
    ];

    const OPTIONS_20_50_100 = [
        '20' => [
            'rawValue' => '20',
            'translateDisplayedValue' => false,
            'optionKey'=> '20'
        ],
        '50' => [
            'rawValue' => '50',
            'translateDisplayedValue' => false,
            'optionKey'=> '50'
        ],
        '100' => [
            'rawValue' => '100',
            'translateDisplayedValue' => false,
            'optionKey'=> '100'
        ]
    ];
}

?>