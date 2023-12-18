<?php
namespace framework\packages\NewsletterPackage\service;

use framework\kernel\utility\BasicUtils;
use framework\component\parent\Service;
use framework\kernel\utility\FileHandler;
use framework\packages\UserPackage\repository\UserAccountRepository;
use framework\packages\UserPackage\entity\TemporaryAccount;

class NewsletterService extends Service
{
    public function getNewsletterStatuses()
    {
        return [
            '0' => trans('disabled'),
            '1' => trans('active'),
        ];
    }

    public function getNewsletterCampaignStatuses()
    {
        return [
            '0' => trans('disabled'),
            '1' => trans('active'),
        ];
    }

    public function getNewsletterDispatchProcessStatuses()
    {
        return [
            '0' => trans('disabled'),
            '1' => trans('active'),
            '2' => trans('paused'),
            '3' => trans('created')
        ];
    }

    public function getNewsletterDispatchProcessEditStatuses()
    {
        return [
            '0' => trans('disabled'),
            '1' => trans('active'),
            '2' => trans('paused')
        ];
    }

    public function getNewsletterDispatchProcessNewStatuses()
    {
        return [
            '3' => trans('created')
        ];
    }

    public function getNewsletterDispatchProcessModes()
    {
        return [
            '1' => trans('test'),
            '2' => trans('production'),
        ];
    }

    public function getNewsletterDispatchProcessMode($mode)
    {
        return (int)$mode == 1 ? ['1' => trans('test')] : ['2' => trans('production')];
    }
}