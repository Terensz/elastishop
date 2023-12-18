<?php

use projects\ASC\service\AscRequestService;

App::getContainer()->wireService('projects/ASC/service/AscRequestService');
$pageRoute = App::getContainer()->getRouting()->getPageRoute()->getName();
// echo $viewType;

?>
    
    <div class="navbar-wrapper" style="width: 280px; height: 100% !important;">
        <div class="navbar-content ps">
            <ul class="pc-navbar">

                <!-- <li class="pc-item pc-caption">
                    <label><?php echo trans('dashboards'); ?></label>
                </li> -->

<?php 
$liClassAddStr = '';
?>
                <li class="pc-item<?php echo $liClassAddStr; ?>">
                    <a href="/asc/dashboard" class="ajaxCallerLink pc-link ">
                        <span class="nav-link-icon">
                            <img src="/public_folder/plugin/Bootstrap-icons/Dashkit-light/list-check.svg">
                        </span>
                        <span class="pc-mtext">
                            <?php echo trans('dashboard'); ?>
                        </span>
                    </a>
                </li>
<?php 
// dump($currentSubject);
// dump($pageRoute);
$liClassAddStr = $pageRoute == 'asc_scaleBuilder_scale_dashboard' ? ' active' : '';
?>
                <li class="pc-item<?php echo $liClassAddStr; ?>">
                    <a href="/asc/scaleBuilder/scale/<?php echo $ascScale->getId(); ?>" class="ajaxCallerLink pc-link ">
                        <span class="nav-link-icon">
                            <img src="/public_folder/plugin/Bootstrap-icons/Dashkit-light/list-check.svg">
                        </span>
                        <span class="pc-mtext">
                            <label><?php echo trans('this.admin.scale'); ?></label>
                        </span>
                    </a>
                </li>
                <?php 
// dump($currentSubject);
// dump($pageRoute);
$liClassAddStr = $pageRoute == 'asc_projectTeamwork_scale' ? ' active' : '';
?>
                <li class="pc-item<?php echo $liClassAddStr; ?>">
                    <a href="/asc/projectTeamwork/scale/<?php echo $ascScale->getId(); ?>" class="ajaxCallerLink pc-link ">
                        <span class="nav-link-icon">
                            <img src="/public_folder/plugin/Bootstrap-icons/Dashkit-light/list-check.svg">
                        </span>
                        <span class="pc-mtext">
                            <label><?php echo trans('project.teams'); ?></label>
                        </span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label><?php echo trans('subjects'); ?></label>
                </li>

                <!-- <li class="pc-item">
                    <a href="/asc/scaleBuilder/scale/<?php echo $ascScale->getId(); ?>" class="ajaxCallerLink pc-link "><span class="pc-micon"><i class="material-icons-two-tone">home</i></span><span class="pc-mtext"><?php echo trans('dashboard'); ?></span></a>
                </li> -->
                <!-- <li class="pc-item pc-caption">
                    <label><?php echo trans('subjects'); ?></label>
                    <span><?php echo trans('main.conponents.of.the.admin.scale'); ?></span>
                </li> -->

                <?php foreach ($primarySubjectConfig as $subject => $config): ?>
                <?php 
                // dump($config);
                // $classStrActiveAdd = $currentSubject == $subject ? '-active' : '';
                $urlJuxtaposedSubjectStr = $juxtaposedSubject && $juxtaposedSubject != $subject ? '/juxtaposedSubject/'.$juxtaposedSubject : '';
                $liClassAddStr = $subject && $subject == $currentSubject ? ' active' : '';

                $link = '/asc/scaleBuilder/scale/'.$ascScale->getId().'/subject/'.$subject.$urlJuxtaposedSubjectStr;
                if ($viewType === AscRequestService::VIEW_TYPE_COLUMN_VIEW) {
                    $link = str_replace('/scaleBuilder/', '/scaleBuilder/columnView/', $link);
                }
                ?>
                <li class="pc-item<?php echo $liClassAddStr; ?>">
                    <a href="<?php echo $link; ?>" class="ajaxCallerLink pc-link ">
                        <!-- <span class="pc-micon">
                            <i class="material-icons-two-tone"><?php echo isset($config['iconRefName']) ? $config['iconRefName'] : ''; ?></i>
                        </span> -->
                        <span class="nav-link-icon">
                            <img src="/public_folder/plugin/Bootstrap-icons/Dashkit-light/<?php echo isset($config['iconRefName']) ? $config['iconRefName'] : 'emoji-neutral'; ?>.svg">
                        </span>
                        <span class="pc-mtext">
                            <?php echo trans($config['translationReferencePlural']); ?>
                        </span>
                    </a>
                </li>
                <?php endforeach; ?>

            </ul>

            <!-- <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;">
                </div>
            </div>
            <div class="ps__rail-y" style="top: 0px; right: 0px;">
                <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;">
                </div>
            </div> -->
        </div>
    </div>
