<?php 

$configuredMaxItemsOnPage = 10;

/*
Example data: 
*/

$table = [
    'header' => [
        'Entitás neve',
        'Szülő osztály neve',
        'Entitás állapota'
    ], 
    'data' => [
        [
            'ExceptionLog',
            'DbEntity',
            'A tábla létezik'
        ],
        [
            'ExceptionTrace',
            null,
            null
        ],
        [
            'ExceptionTrace',
            null,
            null
        ]
    ]
];

?>
    <div class="row">
            <div class="col-xl-12 col-md-12">

                <div class="card table-card">
                    <!-- <div class="card-header">
                        <h5>Alma Teendők</h5>
                    </div> -->
<?php
$priorizedTabActiveString = ' active';
$closedTabActiveString = '';
if ($activeCategory == 'closed') {
    $priorizedTabActiveString = '';
    $closedTabActiveString = ' active';
}
?>
                    <div class="card-header" style="padding-bottom: 0px !important;">
                        <ul class="nav nav-tabs" id="myTabs" role="tablist" style="border-bottom: 0px !important;">
                            <li class="nav-item">
                                <a class="navLink-priorized nav-link<?php echo $priorizedTabActiveString; ?>" id="tab1-tab" data-toggle="tab" href="" onclick="EventActualityList.switchTab(event, 'priorized');" role="tab" aria-controls="tab1" aria-selected="true">Aktuális teendők</a>
                            </li>
                            <li class="nav-item">
                                <a class="navLink-closed nav-link<?php echo $closedTabActiveString; ?>" id="tab2-tab" data-toggle="tab" href="" onclick="EventActualityList.switchTab(event, 'closed');" role="tab" aria-controls="tab2" aria-selected="false">Lezárt teendők</a>
                            </li>
                        </ul>
                    </div>

                    <div class="pro-scroll">

                        <div class="card-body p-0">

                            <?php if (isset($config['showStatusCharts'])): ?>
                            <div class="text-primary m-2">
                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-3">
                                        <div class="card-statCircle-container">
                                            <div class="mt-2 d-flex align-items-center">
                                                <i class="icon feather icon-clock f-16 text-primary me-2"></i>
                                                <h6 class="m-0 text-nobreak">Mai teendők</h6>
                                            </div>
                                            <svg class="card-statCircle" width="140" height="140">
                                                <circle cx="70" cy="70" r="58" stroke="#4267B2" stroke-width="6" fill="none"/>
                                                <text x="50%" y="50%" text-anchor="middle" dy=".3em" font-size="24"><?php echo isset($sum['todays']) ? $sum['todays'] : '0'; ?></text>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-3">
                                        <div class="card-statCircle-container">
                                            <div class="mt-2 d-flex align-items-center">
                                                <i class="icon feather icon-alert-circle f-16 text-danger me-2"></i>
                                                <h6 class="m-0 text-nobreak">Lejárt teendők</h6>
                                            </div>
                                            <svg class="card-statCircle" width="140" height="140">
                                                <circle cx="70" cy="70" r="58" stroke="#E60023" stroke-width="6" fill="none"/>
                                                <text x="50%" y="50%" text-anchor="middle" dy=".3em" font-size="24"><?php echo isset($sum['expired']) ? $sum['expired'] : '0'; ?></text>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-3">
                                        <div class="card-statCircle-container">
                                            <div class="mt-2 d-flex align-items-center">
                                                <i class="icon feather icon-thumbs-up f-16 text-success me-2"></i>
                                                <h6 class="m-0 text-nobreak">Lezárt teendők</h6>
                                            </div>
                                            <svg class="card-statCircle" width="140" height="140">
                                                <circle cx="70" cy="70" r="58" stroke="#42B72A" stroke-width="6" fill="none"/>
                                                <text x="50%" y="50%" text-anchor="middle" dy=".3em" font-size="24"><?php echo isset($sum['closed']) ? $sum['closed'] : '0'; ?></text>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-3">
                                        <div class="card-statCircle-container">
                                            <div class="mt-2 d-flex align-items-center">
                                                <i class="icon feather icon-clock f-16 text-secondary me-2"></i>
                                                <h6 class="m-0 text-nobreak">Elnapolt teendők</h6>
                                            </div>
                                            <svg class="card-statCircle" width="140" height="140">
                                                <circle cx="70" cy="70" r="58" stroke="#888888" stroke-width="6" fill="none"/>
                                                <text x="50%" y="50%" text-anchor="middle" dy=".3em" font-size="24"><?php echo isset($sum['postponed']) ? $sum['postponed'] : '0'; ?></text>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="table-responsive">
                                <?php  
                                $listDataPriorized = [];
                                $listDataPriorized = array_merge($listDataPriorized, $listData['Todays']);
                                $listDataPriorized = array_merge($listDataPriorized, $listData['Expired']);
                                $listDataPriorized = array_merge($listDataPriorized, $listData['Postponed']);
                                // dump($listDataPriorized);
                                ?>
                                <?php  
                                $styleString = '';
                                if ($activeCategory == 'closed') {
                                    $styleString = ' style="display: none;"';
                                }
                                ?>
                                <div id="EventActualityCard-list-priorized"<?php echo $styleString; ?>>
                                    <table class="table table-hover m-b-0">
                                        <thead>
                                            <tr>
                                                <th>Típus</th>
                                                <th>Szerkesztés</th>
                                                <th>Elem</th>
                                                <!-- <th>Státusz</th> -->
                                                <th>
                                                    Esedékesség
                                                    <!-- <img src="/public_folder/plugin/Bootstrap-icons/chevron-up.svg">
                                                    <img src="/public_folder/plugin/Bootstrap-icons/chevron-down.svg"> -->
                                                </th>
                                                <th>Lezárás (sikeres)</th>
                                                <th>Lezárás (sikertelen)</th>
                                            </tr>
                                        </thead>
                                        <?php 
                                        $actualPage = $page_priorized;
                                        $itemsCount = count($listDataPriorized);
                                        $maxItemsOnPage = $configuredMaxItemsOnPage;
                                        $pagesCount = ceil($itemsCount / $maxItemsOnPage);
                                        $category = 'priorized';
                                        $pageCounter = 1;
                                        $pageItemCounter = 1;
                                        // dump($listDataPriorized);
                                        ?>
                                        <tbody>
                                            <?php foreach ($listDataPriorized as $dataRow): ?>
                                            <?php 
                                            // dump($dataRow);
                                                if ($pageItemCounter == ($maxItemsOnPage + 1)) {
                                                    $pageCounter++;
                                                    $pageItemCounter = 1;
                                                }
                                                $label = $dataRow['label'];
                                                include('EventActualityListTableRow_closable.php');
                                                $pageItemCounter++;
                                            ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php  
                                    // dump($actualPage);
                                    if ($pagesCount > 1) {
                                        include('EventActualityListPager.php');
                                    }
                                    ?>
                                </div>
                                <?php  
                                $styleString = '';
                                if ($activeCategory == 'priorized') {
                                    $styleString = ' style="display: none;"';
                                }
                                ?>
                                <div id="EventActualityCard-list-closed"<?php echo $styleString; ?>>
                                    <table class="table table-hover m-b-0">
                                        <thead>
                                            <tr>
                                                <th>Típus</th>
                                                <th>Szerkesztés</th>
                                                <th>Elem</th>
                                                <!-- <th>Státusz</th> -->
                                                <th>Esedékesség</th>
                                                <th>Visszanyitás</th>
                                            </tr>
                                        </thead>
                                        <?php 
                                        $actualPage = $page_closed;
                                        $itemsCount = count($listData['Closed']);
                                        $maxItemsOnPage = $configuredMaxItemsOnPage;
                                        $pagesCount = ceil($itemsCount / $maxItemsOnPage);
                                        $category = 'closed';
                                        $pageCounter = 1;
                                        $pageItemCounter = 1;
                                        ?>
                                        <tbody>
                                            <?php foreach ($listData['Closed'] as $dataRow): ?>
                                            <?php 
                                                if ($pageItemCounter == ($maxItemsOnPage + 1)) {
                                                    $pageCounter++;
                                                    $pageItemCounter = 1;
                                                }
                                                $category = 'Closed';
                                                include('EventActualityListTableRow_reopenable.php');
                                                $pageItemCounter++;
                                            ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php 
                                    // dump($actualPage);
                                    if ($pagesCount > 1) {
                                        echo $views['pager'];
                                    }
                                    ?>
                                </div>

                            </div>

                        </div> <!-- /card-body -->

                    </div>
                    
                </div>

            </div>
        </div>

    <!-- </div>
</div> -->