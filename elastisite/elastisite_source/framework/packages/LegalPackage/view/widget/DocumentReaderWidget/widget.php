<div style="display: flex;" class="flex-container">
    <div class="flex-content-sidebar" style="width: 380px; height: 100% !important; z-index: 0 !important;">
        <div class="navbar-wrapper" style="width: 100%; height: 100% !important;">
            <div class="navbar-contentss ps" id="LegalPackage_DocumentReader_SidebarContainer">
                <?php include('Sidebar.php'); ?>
            </div>
        </div>
    </div>
    <div class="flex-content-main pc-container">
        <div class="pcoded-content card-container">
            <div class="card" id="LegalPackage_DocumentReader_DocumentContainer">
                <div class="card-body">
                <h3><?php echo trans($title); ?></h3>
                <?php 
                echo $textView;
                ?>
                </div>
            </div>
        </div>
    </div>
</div>