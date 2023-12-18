<?php
namespace framework\packages\UserPackage\form;

use framework\component\parent\CustomFormValidator;

/**
 * @var bool ruleValue: Desired return
*/
class UserRegistrationCustomValidator extends CustomFormValidator
{
    public function minNameLength($value, bool $ruleValue, $form)
    {
        // getValue($requestKey, '')
        if (strlen($form->getValueCollector()->getPosted('name')) < 6) {
            return [
                'result' => false,
                'message' => trans('min.character.length.6')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function usernameFormat($value, bool $ruleValue, $form)
    {
        $username = $form->getValueCollector()->getPosted('username');
        $match = preg_match('/[^a-zA-Z0-9]/', $username);
        if ($match == true) {
            return [
                'result' => false,
                'message' => trans('username.format.only.alfanumeric')
            ];
        }
        elseif (strlen($username) < 4) {
            return [
                'result' => false,
                'message' => trans('min.character.length.4')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function checkHungarianZip($value, bool $ruleValue, $form)
    {
        $country = $form->getValueCollector()->getDisplayed('country');
        
        if ($country == 348) {
            if (!is_numeric($value) || strlen($value) != 4) {
                return [
                    'result' => false,
                    'message' => trans('hungarian.zip.must.stand.of.4.numbers')
                ];
            }
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function uniqueEmail($value, $ruleValue, $form)
    {
        if ($form->getValueCollector()->getStored('email') == $form->getValueCollector()->getPosted('email')) {
            return [
                'result' => true,
                'message' => null
            ];
        }

        $this->setService('UserPackage/repository/PersonRepository');
        $repo = $this->getService('PersonRepository');

        if ($repo->checkUniqueEmail($form->getValueCollector()->getPosted('email'))) {
            $repo->reserveEmail($form->getValueCollector()->getPosted('email'));
            return [
                'result' => true,
                'message' => null
            ];
        }
        else {
            return [
                'result' => false,
                'message' => trans('email.reserved')
            ];
        }
    }

    public function checkUsernameAvailability($value, bool $ruleValue, $form)
    {
        if ($form->getValueCollector()->getStored('username') == $form->getValueCollector()->getPosted('username')) {
            return [
                'result' => true,
                'message' => null
            ];
        }

        $this->setService('UserPackage/repository/PersonRepository');
        $repo = $this->getService('PersonRepository');

        if ($repo->checkUsernameAvailability($form->getValueCollector()->getPosted('username'))) {
            $repo->reserveUsername($form->getValueCollector()->getPosted('username'));
            return [
                'result' => true,
                'message' => null
            ];
        }
        else {
            return [
                'result' => false,
                'message' => trans('username.reserved')
            ];
        }
    }

    public function minPasswordLength($value, bool $ruleValue, $form)
    {
        $passwordPost = $form->getValueCollector()->getPosted('password');
        if (!$passwordPost || strlen($passwordPost) == 0) {
            return [
                'result' => true,
                'message' => null
            ];
        }
        if ($passwordPost && strlen($passwordPost) < 8) {
            return [
                'result' => false,
                'message' => trans('min.character.length.8')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function mixed($value, bool $ruleValue, $form)
    {
        $passwordPost = $form->getValueCollector()->getPosted('password');
        if (!$passwordPost || strlen($passwordPost) == 0) {
            return [
                'result' => true,
                'message' => null
            ];
        }

        $result = true;
        if(!preg_match('/[A-Z]/', $passwordPost)){
            $result = false;
        }
        if(!preg_match('/[a-z]/', $passwordPost)){
            $result = false;
        }
        if(!preg_match('/[0-9]/', $passwordPost)){
            $result = false;
        }
        if (!$result) {
            return [
                'result' => false,
                'message' => trans('must.contain.lower.upper.digit')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function passwordIsNotTooStrong($value, bool $ruleValue, $form)
    {
        $passwordPost = $form->getValueCollector()->getPosted('password');
        if (in_array(mb_strtolower($passwordPost), ['chucknorris', 'chuck_norris', 'chuck norris', 'chuck-norris'])) {
            return [
                'result' => false,
                'message' => trans('password.is.too.strong')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function compareRetypedPassword($value, bool $ruleValue, $form)
    {
        $password = $form->getValueCollector()->getPosted('password');
        if ($password == '') {
            return [
                'result' => false,
                'message' => trans('password.must.not.be.empty')
            ];
        }
        if ($value == $password) {
            return [
                'result' => true,
                'message' => null
            ];
        }
        else {
            return [
                'result' => false,
                'message' => trans('password.and.retyped.must.match')
            ];
        }
    }

    public function validateEmail($value, bool $ruleValue, $form)
    {
        if (!$value) {
            return [
                'result' => true,
                'message' => null
            ];
        }
        
        $temp1 = explode('@', $value);

        if (count($temp1) != 2) {
            return [
                'result' => false,
                'message' => trans('invalid.email')
            ];
        }

        $temp2 = explode('.', $temp1[1]);

        if (count($temp2) < 2) {
            return [
                'result' => false,
                'message' => trans('invalid.email')
            ];
        }
        else {
            return [
                'result' => true,
                'message' => null
            ];
        }
    }
}
