<?php

use projects\ASC\entity\ProjectTeamInvite;

include('definitions/pathToCommonComponents.php');

App::getContainer()->wireService('projects/ASC/entity/ProjectTeamInvite');
?>
<div class="card">

    <div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <div class="card-header-iconContainer-left" style="line-height: 1;">
                <a class="" href="" onclick="ProjectTeamwork.editProjectTeam(event, false, '<?php echo $projectTeamDataRow['projectTeamId']; ?>');">
                    <img src="/public_folder/plugin/Bootstrap-icons/edit.svg">
                </a>
            </div>
            <h6 class="mb-0 text-white"><?php echo $projectTeamDataRow['projectTeamName']; ?></h6>
            <?php 
            // dump('alma');
            // echo "&nbsp; (".App::getElapsedLoadingTime().")";
            ?>
        </div>
    </div>

    <div class="pro-scroll">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-b-0">
                    <thead>
                        <tr>
                            <th>Név</th>
                            <th>E-mail</th>
                            <th>Típus</th>
                            <th>Státusz</th>
                            <th>Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5">
                                <div class="mb-0">
                                    <!-- <button onclick="ProjectTeamwork.newProjectTeam(event, false);" class="ajaxCaller btn btn-success">Új tag meghívása</button> -->
                                    <button id="" name=""
                                        onclick="ProjectTeamwork.newProjectTeamInvite(event, false, '<?php echo $projectTeamDataRow['projectTeamId']; ?>');"
                                        type="button" class="btn btn-success">Új tag meghívása
                                    </button>

                                    <button id="" name=""
                                        onclick="ProjectTeamwork.newProjectTeamUser(event, false, '<?php echo $projectTeamDataRow['projectTeamId']; ?>');"
                                        type="button" class="btn btn-primary">Felhasználó hozzárendelése
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php if (isset($projectTeamUserDataBlock['invites'])): ?>
                        <?php foreach ($projectTeamUserDataBlock['invites'] as $projectTeamUserDataRow): ?>
                            <?php 
                            $debug = false;

                            if ($debug):
                            ?>
                            <!-- <tr>
                                <td>
                            <?php 
                                dump($projectTeamUserDataRow);
                            ?>
                                </td>
                            </tr> -->
                            <?php 
                            endif;
                            ?>
                        <tr>
                            <td><?php echo $projectTeamUserDataRow['userFullName']; ?></td>
                            <td><?php echo $projectTeamUserDataRow['userEmail']; ?></td>
                            <td><?php echo trans('invite'); ?></td>
                            <?php 
                            $inviteStatus = $projectTeamUserDataRow['inviteStatus'];
                            $classAdd = '';
                            if ($inviteStatus == ProjectTeamInvite::STATUS_ACTIVE_TEXT) {
                                $classAdd = 'success';
                            }
                            if ($inviteStatus == ProjectTeamInvite::STATUS_BANNED_TEXT) {
                                $classAdd = 'danger';
                            }
                            if ($inviteStatus == ProjectTeamInvite::STATUS_USED_TEXT) {
                                $classAdd = 'primary';
                            }
                            if ($inviteStatus == ProjectTeamInvite::STATUS_EXPIRED_TEXT) {
                                $classAdd = 'warning';
                            }
                            ?>
                            <td>
                                <div><label class="badge bg-light-<?php echo $classAdd; ?>"><?php echo trans($projectTeamUserDataRow['inviteStatus']); ?></label></div>
                            </td>
                            <td>
                                <a class="ajaxCallerLink" href="" onclick="ProjectTeamwork.editProjectTeamInvite(event, false, '<?php echo $projectTeamUserDataRow['projectTeamInviteId']; ?>');"><i class="icon feather icon-edit f-16 text-success"></i></a>
                                <!-- <a class="ajaxCallerLink" href="" onclick=""><i class="feather icon-trash-2 ms-3 f-16 text-danger"></i></a> -->
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if (isset($projectTeamUserDataBlock['teamMembers'])): ?>
                        <?php foreach ($projectTeamUserDataBlock['teamMembers'] as $projectTeamUserDataRow): ?>
                            <?php 
                            // dump($projectTeamUserDataRow);
                            ?>
                        <tr>
                            <td><?php echo $projectTeamUserDataRow['userFullName']; ?></td>
                            <td><?php echo $projectTeamUserDataRow['userEmail']; ?></td>
                            <td><?php echo trans('team.member'); ?></td>
                            <td>
                                <div><label class="badge bg-light-success"><?php echo trans(ProjectTeamInvite::STATUS_ACTIVE_TEXT); ?></label></div>
                            </td>
                            <td>
                                <a class="ajaxCallerLink" href="" onclick="ProjectTeamwork.editProjectTeamUser(event, false, '<?php echo $projectTeamUserDataRow['projectTeamUserId']; ?>', '<?php echo $projectTeamDataRow['projectTeamId']; ?>');"><i class="icon feather icon-edit f-16 text-success"></i></a>
                                <!-- <a class="ajaxCallerLink" href="" onclick=""><i class="feather icon-trash-2 ms-3 f-16 text-danger"></i></a> -->
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<script>

</script>