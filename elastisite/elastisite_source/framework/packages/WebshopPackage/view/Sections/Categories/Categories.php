<?php 
/**
 * @var $categoriesData
*/

/*
###############
# Array details
###############
$categoriesData = [
    'config' => [
        'active' => 8921
    ],
    'data' => [
        0 => [
            'id' => 6754,
            'displayedName' => 'Alma category',
            'link' => 'http://alma/alma1',
            'subdata' => [
                0 => [
                    'id' => 8921,
                    'displayedName' => 'Almaalma category',
                    'link' => 'http://alma/alma2',
                    'subdata' => null
                ]
            ]
        ]
    ]
];
*/
// dump($categoriesData);
// dump($localizedWebshopUrlKey);
?>
<div class="card mb-3 card-noBorderRadius">
    <div class="card-footer card-header-sideMenu justify-content-between align-items-center">
        <div class="col-md-12 sidebar-text-container d-flex align-items-center">
            <!-- <img src="/public_folder/plugin/Bootstrap-icons/Dashkit-light/cart.svg" class="me-2 mb-1" alt="Cart Icon"> -->
            <h5 class="p-0"><?php echo trans('product.categories'); ?></h5>
        </div>
    </div>
    <section class="w-100">
        <div class="row">
            <div class="col">

                <!-- <nav class="flex-content-sidebar pc-sidebar collapse show sideNavbar-container webshop-sidebar-widget">
                    <ul class="pc-navbar">
                        <li class="pc-item pc-caption webshop-sidebar-caption">
                            <label><?php echo trans('product.categories'); ?></label>
                        </li>
                        <?php
                        $loopIndex = 0;
                        $data = $categoriesData['data'];
                        include ('CategoryLooper.php');
                        ?>
                    </ul>
                </nav> -->

                <nav class="flex-content-sidebar pc-sidebar collapse show sideNavbar-container" style="box-shadow: none;">
                    <ul class="pc-navbar sideMenu-row-container">
                    <?php
                        $customCategoryActiveStr = $specialCategorySlugKey == 'RecommendedProducts' ? ' active sideMenu-active' : '';
                        $customCategoryLink = '/'.$localizedWebshopUrlKey.'/'.$localizedCategoryUrlKey.'/'.$localizedRecommendedProductsSlugKey;
                        $customCategoryDisplayedName = $recommendedProductsTitle;
                        include ('CustomCategory.php');
                    ?>
                    </ul>
                    <ul class="pc-navbar sideMenu-row-container">
                    <?php
                        $customCategoryActiveStr = $specialCategorySlugKey == 'AllProducts' ? ' active sideMenu-active' : '';
                        $customCategoryLink = '/'.$localizedWebshopUrlKey.'/'.$localizedCategoryUrlKey.'/'.$localizedAllProductsSlugKey;
                        $customCategoryDisplayedName = $allProductsTitle;
                        include ('CustomCategory.php');
                    ?>
                    </ul>
                    <ul class="pc-navbar sideMenu-row-container">
                        <!-- <li class="pc-item pc-caption webshop-sidebar-caption">
                            <label><?php echo trans('product.categories'); ?></label>
                        </li> -->
                    <?php
                        $loopIndex = 0;
                        $data = $categoriesData['data'];
                        include ('CategoryLooper.php');
                    ?>
                    </ul>
                </nav>

                    <!-- <ul class="pc-navbar">
                        <li class="pc-item pc-caption webshop-sidebar-caption">
                            <label><?php echo trans('product.categories'); ?></label>
                        </li>
                        <?php
                        $loopIndex = 0;
                        $data = $categoriesData['data'];
                        include ('CategoryLooper.php');
                        ?>
                    </ul> -->

            </div>
        </div>

    </section>
<!-- </div> -->
<!-- </section> -->

</div>
<!-- <div class="navbar-wrapper" style="width: 280px; height: 100% !important;">
    <div class="navbar-content ps">
        [Sidebar_OLD]
    </div>
</div> -->