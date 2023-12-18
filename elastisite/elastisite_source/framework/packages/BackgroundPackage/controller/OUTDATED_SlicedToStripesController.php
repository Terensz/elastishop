<?php
namespace framework\packages\BackgroundPackage\controller;

use framework\component\parent\BackgroundController;
use framework\component\parent\JsonResponse;
use framework\component\parent\ImageResponse;

class SlicedToStripesController extends BackgroundController
{
    /**
    * Route: [name: background_slicedToStripes, paramChain: /background/SlicedToStripes/{theme}]
    */
    public function slicedToStripesAction($theme)
    {
        $viewPath = 'framework/packages/BackgroundPackage/view/SlicedToStripes/SlicedToStripes.php';

        $response = [
            'view' => $this->renderBackground($viewPath, [
                'container' => $this->getContainer(),
                'theme' => $theme
            ]),
            'data' => []
        ];

        return new JsonResponse($response);
    }

    /**
    * Route: [name: background_slicedToStripes_stripe, paramChain: /background/SlicedToStripes/{theme}/{stripeId}]
    */
    public function stripeAction($theme, $stripeId)
    {
        $path = 'var/image/backgroundEngine/SlicedToStripes/'.$theme.'/stripe'.$stripeId.'.jpg';
        return new ImageResponse($path);
    }
}
