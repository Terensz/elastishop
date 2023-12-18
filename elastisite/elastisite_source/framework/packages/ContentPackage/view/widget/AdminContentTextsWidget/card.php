        <div class="card">
            <!-- <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-header-textContainer">
                    <h6 class="mb-0">
                        <?php echo trans('warning'); ?>
                    </h6>
                </div>
            </div> -->
            <?php foreach ($textArrayDef as $textDef): ?>
            <div class="card-footer">
            <?php 
                $textEn = $textArrayEn[$textDef['code']];
                include('entry.php');
            ?>
            </div>
            <?php endforeach; ?>
        </div>