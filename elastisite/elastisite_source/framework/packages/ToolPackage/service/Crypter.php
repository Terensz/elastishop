<?php
namespace framework\packages\ToolPackage\service;

use framework\component\parent\Service;

class Crypter extends Service
{
    private $secretKey;
    private $cipherMethod = 'aes-192-cbc';
    private $initVector = 'almaalmaalmaalma';

    public function __construct()
    {
        // $this->secretKey = substr(md5($this->getUrl()->getFullDomain()), -16);
        $this->secretKey = '89n327jkfewGH53453Ksfsd61BNsrBwe';
    }

    public function encrypt($textToEncrypt)
    {
        if (!$textToEncrypt || !$this->cipherMethod || !$this->secretKey) {
            return $textToEncrypt;
        }
        // dump($textToEncrypt);
        try {
            // $initVectorLength = openssl_cipher_iv_length($this->cipherMethod);
            // $initVector = openssl_random_pseudo_bytes($initVectorLength);
            // dump($initVector);exit;
            $encrypted = openssl_encrypt($textToEncrypt, $this->cipherMethod, $this->secretKey, 0, $this->initVector);
            return !$encrypted ? $textToEncrypt : $encrypted;
        } catch (\Exception $e) {
            dump($e);exit;
        }
        
        return $textToEncrypt;
    }

    public function decrypt($encrypted, $oldMethod = false, $debug = false)
    {
        if (!$encrypted || !$this->cipherMethod || !$this->secretKey) {
            return $encrypted;
        }
        if ($oldMethod) {
            $decrypted = openssl_decrypt($encrypted, $this->cipherMethod, $this->secretKey);
        } else {
            $decrypted = openssl_decrypt($encrypted, $this->cipherMethod, $this->secretKey, 0, $this->initVector);
        }
        // if ($debug) {
        //     dump('Encrypted: ');
        //     dump($encrypted);
        //     dump('Decrypted: ');
        //     dump($decrypted);
        // }
        // $decrypted = openssl_decrypt($encrypted, $this->cipherMethod, $this->secretKey);
        
        return !$decrypted ? $encrypted : $decrypted;
    }

    public function isEncrypted($encrypted)
    {
        // $decrypted = openssl_decrypt($encrypted, $this->cipherMethod, $this->secretKey, 0, $this->initVector);
        $decrypted = openssl_decrypt($encrypted, $this->cipherMethod, $this->secretKey);
        return !$decrypted ? false : true;
    }

    public function dynamicEncrypt()
    {

    }

    public function dynamicDecrypt()
    {

    }
}
