<?php
namespace framework\packages\DevPackage\controller;

use framework\component\parent\PageController;
use framework\packages\UserPackage\entity\User;
use framework\packages\UserPackage\Permission;

class UserDevController extends PageController
{
    /**
    * Route: [name: dev_users, paramChain: /dev/users]
    */
    public function devUsersAction()
    {
        // $repo = $this->getService('ArticleRepository');
        // $article = new Article();
        // $article->setTitle('Első bejegyzésem');
        // $article->setBody('Lorem ipsum');
        // $article->setCreatedAt(date('2019-02-01 18:47:00'));
        // $repo->store($article);
        // $articles = $repo->findAll();
        // dump($articles);
        // $asd = $this->getService('Crypter')->encrypt('asd');
        // dump($asd);
        // $asd2 = $this->getService('Crypter')->decrypt($asd);
        // dump($asd2);

        $usersRepo = $this->getService('FBSUserRepository');
        $usersRepo->removeAllObjects();
        $user = new User();
        $user->setName('Admin');
        $user->setUsername('admin');
        $user->setPassword(md5('alma'));
        $user->addPermissionGroup('admin');
        $usersRepo->store($user);
        //
        // $user = new User();
        // $user->setName('Papp Ferenc');
        // $user->setUsername('terence');
        // $user->setPassword(md5('alma'));
        // $user->addPermissionGroup('user');
        // $usersRepo->store($user);
exit;
        return $this->renderStructure(array(
            'container' => $this->getContainer(),
            'users' => $usersRepo->findAll()
        ));
    }
}
