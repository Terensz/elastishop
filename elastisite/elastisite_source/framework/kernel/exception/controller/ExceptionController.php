<?php
namespace framework\kernel\exception\controller;

use framework\component\parent\PageController;
use framework\kernel\request\Session;
use framework\kernel\routing\Url;
use framework\kernel\request\SetUrlRequests;
use framework\kernel\base\Container;
use framework\component\exception\ElastiException;
use framework\kernel\exception\entity\ExceptionLog;
use framework\kernel\exception\repository\ExceptionLogRepository;
use framework\kernel\exception\entity\ExceptionTrace;
use framework\kernel\utility\BasicUtils;

class ExceptionController extends PageController
{
    const EXCEPTION_TYPE = [
        '1' => 'E_ERROR',
        '2' => 'E_WARNING',
        '4' => 'E_PARSE',
        '8' => 'E_NOTICE'
    ];

    public function getExceptionType($typeId)
    {
        if (isset(self::EXCEPTION_TYPE[$typeId])) {
            return self::EXCEPTION_TYPE[$typeId];
        } else {
            return $typeId;
        }
    }

    public function basicAction($exception)
    {
        // echo '<pre>';
        // var_dump($exception);exit;
        Container::setSelfObject();
        $container = Container::getSelfObject();
        $env = $container->getEnv();
        \App::get()->includeOnce('framework/kernel/exception/entity/ExceptionLog.php');
        $log = new ExceptionLog();
        $log->setMessage($exception->getMessage());
        $log->setCode($exception->getCode());
        $log->setFile($exception->getFile());
        $log->setLine($exception->getLine());
        // dump();
        // dump($log);exit;
        if ($this->getContainer()->getSession() && $this->getContainer()->getSession()->isInitialized()) {
            $log->setUserId($this->getContainer()->getSession()->get('userId'));
        }
        $log->setCreatedAt($this->getCurrentTimestamp());
        // dump($log);exit;
        if (method_exists($exception, 'getTraces')) {
            # ElastiException
            $log->setTraces(dump($exception->getTraces(), 'return'));
        } elseif (method_exists($exception, 'getTrace')) {
            # php exception, it means: surely program error, not operating error
            $this->getContainer()->wireService('framework/kernel/exception/entity/ExceptionTrace');
            $traceArray = $exception->getTrace();
            $exceptionTraces = array();
            foreach ($traceArray as $trace) {
                // dump($trace);
                $exceptionTrace = new ExceptionTrace();
                $exceptionTrace->setFile(isset($trace['file']) ? $trace['file'] : '');
                $exceptionTrace->setLine(isset($trace['line']) ? $trace['line'] : '');
                $exceptionTrace->setFunction(isset($trace['function']) ? $trace['function'] : '');
                $exceptionTrace->setClass(isset($trace['class']) ? $trace['class'] : '');
                $exceptionTrace->setType(isset($trace['type']) ? $trace['type'] : '');
                $exceptionTraces[] = $exceptionTrace;
            }
            $log->setTraces(null);
        } else {
            dump($exception);
            exit;
        }

        // dump($exception);exit;

        // dump($exception); exit;

        if (!$container->issetKernelObject('SetUrlRequests') && !\App::isCLICall()) {
            $container->setService('framework/kernel/request/SetUrlRequests');
            $container->setKernelObject($container->getService('SetUrlRequests'));
        }

        // dump($exception);
        // dump($log);exit;

        // dump($container->getUrl());exit;

        \App::get()->includeOnce('framework/kernel/exception/repository/ExceptionLogRepository.php');
        $repo = new ExceptionLogRepository();
        // $repo->setFilePath($this->getContainer()->getPathBase('dynamic').'/log/exception/exception_log.txt');
        // $repo->setProperties(array('id', 'message', 'code', 'userId', 'createdAt', 'traces'));
        // $repo->setEmulateAutoIncrement('id');
        try {
            $repo->store($log);
        } catch(ElastiException $e) {

        }

        $traces = [];
        if (method_exists($exception, 'getTraces')) {
            $traces = $exception->getTraces();
        }
        
        if (\App::isCLICall() == false) {
            $page = $this->renderExceptionPage($env, [
                'container' => $container,
                'exceptionMessage' => $log->getMessage(),
                'exceptionCode' => $log->getCode(),
                'exceptionFile' => $log->getFile(),
                'exceptionLine' => $log->getLine(),
                'exceptionTraces' => $traces
            ]);
            echo $page;
        } else {
            var_dump([
                'exceptionMessage' => $log->getMessage(),
                'exceptionCode' => $log->getCode(),
                'exceptionFile' => $log->getFile(),
                'exceptionLine' => $log->getLine(),
                'exceptionTraces' => $traces
            ]);
        }
    }
}
