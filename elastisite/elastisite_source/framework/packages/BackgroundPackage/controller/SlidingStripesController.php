<?php
namespace framework\packages\BackgroundPackage\controller;

use framework\component\parent\BackgroundController;
use framework\component\parent\JsonResponse;
use framework\component\parent\ImageResponse;
use framework\packages\BackgroundPackage\entity\FBSBackground;
use framework\packages\BackgroundPackage\repository\FBSBackgroundRepository;
use framework\packages\BackgroundPackage\entity\FBSBackgroundImage;
use framework\packages\BackgroundPackage\repository\FBSBackgroundImageRepository;

class SlidingStripesController extends BackgroundController
{
    /**
    * Route: [name: background_slidingStripes, paramChain: /background/SlidingStripes/{theme}]
    */
    public function slidingStripesAction($theme)
    {
        $this->getContainer()->wireService('BackgroundPackage/entity/FBSBackground');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSBackgroundRepository');
        $bgRepo = new FBSBackgroundRepository();
        $background = $bgRepo->findOneBy(['conditions' => [['key' => 'theme', 'value' => $theme]]]);
        $this->getContainer()->wireService('BackgroundPackage/entity/FBSBackgroundImage');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSBackgroundImageRepository');
        $bgImageRepo = new FBSBackgroundImageRepository();
        $backgroundImages = $bgImageRepo->findBy(['conditions' => [['key' => 'fbsBackgroundTheme', 'value' => $background->getTheme()]]]);

        $maxBackgroundStripes = $this->getContainer()->getSkinData('maxBackgroundStripes');
        if ($maxBackgroundStripes && count($backgroundImages) > $maxBackgroundStripes) {
            $modifiedBackgroundImages = array();
            for ($i = 0; $i < $maxBackgroundStripes; $i++) {
                $modifiedBackgroundImages[] = $backgroundImages[$i];
            }
            $backgroundImages = $modifiedBackgroundImages;
        }
// dump($backgroundImages);exit;
        $viewPath = 'framework/packages/BackgroundPackage/view/background/SlidingStripes/SlidingStripes.php';

        $response = [
            'view' => $this->renderBackground($viewPath, [
                'container' => $this->getContainer(),
                'theme' => $theme,
                'backgroundImages' => $backgroundImages
            ]),
            'data' => []
        ];
        // echo $response['view'];exit;
        // dump($response['view']);exit;
        return new JsonResponse($response);
    }

    /**
    * Route: [name: background_slidingStripes_stripe, paramChain: /background/SlidingStripes/{theme}/{stripeId}]
    */
    // public function stripeAction($theme, $stripeId)
    // {
    //     $path = '........./var/image/backgroundEngine/SlidingStripes/'.$theme.'/stripe'.$stripeId.'.jpg';
    //     return new ImageResponse($path);
    // }
}
