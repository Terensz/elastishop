<?php
namespace framework\packages\DevPackage\controller;

use framework\component\parent\PageController;

use framework\packages\UserPackage\repository\UserAccountRepository;
use framework\packages\UserPackage\repository\TestUserAccountRepository;
// use framework\packages\UserPackage\entity\Person;
use framework\packages\BasicPackage\repository\CountryRepository;
use framework\packages\UserPackage\repository\AddressRepository;

class DevFormController extends PageController
{
    /**
    * Route: [name: dev/form, paramChain: /dev/form]
    */
    public function devFormAction()
    {
        // dump($this->getSession()->get('user'));exit;
        $this->test();
        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }

    function test() {
        // Tesztek csak ugy:
        // -----------------------
        // $this->getContainer()->wireService('framework/packages/UserPackage/entity/User');
        // $this->getContainer()->wireService('framework/packages/UserPackage/entity/UserAccount');
        $this->getContainer()->wireService('framework/packages/BasicPackage/repository/CountryRepository');
        $this->getContainer()->wireService('framework/packages/UserPackage/repository/AddressRepository');
        $this->getContainer()->wireService('framework/packages/UserPackage/repository/UserAccountRepository');
        // $this->getContainer()->wireService('framework/packages/UserPackage/entity/Person');
        // $this->getContainer()->wireService('framework/packages/UserPackage/entity/Address');

        $countryRepo = new CountryRepository();
        // $c = $countryRepo->createNewEntity();
        // $c->setCode('HUN');
        // $countryRepo->store($c);
        // dump($countryRepo->findOneBy(['code' => 'HUN']));
        $country = $countryRepo->findOneBy(['conditions' => [['key' => 'code', 'value' => 'HUN']]]);
        // dump($country);exit;
        $addressRepo = new AddressRepository();
        $address = $addressRepo->createNewEntity();
        $address->setCountry($country);
        $address->setZipCode('1138');
        $address->setCity('Budapest');
        $address->setStreetName('Alma');
        $address->setStreetSuffix('utca');
        $address->setHouseNumber('38');

        $addressRepo->store($address);
        dump($address);

        // $userAccountRepo = new UserAccountRepository();
        // $u = $userAccountRepo->find(1);
        // dump($u);

        // dump($this->getContainer()->getSession()->get('visitorCode'));exit;
        // $em = $this->getEntityManager();

        // $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
        // dump($securityEventHandler->getEvents());exit;

        // $this->getContainer()->wireService('framework/packages/UserPackage/repository/PersonRepository');
        // $pr = new PersonRepository();
        // $p = $pr->findAll();
        // dump($p);

        // $userAccountRepo = new UserAccountRepository();

        // $addr = new Address();
        // $addr->setPostalAddress('Budapest, Kisjoska utca 23');
        // $person = new Person();
        // $person->addAddress($addr);
        // $person->setFullName('Kisjanos Aladar');
        // $person->setUsername('kisali');
        // $person->setEmail('csorbadzsi12@asdasd.hu');
        // $acc = $userAccountRepo->createNewEntity();
        // $acc->setPerson($person);
        // $acc->setCode('WASDASD24234');
        // $acc->setRegisteredAt($this->getCurrentTimestamp());
        // $acc->setStatus(1);
        // $userAccountRepo->store($acc);
        // dump($acc);exit;

        // $securityEventRepo = new SecurityEventRepository();
        // $a = $securityEventRepo->findAll();
        // dump($a);exit;

        // $b = $userAccountRepo->findAll();
        // dump($b);exit;

        // $this->getContainer()->wireService('framework/packages/UserPackage/repository/TestUserAccountRepository');
        // $testUserAccRepo = new TestUserAccountRepository();
        // $b = $testUserAccRepo->findAll();
        // dump($b);exit;

        // dump($this->getContainer()->getUser());exit;
        // dump($em->findBy());exit;
        // dump($this->getSession()->get('visitorCode'));
        // dump('Security');exit;

        exit;
     }
}
