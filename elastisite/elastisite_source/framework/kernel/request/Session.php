<?php
namespace framework\kernel\request;

use framework\kernel\component\Kernel;
use framework\packages\UserPackage\entity\User;
use framework\component\exception\ElastiException;
use framework\kernel\exception\controller\ExceptionController;

class Session extends Kernel
{
    private $websiteUniqueSessionSalt;
    private $serverUniqueSessionSalt;
    private $initialized;
    // private $messages = [];
    // private $locale;

    public function __construct()
    {
        // dump('__construct Session');
        // $this->isSessionSavePathWritable();
        if (!isset($_SESSION)) {
            $_SESSION = array();
        }
        if (!$this->get('messages')) {
            $this->set('messages', []);
        }

        // $this->readMessage('cartUpdated');

        $this->setUniqueSessionSalt();
        // $this->startSession();
        // $this->adjustSessionCookie(); // Ez inkabb a UserFactory-bol lesz meghivva
        $this->handleForeignSessions();
        $this->initLocale();
        $this->initialized = true;
    }

    public function addMessage(string $key, $body, $title = null)
    {
        $messages = $this->get('messages');
        $messages[$key] = [
            'addedAt' => $this->getCurrentTimestamp(),
            'title' => $title,
            'body' => $body,
        ];
        $this->set('messages', $messages);
    }

    public function readMessage($key)
    {
        $messages = $this->get('messages');
        if (isset($messages[$key])) {
            $return = $messages[$key];
            unset($messages[$key]);
            $this->set('messages', $messages);
            return $return;
        }
    }

    public function getMessages()
    {
        return $this->get('messages');
    }

    public function isInitialized()
    {
        return $this->initialized;
    }

    public function getVisitorCode()
    {
        return $this->get('visitorCode');
    }

    public function logout()
    {
        $visitorCode = $this->get('visitorCode');
        $this->getContainer()->wireService('UserPackage/entity/User');
        $this->removeAll(); # Remove all, except visitor code.
        $this->initLocale();
        // $this->set('userId', null);
        // $this->set('user', null);
        // $this->set('userStorageType', null);
        $this->getContainer()->setUser(new User);
        $this->getContainer()->getUser()->addPermissionGroup('guest');
    }

    // public function isSessionSavePathWritable()
    // {
    //     if (!is_writable(session_save_path())) {
    //         throw new ElastiException(
    //             'Session path is not writable: '.session_save_path(),
    //             ElastiException::ERROR_TYPE_SECRET_PROG
    //         );
    //     }
    // }

    public function getAll()
    {
        // dump('sadas');
        return $_SESSION;
        // exit;
    }

    public function createCsrfToken($widgetId)
    {
        return md5($widgetId.date('Y-m-d H:I:s').$this->serverUniqueSessionSalt);
    }

    public function userLoggedIn()
    {
        return ($this->get('userId') && $this->get('userId') > 0) ? $this->get('userId') : false;
    }

    /**
	* sessionSalt: kiegeszites a session-nev melle, hogy unique legyen projectenkent.
    */
    public function setUniqueSessionSalt()
    {
        // dump($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);exit;
        if (\App::isCLICall() == false) {
            $this->serverUniqueSessionSalt = substr(md5($_SERVER['HTTP_HOST']), -5);
        }
    }

    public function handleForeignSessions()
    {
        foreach ($_SESSION as $key => $value) {
            $validPartPos = strpos($key, $this->getGlobal('server.webProjectName').'Session-');
            if ($validPartPos === false || $validPartPos !== 0) {
                unset($key);
                // throw new ElastiException('Foreign session found: '.$key, ElastiException::ERROR_TYPE_PUBLIC_USER);
            }
        }
    }

    /**
	* Van-e ilyen nevu session?
    */
    public function has($name)
    {
        if (isset($_SESSION[$this->getGlobal('server.webProjectName').'Session-'.$this->serverUniqueSessionSalt.'-'.$name])) {
            return true;
        } else {
            return false;
        }
    }

    public function get($name)
    {
        if ($name == 'id') {
            return session_id();
        } else {
            if (isset($_SESSION[$this->getGlobal('server.webProjectName').'Session-'.$this->serverUniqueSessionSalt.'-'.$name])) {
                return $_SESSION[$this->getGlobal('server.webProjectName').'Session-'.$this->serverUniqueSessionSalt.'-'.$name];
            } else {
                return null;
            }
        }
    }

    public function getKey($name)
    {
        if ($name == 'id') {
            return session_id();
        } else {
            if (isset($_SESSION[$this->getGlobal('server.webProjectName').'Session-'.$this->serverUniqueSessionSalt.'-'.$name])) {
                return $this->getGlobal('server.webProjectName').'Session-'.$this->serverUniqueSessionSalt.'-'.$name;
            } else {
                return null;
            }
        }
    }

    public function set($name, $value)
    {
        if ($name == 'id') {
            $this->reGenerateSessionID($name);
        } else {
            $_SESSION[$this->getGlobal('server.webProjectName').'Session-'.$this->serverUniqueSessionSalt.'-'.$name] = $value;
        }
    }

    public function unset($name)
    {
        if (isset($_SESSION[$this->getGlobal('server.webProjectName').'Session-'.$this->serverUniqueSessionSalt.'-'.$name])) {
            unset($_SESSION[$this->getGlobal('server.webProjectName').'Session-'.$this->serverUniqueSessionSalt.'-'.$name]);
        } else {
            return null;
        }
    }

    public function adjustSessionCookie() {
        if (isset($_COOKIE['PHPSESSID'])) {
            // $cookie = getLocale 
            $value = $_COOKIE['PHPSESSID'];
            // unset($_COOKIE['PHPSESSID']);
            // dump($this->getUrl());
            setcookie('PHPSESSID', $value, time() + 3600, "/", true, false , true);
            // dump($alma);
            // dump($_COOKIE);exit;
        }
    }

    // public function startSession() {
    //     if (session_status() != PHP_SESSION_ACTIVE) {
    //         session_start();
    //     }
    //     if (!empty($_SESSION['deleted_time']) && $_SESSION['deleted_time'] < time() - 180) {
    //         session_destroy();
    //         session_start();
    //     }
    // }

    public function reGenerateSessionID($unsaltedSessionIDPart)
    {
        $newSessionID = $this->getGlobal('server.webProjectName').'Session-'.$this->serverUniqueSessionSalt.'-'.$unsaltedSessionIDPart.'-'.time();
        $_SESSION['deleted_time'] = time();
        session_commit();
        ini_set('session.use_strict_mode', 0);
        session_id($newSessionID);
        ini_set('session.use_strict_mode', 1);
        session_start();
        unset($_SESSION['deleted_time']);
    }

    public function remove($name)
    {
        foreach ($_SESSION as $key => $value) {
            if ($key == $this->getGlobal('server.webProjectName').'Session-'.$this->serverUniqueSessionSalt.'-'.$name) {
                unset($_SESSION[$key]);
            }
        }
    }

    public function removeAll()
    {
        $visitorCodeKey = $this->getKey('visitorCode');
        $maintenanceModeKey = $this->getKey('maintenanceMode');
        foreach ($_SESSION as $key => $value) {
            if (!in_array($key, array($visitorCodeKey, $maintenanceModeKey))) {
                unset($_SESSION[$key]);
            }
        }
    }

    public function getLocale()
    {
        return $this->get('locale');
    }

    public function setLocale($locale)
    {
        $this->set('locale', $locale);
        // $this->getContainer()->setDefaultLocale(null);
    }

    public function initLocale()
    {
        // dump($this->getContainer()->getUrl()->getMainRouteRequest());exit;
        // // dump($this->getContainer()->getRouting()->getPageRoute()->getName());exit;
        // $routeName = $this->getContainer()->getRouting()->getPageRoute()->getName();
        // $routeNameParts = explode('_', $routeName);
        $locale = $this->getContainer()->getUrl()->getMainRouteRequest() == 'admin' ? $this->getContainer()->getConfig()->getGlobal('website.adminLocale') : $this->getContainer()->getDefaultLocale();
        // dump($locale);exit;
        $this->set('locale', $locale);
        // if (!$this->get('locale')) {
        //     $this->set('locale', 'en');
        // }
    }
}
