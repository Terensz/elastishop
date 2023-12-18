<?php
namespace framework\packages\ToolPackage\service;

// use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
use framework\kernel\utility\FileHandler;
use framework\component\parent\Service;

class Mailer extends Service
{
    public $success;
    public $textAssembler;
    protected $smtpAuth = false;
    protected $engine;
    protected $subject;
    protected $body;
    protected $isHtml = true;
    protected $recipients = array();
    // protected $bccs = array();

	public function __construct()
	{
        $this->setService('ToolPackage/service/TextAssembler');
        $this->textAssembler = $this->getService('TextAssembler');
        $this->textAssembler->setDocumentType('email');
        // $this->textAssembler->setDisplayType('email');
        FileHandler::includeFileOnce('thirdparty/PHPMailer/Exception.php', 'source');
        FileHandler::includeFileOnce('thirdparty/PHPMailer/PHPMailer.php', 'source');
        FileHandler::includeFileOnce('thirdparty/PHPMailer/SMTP.php', 'source');
        $smtpAuth = $this->getContainer()->getConfig()->getGlobal('smtp.auth');
        $this->smtpAuth = $smtpAuth ? true : false;
        $this->engine = new PHPMailer(true);
        $this->engine->setFrom($this->getCompanyData('fromEmail'));
	}

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function addRecipient($email, $displayedName, $type = 'Address')
    {
        $this->recipients[] = [
            'type' => $type,
            'email' => $email,
            'displayedName' => $displayedName
        ];
    }

    public function addBCC($email, $displayedName)
    {
        $this->addRecipient($email, $displayedName, 'BCC');
    }

    public function prepare()
    {
        // dump($smtpAuth);
        // if ($this->smtpAuth == 'false') {
        //     $smtpAuth = false;
        // }
        // if ($smtpAuth == 'true') {
        //     $smtpAuth = true;
        // }
        $this->engine->IsSMTP();
        $this->engine->CharSet = 'UTF-8';
        $this->engine->Host = $this->getContainer()->getConfig()->getGlobal('smtp.host');
        $this->engine->Port = $this->getContainer()->getConfig()->getGlobal('smtp.port');
        $this->engine->SMTPAuth = $this->smtpAuth;
        if ($this->smtpAuth) {
            $this->engine->Username = $this->getContainer()->getConfig()->getGlobal('smtp.username');
            $this->engine->Password = $this->getContainer()->getConfig()->getGlobal('smtp.password');
        }

        $this->engine->Subject = $this->subject;

        $this->engine->Body = $this->body;
        
        if ($this->isHtml) {
            $this->engine->isHTML(true);
        }

        foreach ($this->recipients as $recipient) {
            $method = 'add'.$recipient['type'];
            $this->engine->$method($recipient['email'], $recipient['displayedName']);
        }
    }

    public function send()
    {
        try {
            $this->prepare();
            $sent = $this->engine->send();
            // dump($sent);exit;
            $this->success = true;
            return $sent !== false ? true : false;
        } catch (\Exception $e) {
            $this->success = false;
            return false;
        }
    }
}
