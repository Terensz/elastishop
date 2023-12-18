<?php
namespace framework\packages\ForumPackage\routeMap;

class ForumRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'forum',
                'paramChains' => array(
                    'forum' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'title' => 'login.or.register',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'framework/packages/ForumPackage/view/widget/ForumTopicListWidget'
                )
            ),
            array(
                'name' => 'forum_topicListWidget',
                'paramChains' => array(
                    'forum/topicListWidget' => 'default'
                ),
                'controller' => 'framework/packages/ForumPackage/controller/ForumWidgetController',
                'action' => 'forumTopicListWidgetAction',
                'permission' => 'viewGuestContent'
            ),
        );
    }
}