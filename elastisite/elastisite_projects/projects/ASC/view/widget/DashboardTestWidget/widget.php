<?php

use projects\ASC\repository\AscUnitRepository;
use projects\ASC\service\AscConfigService;

App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
App::getContainer()->wireService('projects/ASC/service/AscConfigService');

?>

<script src="/public_folder/plugin/Dropzone/dropzone.min.js"></script>
<link rel="stylesheet" href="/public_folder/plugin/Dropzone/dropzone.min.css" type="text/css" />
<script src="/public_folder/plugin/CKEditor/ckeditor/ckeditor.js"></script>

<?php 
// include('definitions/pathToBuilder.php');
?>

<style>
.sideNavbar-container {
    display: block;
}
@media (min-width: 992px) {
    .sideNavbar-hamburger-container {
        /* display: none; */
    }
}
@media (max-width: 991.98px) {
    .sideNavbar-hamburger-container {
        /* display: block; */
    }
    .sideNavbar-container.collapsed {
        display: none;
    }
}
#AscScaleBuilder_BuilderInterface_container {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
}
.pc-sidebar {
    flex: 0 0 280px; /* Rögzített 280px szélesség */
    box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
    z-index: 1026;
    overflow-y: auto;
}
.pc-container {
    flex: 1;
    /* padding: 10px; */
}
</style>

<div id="AdminScaleBuilder-container" class="AdminScaleBuilder-fontSize-normalText">

    <!-- Navbar -->
    <!-- <nav class="navbar navbar-light bg-light sideNavbar-hamburger-container page-header" id="PrimarySubjectBar_hamburger_container"> -->
        <!-- <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#AscScaleBuilder_PrimarySubjectBar_container" 
                aria-controls="AscScaleBuilder_PrimarySubjectBar_container" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div> -->

        <!-- <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex align-items-center">

                        <button class="navbar-toggler" style="display: block !important;" type="button" data-bs-toggle="collapse" data-bs-target="#AscScaleBuilder_PrimarySubjectBar_container" 
                            aria-controls="AscScaleBuilder_PrimarySubjectBar_container" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <i class="material-icons-two-tone text-primary mb-1 me-2">list</i>
                        <i class="material-icons-two-tone text-primary mb-1 me-2">view_week</i>

                        <div class="page-header-title">
                            <h5 class="m-b-10">Admin Skála Építő</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/asc/scaleLister">Admin skálák</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/asc/scaleBuilder/scale/213000">Kigyúrom magam</a>
                            </li>
                            <li class="breadcrumb-item">
                                dashboard
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div> -->
    <!-- </nav> -->

    <div id="AscScaleBuilder_BuilderInterface_container" style="display: flex;">

        <!-- Sidebar -->
        <!-- collapse -->
        <nav class="pc-sidebar collapse show sideNavbar-container" id="AscScaleBuilder_PrimarySubjectBar_container" style="width: 280px; height: 100% !important; z-index: 0 !important;">
        <?php include('examplePrimarySubjectBar.php'); ?>
        </nav>

        <!-- Main Content -->
        <div class="pc-container">
            <div id="AscScaleBuilder_ControlPanel_container" style="width: 100%;">
            <?php 
            include('exampleControlPanel.php'); 
            ?>
            </div>
            <div id="AscScaleBuilder_Content_container" style="width: 100%;">
            <?php include('exampleContent.php'); ?>
            </div>
        </div>

    </div>
</div> <!-- / #AdminScaleBuilder-container -->
