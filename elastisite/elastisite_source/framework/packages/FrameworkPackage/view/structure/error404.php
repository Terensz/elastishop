<?php

use framework\packages\UXPackage\service\ViewTools;

?>
        <div id="sheetContainer" class="sheetWidth">
            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-noPadding">
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer" id="widgetContainer-BannerWidget">{{ BannerWidget }}</div>
                    </div>
                </div>
            </div>
            <div class="row sheetLevel" style="position: relative; z-index: 200;">
                <div class="col-sm-12 widgetRail widgetRail-noPadding">
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer" id="widgetContainer-MenuWidget">{{ MenuWidget }}</div>
                    </div>
                </div>
            </div>
            
            <div class="pc-container">
                <div class="pcoded-content card-container">

                    <div class="row">
                        <div class="col-md-12 card-pack-header">
                            <h4><?php echo trans('error.404.title'); ?></h4>
                            <div class="card-pack-sub-heading">
                                <h5><?php echo trans('this.page.is.unavailable').': <b>'.$container->getFailedRoute().'</b>'; ?></h5>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="row">
                        <div class="col-xl-12 col-md-12">
                    <?php
                        ViewTools::displayComponent('dashkit/card', [
                            'additionalCardClassString' => 'card-highlighted',
                            'title' => '['.trans('system.message').'] '.trans('error.404.title'),
                            'body' => trans('this.page.is.unavailable').': <b>'.$container->getFailedRoute().'</b>',
                            // 'additionalCardClassString' => 'bg-primary text-white',
                        ]);
                    ?>
                        </div>
                    </div> -->

    <?php
    $suggestionsString = '';
    if (count($suggestedRoutes) > 0) {
        foreach ($suggestedRoutes as $suggestedRoute) {
            $suggestedParamChain = null;
            $counter = 0;
            $reservedParamChain = '';
            foreach ($suggestedRoute['paramChains'] as $paramChain => $locale) {
                if ($counter == 0) {
                    $reservedParamChain = $paramChain;
                }
                if ($container->getSession()->getLocale() == $locale) {
                    $suggestedParamChain = $paramChain;
                }
                $counter++;
            }
            $displayedParamChain = $suggestedParamChain ? $suggestedParamChain : $reservedParamChain;
            $suggestionsString .= '<a href="'.$container->getUrl()->getHttpDomain().'/'.$displayedParamChain.'" class="ajaxCallerLink">'.trans($suggestedRoute['title']).'</a><br />';
        }
    }
    ?>

                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                    <?php
                        ViewTools::displayComponent('dashkit/card', [
                            'title' => trans('page.suggestions'),
                            'body' => $suggestionsString,
                            // 'additionalCardClassString' => 'bg-primary text-white',
                        ]);
                    ?>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-noPadding">
                    <div class="widgetWrapper-off widgetContainer" id="widgetContainer-FooterWidget">{{ FooterWidget }}</div>
                </div>
            </div>
        </div>
