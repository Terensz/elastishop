<?php
namespace framework\component\exception;

use framework\kernel\utility\BasicUtils;
use framework\kernel\exception\entity\ExceptionTrace;
use framework\kernel\exception\entity\ExceptionTraceArg;
use framework\kernel\base\Reflector;

class ElastiException extends \Exception
{
    const ERROR_TYPE_SECRET_PROG = 101;
    const ERROR_TYPE_PUBLIC_PROG = 102;
    const ERROR_TYPE_SECRET_USER = 201;
    const ERROR_TYPE_PUBLIC_USER = 202;

    const COMPETENCE_WEBSITE_DEVELOPER = 1;
    const COMPETENCE_WEBSITE_OPERATOR = 2;
    const COMPETENCE_WEBSITE_USER = 3;

    public $codes = array(
        1401 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'Az ElastiSite-gyökérmappa/source/config/basePathConfig.txt valamelyik sorában 2 db egyenlőségjel van, így nem tudja a config-feldolgozó, hogy melyik a kulcs és melyik az érték a három rész közül',
                    'en' => 'In the ElastiSite-root/source/config/basePathConfig.txt there is two pieces of equals sign in one of the lines, therefore the config processor cannot decide, which one is the key and which one is the value of the three parts'
                ),
                'prod' => array(
                    'hu' => 'IT-Üzemeltetési hiba',
                    'en' => 'IT Operating error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_OPERATOR
        ),
        1410 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'A session-tároló mappa nem írható: [sessionSavePath]',
                    'en' => 'Unable to write session storage directory: [sessionSavePath]'
                ),
                'prod' => array(
                    'hu' => 'IT-Üzemeltetési hiba',
                    'en' => 'IT Operating error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_OPERATOR
        ),
        1411 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'Nem olvasható file: [filePath]',
                    'en' => 'Unable to read file: [filePath]'
                ),
                'prod' => array(
                    'hu' => 'IT-Üzemeltetési hiba',
                    'en' => 'IT Operating error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_OPERATOR
        ),
        1412 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'A file nem hozható létre: [filePath]',
                    'en' => 'Unable to create file: [filePath]'
                ),
                'prod' => array(
                    'hu' => 'IT-Üzemeltetési hiba',
                    'en' => 'IT Operating error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_OPERATOR
        ),
        1413 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'Nem írható file: [filePath]',
                    'en' => 'Unable to overwrite file: [filePath]'
                ),
                'prod' => array(
                    'hu' => 'IT-Üzemeltetési hiba',
                    'en' => 'IT Operating error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_OPERATOR
        ),
        1414 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'Nem bővíthető file: [filePath]',
                    'en' => 'Unable to append file: [filePath]'
                ),
                'prod' => array(
                    'hu' => 'IT-Üzemeltetési hiba',
                    'en' => 'IT Operating error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_OPERATOR
        ),
        1415 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'A file létrehozásához nincs a webservernek jogosultsága: [filePath]',
                    'en' => 'Not enough permission to create file: [filePath]'
                ),
                'prod' => array(
                    'hu' => 'IT-Üzemeltetési hiba',
                    'en' => 'IT Operating error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_OPERATOR
        ),
        1601 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'Ez a widget nem szerepel a widgetMap-ben: [widgetName]',
                    'en' => 'This widget is not on widgetMap: [widgetName]'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_OPERATOR
        ),
        1611 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'Nem olvasható file: [filePath]. A configurált útvonala nem található az üzemeltetési hatáskörbe tartozók között.',
                    'en' => 'Unable to read file: [filePath]. Configured path is not among those at operators competence.'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1612 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'A file nem hozható létre: [filePath]. A configurált útvonala nem található az üzemeltetési hatáskörbe tartozók között.',
                    'en' => 'Unable to create file: [filePath]. Configured path is not among those at operators competence.'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1613 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'Nem írható file: [filePath]. A configurált útvonala nem található az üzemeltetési hatáskörbe tartozók között.',
                    'en' => 'Unable to overwrite file: [filePath]. Configured path is not among those at operators competence.'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1614 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'Nem bővíthető file: [filePath]. A configurált útvonala nem található az üzemeltetési hatáskörbe tartozók között.',
                    'en' => 'Unable to append file: [filePath]. Configured path is not among those at operators competence.'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1615 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'A file létrehozásához nincs a webservernek jogosultsága: [filePath]',
                    'en' => 'Not enough permission to create file: [filePath]'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1616 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'A filePath nem lehet üres',
                    'en' => 'FilePath cannot be empty'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1617 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'FBS repo lefutásakor nem volt includolva a file-ban található entitás',
                    'en' => 'While running a FBS repo, its entity was not included'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1620 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'Nem besorolható service link: [serviceLink]',
                    'en' => 'Unable to determine type of service link: [serviceLink]'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1621 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'Ismeretlen service: [service]',
                    'en' => 'Unknown service: [service]'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        /**
         * This error occurs when you get-call a form value from FormValueCollector, and you use only the propertyAlias,
         * but there are more requestKeys under that propertyAlias. Check the entity relations! Maybe one of them 
         * is multiple.
        */
        1630 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'Pontatlan form-érték lekérdezés. Ehhez a requesthez: [request] ennél az érték-típusnál: [valueType] több érték is szerepel. Érdemes inkább teljes requestKey alapján meghívni.',
                    'en' => 'Inaccurate get-call of form-value. For this request: [request] and this value-type: [valueType] there is more result than one. It is more suggested to get-call by full requestKey.'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        // 1631 => array(
        //     'text' => array(
        //         'dev' => array(
        //             'hu' => '',
        //             'en' => ''
        //         ),
        //         'prod' => array(
        //             'hu' => 'Programhiba',
        //             'en' => 'Coding error'
        //         ),
        //     ),
        //     'competence' => 1
        // ),
        // 1648 => array(
        //     'text' => array(
        //         'dev' => array(
        //             'hu' => '[entity] entitásnak nincs [propertyCode] propertyCode-ja.',
        //             'en' => '[entity] entity does not have [propertyCode] propertyCode.'
        //         ),
        //         'prod' => array(
        //             'hu' => 'Programhiba',
        //             'en' => 'Coding error'
        //         ),
        //     ),
        //     'competence' => 1
        // ),
        1649 => array(
            'text' => array(
                'dev' => array(
                    'hu' => '[entity] entitásnak nincs [propertyCode] propertyCode-ja.',
                    'en' => '[entity] entity does not have [propertyCode] propertyCode.'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1651 => array(
            'text' => array(
                'dev' => array(
                    'hu' => '[callingClassName] és [targetClassName] entitások relációjával probléma van. Mindkét tábla tartalmaz mező-kapcsolatot a másikhoz, és csak az egyiknek kellene.',
                    'en' => 'Bad cross-relation of [callingClassName] and [targetClassName]. Both entities contain field reference to each other, in spite of only one should do so.'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1652 => array(
            'text' => array(
                'dev' => array(
                    'hu' => '[callingClassName] és [targetClassName] entitások relációjával probléma van. Egyik tábla sem tartalmaz mező-kapcsolatot a másikhoz.',
                    'en' => 'Bad cross-relation of [callingClassName] and [targetClassName]. Both tables missing field reference to the other.'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1653 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'Nem lehet [callingClassName] szülőnek [targetClassName] gyerek-relációja "multiple", ha a szülő táblája tartalmazza a gyerek id-ját.',
                    'en' => 'Relation of [callingClassName] parent and [targetClassName] child cannot be "multiple", if the parent table contains the id of the child.'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1654 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'Ennek a szülő-entitásnak: [thisClassName] ez a gyereke: [targetClassName] nem aktív. Vagy állítsd aktívra a gyereket, vagy töröld a szülőből.',
                    'en' => 'This child: [targetClassName] of this parent: [thisClassName] is not active. You should set child to active, or delete child from parent.'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1655 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'Nem aktív entitás: [entityName].',
                    'en' => 'Not active entity: [entityName].'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1660 => array(
            'text' => array(
                'dev' => array(
                    'hu' => "PDO hiba: hiányzó tábla",
                    'en' => "PDO error: missing table"
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1661 => array(
            'text' => array(
                'dev' => array(
                    'hu' => "PDO hiba: [error]<br>Lekérdezés: [statement] <br>Query-paraméterek: [queryParams]",
                    'en' => "PDO error: [error]<br>Statement: [statement] <br>Query-params: [queryParams]"
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1671 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'addDeleteLink metódus csak akkor működik, ka a Grid osztályban már set-elted a property-ket',
                    'en' => 'addDeleteLink method only works after properties has been added to Grid class'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1672 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'Hiányzó collection parent',
                    'en' => 'Missing collection parent'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1675 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'Hiányzó jogi file-ok',
                    'en' => 'Missing legal files'
                ),
                'prod' => array(
                    'hu' => 'Programhiba',
                    'en' => 'Coding error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_DEVELOPER
        ),
        1801 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'A kliens országa tiltólistás',
                    'en' => 'Client\'s country is on the ban list'
                ),
                'prod' => array(
                    'hu' => 'Felhasználói hiba',
                    'en' => 'User error'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_USER
        ),
        1820 => array(
            'text' => array(
                'dev' => array(
                    'hu' => 'Nem létező címet választott ki a listáról',
                    'en' => 'You have chosen a non existing address from the list'
                ),
                'prod' => array(
                    'hu' => 'Nem létező címet választott ki a listáról',
                    'en' => 'You have chosen a non existing address from the list'
                ),
            ),
            'competence' => self::COMPETENCE_WEBSITE_USER
        )
    );

    private $traces;

    public function __construct($params, $code = 0, $previous = null, $customMessage = ['dev' => null, 'prod' => null]) {
        \App::get()->includeOnce('framework/kernel/base/Reflector.php');
        \App::get()->includeOnce('framework/kernel/utility/BasicUtils.php');
        \App::get()->includeOnce('framework/kernel/exception/entity/ExceptionTrace.php');
        \App::get()->includeOnce('framework/kernel/exception/entity/ExceptionTraceArg.php');

        $message = BasicUtils::arrayToString($params);

        if (!$params) {
            $params = [];
        }
        // dump($params);
        if (is_array($params)) {
            if (isset($this->codes[$code])) {
                $message = $this->codes[$code]['text'][$params['container']->getEnv()][$params['container']->getLocale()];
            } else {
                $message = $customMessage ? $customMessage[$params['container']->getEnv()] : '';
            }
            // $message = str_replace('[message]', '', $message);
            // $message = str_replace('[/message]', '', $message);
            // $message = '[message]'.$message.'[/message]';
            $message = '<span class="message-toggled">'.$message.'</span>';
            foreach ($params['placeholderParams'] as $placeholder => $paramValue) {
                $message = str_replace('['.$placeholder.']', '['.$paramValue.']', $message);
            }
        }

        for ($i = 0; $i < count($this->getTrace()); $i++) {
            $this->traces[] = $this->objectifyTrace($this->getTrace()[$i]);
        }
        // if (!is_object($previous)) {
        //     dump($code);
        //     dump($params['placeholderParams']);
        //     dump($previous);
        //     dump($this);exit;
        // }
        if (!is_object($previous)) {
            parent::__construct($message, $code);
        } else {
            parent::__construct($message, $code, $previous);
        }
        // parent::__construct($message, $code, $previous);
    }

    public function getNotice()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function getTraces()
    {
        return $this->traces;
    }

    public function __toString()
    {
        $string = $this->getNotice().($this->getCode() ? " (".$this->getCode().")" : "")."\n";
        foreach ($this->traces as $trace) {
            $string .= "File: ".$trace->getFile()."\n";
            $string .= "Line: ".$trace->getLine()."\n";
            $string .= "Function: ".$trace->getFunction()."\n";
            $string .= "Args: \n";
            foreach ($trace->getArgs() as $arg) {
                $string .= $arg->getKey().": ".$arg->getValue();
            }
        }
        return $string;
    }

    public function objectifyTrace($trace, $defaultMessage = null) {
        $returnTrace = new ExceptionTrace();
        if (isset($trace['file'])) {
            $returnTrace->setFile($this->stringify($trace['file']));
        }
        if (isset($trace['line'])) {
            $returnTrace->setLine($this->stringify($trace['line']));
        }
        if (isset($trace['function'])) {
            $returnTrace->setFunction($this->stringify($trace['function']));
        }
        if (isset($trace['args'])) {
            foreach ($trace['args'] as $argKey => $argValue) {
                $returnTrace->addArg(new ExceptionTraceArg($this->stringify($argKey), $this->stringify($argValue)));
            }
        }
        return $returnTrace;
    }

    public function stringify($data)
    {
        if (is_array($data)) {
            $data = BasicUtils::arrayToString($data);
        }
        elseif (is_object($data)) {
            $reflector = new Reflector();
            $data = $reflector->objectToString($data);
        }
        return $data;
    }
}
