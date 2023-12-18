<?php
// dump($data);
// dump($loopIndex);
foreach ($data as $dataRow):
    $activeStr = (int)$categoriesData['config']['active'] == (int)$dataRow['id'] ? ' active' : '';
    $loopStyleStr = '';
    if ($dataRow['productsCount'] > 0):
?>
            <li class="pc-item<?php echo $activeStr; ?>">
                <a href="<?php echo $dataRow['link']; ?>" class="ajaxCallerLink pc-link sideMenu-row-container" style="margin: 0px;">
                    <?php if ($loopIndex > 0): ?>
                    <span class="nav-link-icon">
                        <img src="/public_folder/plugin/Bootstrap-icons/Dashkit-light/arrow-right-circle-fill.svg">
                    </span>
                    <?php endif; ?>
                    <span class="pc-mtext">
                        <?php echo $dataRow['displayedName']; ?>
                    </span>
                </a>
                <?php  
                if (!empty($dataRow['subdata'])):
                    $loopIndex++;
                    // $loopStyleStr = ' style="padding-left: '.($loopIndex * 20).'px;"';
                    // if ($loopIndex > 0) {
                    //     $loopStyleStr = ' style="padding-left: '.($loopIndex * 20).'px;"';
                    // }
                ?>
                <div class="show"<?php echo $loopStyleStr; ?>>
                    <ul class="pc-navbar flex-column">
                    <?php 
                    $data = $dataRow['subdata'];
                    include ('CategoryLooper.php');
                    // $loopIndex--;
                    ?>
                    </ul>
                </div>
                <?php
                    $loopIndex--;
                endif;
                ?>
            </li>
<?php
    endif;
endforeach;
?>