<?php
namespace framework\kernel\DbManager;

use framework\kernel\component\Kernel;
use framework\component\exception\ElastiException;
use framework\component\helper\StringHelper;

// use framework\kernel\utility\BasicUtils;

class StatementAnalyzer extends Kernel
{
    private $rawStatement;
    private $analyzerStatement;
    private $innerSelectStatements;
    private $mostInnerStatement;
    // private $bracketSets = array();

    public function __construct($rawStatement)
    {
        $this->rawStatement = $rawStatement;
        $this->setAnalyzerStatement();
        $this->setInnerSelectStatements();
    }

    public function getAnalyzerStatement()
    {
        return $this->analyzerStatement;
    }

    public function setAnalyzerStatement()
    {

        $rawStatement = trim($this->rawStatement);
        $statement = '';

        $rawStatementParts = explode("\n", $rawStatement);
        foreach ($rawStatementParts as $rawStatementRow) {
            $statementRow = trim($rawStatementRow);
            $statementRow = str_replace('-- ', '--', $statementRow);
            $commentPos = strpos($statementRow, '--');
            if ($commentPos === false) {
                $statement .= ' '.$statementRow;
            } else {
                if ($commentPos != 0) {
                    $statementRowParts = explode('--', $statementRow);
                    $statement .= ' '.$statementRowParts[0];
                }
            }
        }
        // dump($rawStatementParts);
        // dump(nl2br($this->rawStatement));

        $statement = strtolower($statement);
        $statement = str_replace("\r\n", '', $statement);
        $statement = str_replace("\r", '', $statement);
        $statement = str_replace("\n", '', $statement);
        // $spacelessInnerStatementBase = str_replace(' ', '[space]', $spacelessInnerStatementBase);
        $statement = preg_replace('/\s+/', ' ', $statement);
        $statement = str_replace(' ', '[space]', $statement);
        $statement = str_replace('([space]', '(', $statement);
        $statement = str_replace('[space]', ' ', $statement);
        $statement = str_replace('( select', '(select', $statement);
        $statement = str_replace(' from(', ' from (', $statement);

        // $statement = str_replace('(', '[bracket-open]', $statement);
        // $statement = str_replace(')', '[bracket-close]', $statement);

        $this->analyzerStatement = $statement;
        // dump($statement);
    }

    public function setInnerSelectStatements()
    {
        // dump($this->analyzerStatement);
        $openingBracketPositions = StringHelper::getAllOccurrencies($this->analyzerStatement, '(');
        $closingBracketPositions = array_reverse(StringHelper::getAllOccurrencies($this->analyzerStatement, ')'));
        $innerSelectStartPositions = StringHelper::getAllOccurrencies($this->analyzerStatement, '(select');

        if (count($openingBracketPositions) != count($closingBracketPositions)) {
            /**
             * @todo Exception
            */
            return false;
        }

        // dump($openingBracketPositions);
        // dump($closingBracketPositions);
        $innerSelectStatements = [];
        for ($i = 0; $i < count($openingBracketPositions); $i++) {
            if (is_array($innerSelectStartPositions) && in_array($openingBracketPositions[$i], $innerSelectStartPositions)) {
                $openingBracketPosition = (int)$openingBracketPositions[$i] + 1;
                $closingBracketPosition = (int)$closingBracketPositions[$i];
                $length = $closingBracketPosition - $openingBracketPosition;
                // dump($openingBracketPosition);
                // dump($closingBracketPosition);
                // dump($length);
                $innerSelect = substr($this->analyzerStatement, $openingBracketPosition, $length);
                // dump($innerSelect);
                $innerSelectStatements[] = $innerSelect;
            }
        }

        // $this->innerSelectStatements = $innerSelectStatements;
        $this->innerSelectStatements = array_reverse($innerSelectStatements);
        if (!isset($this->innerSelectStatements[0])) {
            // dump($this);
            $this->mostInnerStatement = $this->analyzerStatement;
        } else {
            $this->mostInnerStatement = $this->innerSelectStatements[0];
        }
        // dump($this->innerSelectStatements);
    }

    public function getInnerSelectStatements()
    {
        return $this->innerSelectStatements;
    }

    public function getMostInnerStatement()
    {
        return $this->mostInnerStatement;
    }

    public static function pairFieldWidthParam($analyzerStatement) : array
    {
        $result = [];
        $analyzerStatement = trim($analyzerStatement);
        $analyzerStatementParts1 = explode(',', $analyzerStatement);
        foreach ($analyzerStatementParts1 as $analyzerStatementPart1) {
            $analyzerStatementParts2 = explode(' ', trim($analyzerStatementPart1));
            $field = $analyzerStatementParts2[count($analyzerStatementParts2) - 3];
            $param = $analyzerStatementParts2[count($analyzerStatementParts2) - 1];
            $result[strtolower(trim($param, ':'))] = $field;
            // $result[$param] = $field;
        }

        return $result;
    }
}