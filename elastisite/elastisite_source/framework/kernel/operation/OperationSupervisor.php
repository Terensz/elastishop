<?php
namespace framework\kernel\operation;

use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\component\exception\ElastiException;
use framework\component\helper\PHPHelper;
use framework\kernel\utility\FileHandler;
use framework\kernel\operation\DirChecker;

class OperationSupervisor extends Kernel
{
    private $foundErrors = 0;
    private $databaseConnectionErrors;
    private $databaseTableErrors;
    private $missingCreateTableStatements;
    private $missingTables;
    private $createTableStatements;
    private $onCreateQueries;
    private $unwritableDynamicFiles;
    private $writablePublicDirs;
    private $writablePublicFiles;

    public function __construct()
    {

    }

    public function reset()
    {
        $this->foundErrors = 0;
        $this->databaseConnectionErrors = null;
        $this->databaseTableErrors = null;
        $this->missingCreateTableStatements = null;
        $this->missingTables = null;
        $this->createTableStatements = null;
        $this->onCreateQueries = null;
        $this->unwritableDynamicFiles = null;
        $this->writablePublicDirs = null;
        $this->writablePublicFiles = null;
    }

    public function init($forceRun = false)
    {
        if ($forceRun) {
            $this->reset();
        }
        // dump('OperationSupervisor!!!!!!!');
        //phpinfo();
        //dump($this->getContainer()->getUrl()->getElastiSiteRoot());exit;

        $this->automaticUrlRedirecting();

        if (!$this->getContainer()->isAjax() || $forceRun) {
            $this->getContainer()->wireService('framework/kernel/operation/DirChecker');
            $dynDirChecker = new DirChecker();
            $dynDirChecker->checkDynamicFilePermissions();
            $dynDirChecker->checkPublicFilePermissions();
            if ($dynDirChecker->getUnwritableDynamicFiles()) {
                $this->unwritableDynamicFiles = $dynDirChecker->getUnwritableDynamicFiles();
                $this->foundErrors++;
            }
            if ($dynDirChecker->getWritablePublicDirs() && $this->getContainer()->getEnv() != 'dev') {
                $this->writablePublicDirs = $dynDirChecker->getWritablePublicDirs();
                $this->foundErrors++;
            }
            if ($dynDirChecker->getWritablePublicFiles() && $this->getContainer()->getEnv() != 'dev') {
                $this->writablePublicFiles = $dynDirChecker->getWritablePublicFiles();
                $this->foundErrors++;
            }

            $dbm = $this->getContainer()->getKernelObject('DbManager');
            //dump($dbm->tableExists('alma'));exit;
            if ($dbm->getErrorMessage()) {
                $this->databaseConnectionErrors[] = $dbm->getErrorMessage();
                $this->foundErrors++;
            } else {
                $entityMap = $this->getContainer()->getEntityMap();
                // dump($entityMap);exit;
                foreach ($entityMap as $entityName => $entityMapElement) {
                    if ($entityMapElement['parentClassName'] == 'DbEntity') {
                        if (!isset($entityMapElement['createTableStatement']) && $entityMapElement['condition'] != 'inactive') {
                            //dump($entityName);
                            $this->missingCreateTableStatements[] = $entityName;
                            $this->foundErrors++;
                        } elseif (isset($entityMapElement['createTableStatement']) && $entityMapElement['condition'] == 'missing.table') {
                            /**
                             * Naturally, we just address a table as 'missing' when it has the CREATE statement.
                            */
                            $this->createTableStatements[$entityName] = $entityMapElement['createTableStatement'];
                            if (isset($entityMapElement['onCreateQueries'])) {
                                $this->onCreateQueries[$entityName] = $entityMapElement['onCreateQueries'];
                            }
                            $this->missingTables[] = $entityName;
                            $this->foundErrors++;
                        }
                        if (isset($entityMapElement['databaseTableErrors'])) {
                            $this->databaseTableErrors[$entityName] = $entityMapElement['databaseTableErrors'];
                            $this->foundErrors++;
                        }
                    }
                }
            }

            if ($this->foundErrors > 0) {
                // dump($this);exit;
                $this->getSession()->set('maintenanceMode', true);
            } else {
                $this->getSession()->set('maintenanceMode', null);
            }

            //dump($this);exit;
        }
    }

    public function automaticUrlRedirecting()
    {
        $from = $this->getContainer()->getConfig()->getProjectData('automaticUrlRedirectingFrom');
        $to = $this->getContainer()->getConfig()->getProjectData('automaticUrlRedirectingTo');
        $domain = $this->getContainer()->getUrl()->getFullDomain();
        $protocol = $this->getContainer()->getUrl()->getProtocol();

        if (is_array($from)) {
            foreach ($from as $fromDomain) {
                $fromDomain = trim($fromDomain);
                $protocolMarkPosition = strpos($fromDomain, '://');
                if ($protocolMarkPosition !== false) {
                    $parts = explode('://', $fromDomain);
                    $fromProtocol = $parts[0].'://';
                    $fromDomain = $parts[1];
                    // dump($this->getContainer()->getUrl()->getProtocol());
                    if (($fromDomain == $domain && $fromProtocol == $protocol) && ($fromProtocol.$fromDomain != $to)) {
                        // header('Location: '.$to);
                        PHPHelper::redirect($to, 'OperationSupervisor/automaticUrlRedirecting()');
                        // dump('fromDomain: '.$fromDomain);
                        // dump('domain: '.$domain);
                        // dump('fromProtocol: '.$fromProtocol);
                        // dump('protocol: '.$protocol);
                    }
                    // dump($protocol);
                } else {
                    // $fromProtocol = $protocol;
                    if (($fromDomain == $domain) && ($protocol.$domain != $to)) {
                        PHPHelper::redirect($to, 'OperationSupervisor/automaticUrlRedirecting()');
                    }
                }
            }
        }
    }

    public function createMissingTables()
    {
        return $this->foundErrors;
    }

    public function getFoundErrors()
    {
        return $this->foundErrors;
    }

    public function getDatabaseConnectionErrors()
    {
        return $this->databaseConnectionErrors;
    }

    public function getMissingCreateTableStatements()
    {
        return $this->missingCreateTableStatements;
    }

    public function getCreateTableStatements()
    {
        return $this->createTableStatements;
    }

    public function getOnCreateQueries()
    {
        return $this->onCreateQueries;
    }

    public function getDatabaseTableErrors()
    {
        return $this->databaseTableErrors;
    }

    public function getMissingTables()
    {
        return $this->missingTables;
    }

    public function getWritablePublicDirs()
    {
        return $this->writablePublicDirs;
    }

    public function getUnwritableDynamicFiles()
    {
        return $this->unwritableDynamicFiles;
    }

    public function getWritablePublicFiles()
    {
        return $this->writablePublicFiles;
    }
}