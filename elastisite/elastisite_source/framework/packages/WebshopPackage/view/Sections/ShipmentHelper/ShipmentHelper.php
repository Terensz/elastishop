<div class="card mb-3 card-noBorderRadius">
    <div class="card-footer">
        <div class="col-md-12 sidebar-text-container d-flex align-items-center">
            <!-- <img src="/public_folder/plugin/Bootstrap-icons/Dashkit-light/cart.svg" class="me-2 mb-3" alt="Cart Icon"> -->
            <h5 class="mb-2"><?php echo trans('help.panel'); ?></h5>
        </div>
    </div>
    <div class="card-footer">
        <?php 
        
        ?>
        <?php foreach ($alerts as $alert): ?>
        <div class="alert alert-<?php echo $alert['type']; ?>" role="alert">
            <?php echo $alert['text']; ?>
        </div>
        <?php endforeach; ?>
    <?php 
    // dump($errors);
    ?>
    </div>
</div>