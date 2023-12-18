<?php
namespace framework\packages\ForumPackage\controller;

use framework\component\parent\WidgetController;

class ForumWidgetController extends WidgetController
{
    /**
    * Route: [name: forum_topicListWidget, paramChain: /forum/topicListWidget]
    */
    public function forumTopicListWidgetAction()
    {
        $viewPath = 'framework/packages/ForumPackage/view/widget/ForumTopicListWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('ForumTopicListWidget', $viewPath, [
                'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }
}
