<?php
namespace framework\packages\FrameworkPackage\service;

use App;
use framework\component\parent\Service;

class SettingsService extends Service
{
    public function get($param)
    {
        $this->setService('FrameworkPackage/repository/SettingRepository');
        $repo = $this->getService('SettingRepository');
        // $setting = $repo->findOneBy(['param' => $param]);
        $setting = $repo->findOneBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'param', 'value' => $param]
        ]]);
        // dump($setting);

        return $setting ? $setting->getValue() : null;
    }

    public function set($param, $value)
    {
        $this->setService('FrameworkPackage/repository/SettingRepository');
        $repo = $this->getService('SettingRepository');

        $value = $this->convertValueToText($value);
        // $setting = $repo->findOneBy(['param' => $param]);
        $existingSetting = $repo->findOneBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'param', 'value' => $param]
        ]]);
        
        if ($existingSetting) {
            $existingSetting->setValue($value);
            $repo->store($existingSetting);
        } else {
            $setting = $repo->createNewEntity();
            $setting->setParam($param);
            $setting->setValue($value);
            $repo->store($setting);
        }
    }

    public function convertValueFromText($value)
    {
        if ($value === 'true') {
            $value = true;
        }
        if ($value === 'false') {
            $value = false;
        }
        if ($value === 'null') {
            $value = null;
        }

        return $value;
    }

    public function convertValueToText($value)
    {
        if ($value === true) {
            $value = 'true';
        }
        if ($value === false) {
            $value = 'false';
        }
        if ($value === null) {
            $value = 'null';
        }

        return $value;
    }

    public function processPosts($postsNotToHandle = [])
    {
        $posts = $this->getRequest()->getAll();
        // var_dump($posts);exit;
        foreach ($posts as $param => $value) {
            // $settinParts = 
            if (!in_array($param, $postsNotToHandle)) {
                $this->set($param, $value);
            }
        }
    }
}
