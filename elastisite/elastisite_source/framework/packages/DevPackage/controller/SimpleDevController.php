<?php
namespace framework\packages\DevPackage\controller;

use framework\component\parent\PageController;
use framework\packages\ArticlePackage\entity\Article;
use framework\packages\ToolPackage\service\Mailer;
use framework\packages\UserPackage\repository\PersonRepository;
use framework\packages\UserPackage\repository\UserAccountRepository;
use framework\packages\WebshopPackage\repository\ProductRepository;

class SimpleDevController extends PageController
{
    public function inputTestAction()
    {
        $req = $this->getContainer()->getRequest();
        return $this->renderSimplePage([
            'container' => $this->getContainer()
        ]);
    }

    /**
    * urlParamChain: /dev/noscriptTest
    */
    public function noscriptTestAction()
    {
        $req = $this->getContainer()->getRequest();
        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
        // dump($this->getContainer()->getFullRouteMap());
        // dump($this->getWidgetContents($page)); exit;

        // return $page;
    }

    /**
    * urlParamChain: /dev/db
    */
    public function devDbAction()
    {
        $this->setService('SchedulePackage/entity/Event');
        $event = $this->getService('Event');
        // dump($event);exit;
        $event->setTitle('Teszt');
        $event->setDescription('Ez olyan... á mindegy');
        $event->setStartDate('2019-09-17 19:00');
        $event->setEndDate('2019-10-01 13:00');
        dump($event->getRepository()->store($event));exit;
        return $this->renderSimplePage([
            'content' => '',
            'container' => $this->getContainer()
        ]);
        // dump($this->getContainer()->getFullRouteMap());
        // dump($this->getWidgetContents($page)); exit;

        // return $page;
    }

    public function devMailTestAction()
    {
        $this->getContainer()->wireService('framework/packages/UserPackage/repository/PersonRepository');
        $this->getContainer()->wireService('framework/packages/UserPackage/repository/UserAccountRepository');
        $accRepo = new UserAccountRepository();
        $personRepo = new PersonRepository();

        // $acc = $accRepo->find(15);
        // $acc->getPerson()->setPassword(md5('Alma1234'));
        // $accRepo->store($acc);
        // dump($acc);exit;
        // dump($this->decrypt('x+fd7R9L2LjSHz3Nboh5zw=='));
        // dump($this->decrypt('HvXAY2nCt7qQKrrPZvH/jYQVEn01SMuc+nkgef34Z6JLvZ+y1lM24vsIfp5aDLKn'));exit;

        // if ($this->getContainer()->getUser()->getEmail()) {
        //     $email = $this->getContainer()->getUser()->getEmail();
        // } else {
        //     $email = $this->getContainer()->getRequest()->get('UserPackage_forgottenPassword_email');
        // }
        // $email = $this->encrypt($email);
        // $person = $personRepo->findOneBy(['email' => $email]);
        // dump($person);exit;

        $this->wireService('ToolPackage/service/Mailer');
        $mailer = new Mailer();
        $mailer->setSubject('Alma');
        $mailer->setBody('Alma');
        $mailer->addRecipient('terencecleric@gmail.com', 'Terensz TESZT');
        // $mailer->addRecipient('papp.ferenc39@upcmail.hu', 'Terensz TESZT');
        $mailer->send();
        dump($mailer);exit;
    }

    public function devOrmTestAction()
    {
        $this->getContainer()->wireService('framework/packages/WebshopPackage/repository/ProductRepository');
        // $this->getContainer()->wireService('framework/packages/UserPackage/repository/UserAccountRepository');
        $repo = new ProductRepository();
        $prod5 = $repo->find(5);
        // $prod5->setProductCategory(null);
        // $repo->store($prod5);
        dump($prod5);
    }
}
