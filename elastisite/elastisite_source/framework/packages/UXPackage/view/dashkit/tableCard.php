<?php 

// $configuredMaxItemsOnPage = 10;

/*
Example data: 
*/

// $table = [
//     'header' => [
//         'Entitás neve',
//         'Szülő osztály neve',
//         'Entitás állapota'
//     ], 
//     'data' => [
//         [
//             'ExceptionLog',
//             'DbEntity',
//             'A tábla létezik'
//         ],
//         [
//             'ExceptionTrace',
//             null,
//             null
//         ],
//         [
//             'ExceptionTrace',
//             null,
//             null
//         ]
//     ]
// ];

?>
    <div class="row">
            <div class="col-xl-12 col-md-12">

                <div class="card table-card">
                    <!-- <div class="card-header">
                        <h5>Alma Teendők</h5>
                    </div> -->
<?php
// dump($tableData);
// dump($tableData['configuration']['displayedProperties']);
$priorizedTabActiveString = ' active';
$closedTabActiveString = '';
// if ($activeCategory == 'closed') {
//     $priorizedTabActiveString = '';
//     $closedTabActiveString = ' active';
// }
?>
                    <!-- <div class="card-header" style="padding-bottom: 0px !important;">
                        <ul class="nav nav-tabs" id="myTabs" role="tablist" style="border-bottom: 0px !important;">
                            <li class="nav-item">
                                <a class="navLink-priorized nav-link<?php echo $priorizedTabActiveString; ?>" id="tab1-tab" data-toggle="tab" href="" onclick="EventActualityList.switchTab(event, 'priorized');" role="tab" aria-controls="tab1" aria-selected="true">Aktuális teendők</a>
                            </li>
                            <li class="nav-item">
                                <a class="navLink-closed nav-link<?php echo $closedTabActiveString; ?>" id="tab2-tab" data-toggle="tab" href="" onclick="EventActualityList.switchTab(event, 'closed');" role="tab" aria-controls="tab2" aria-selected="false">Lezárt teendők</a>
                            </li>
                        </ul>
                    </div> -->

                    <div class="pro-scroll">

                        <div class="card-body p-0">

                            <div class="table-responsive">
                                <?php  
                                // $listDataPriorized = [];
                                // $listDataPriorized = array_merge($listDataPriorized, $listData['Todays']);
                                // $listDataPriorized = array_merge($listDataPriorized, $listData['Expired']);
                                // $listDataPriorized = array_merge($listDataPriorized, $listData['Postponed']);
                                // dump($listDataPriorized);
                                ?>
                                <?php  
                                $styleString = '';
                                // if ($activeCategory == 'closed') {
                                //     $styleString = ' style="display: none;"';
                                // }
                                ?>
                                <?php
                                $colSumAdder = 0;
                                $colCounter = 0;
                                $originalOnclickStr = ' onclick="'.$tableData['data']['dataGridId'].'.orderBy({{orderByProp}}, {{orderByDirection}});"';
                                $orderByDirection = $tableData['search']['orderByDirection'];
                                // dump($tableData['search']['orderByField']);
                                // dump($orderByDirection);
                                // dump($tableData['configuration']['columnParams']);
                                ?>
                                <div id=""<?php echo $styleString; ?>>
                                    <form name="<?php echo $tableData['data']['dataGridId']; ?>_form" id="<?php echo $tableData['data']['dataGridId']; ?>_form" method="get" action="">
                                        <input type="hidden" autocomplete="false">
                                        <table class="table table-hover m-b-0">
                                            <thead>
                                                <tr>
                                                <?php foreach ($tableData['configuration']['columnParams'] as $column): ?>
                                                    <?php 

                                                    /**
                                                     * @var $orderByChevron
                                                     * We will use this variable to determine if the column is displayed or not.
                                                    */
                                                    $orderByChevron = null;

                                                    if ($column['role'] == 'deleteButton'): 
                                                    ?>
                                                    <th><?php echo trans('delete'); ?></th>
                                                    <?php 
                                                    endif;
                                                    if (($column['propertyName'] != 'id' || ($column['propertyName'] == 'id' 
                                                    && $tableData['configuration']['showId'])) && $column['visible'] 
                                                    && (!in_array($column['role'], ['data', 'deleteButton']) || ($column['role'] == 'data' && in_array($column['propertyName'], $tableData['configuration']['displayedProperties'])))):
                                                        if ($tableData['configuration']['allowManualOrder']) {
                                                            // dump($column['propertyName']);
                                                            // $orderByProp = $column['name'];
                                                            $orderByProp = $column['propertyName'];
                                                            $orderByChevron = '-expand';
                                                            if ($tableData['search']['orderByField'] == $column['propertyName']) {
                                                                // dump($orderByDirection);
                                                                // dump($column['propertyName']);
                                                                if ($orderByDirection == 'DESC') {
                                                                    $orderByChevron = '-down';
                                                                    $orderByDirection = 'ASC';
                                                                } else {

                                                                    $orderByChevron = '-up';
                                                                    $orderByDirection = 'DESC';
                                                                }
                                                                // dump($orderByDirection);
                                                            }
                
                                                            $onclickStr = str_replace('{{orderByProp}}', "'".$orderByProp."'", $originalOnclickStr);
                                                            $onclickStr = str_replace('{{orderByDirection}}', "'".$orderByDirection."'", $onclickStr);
                                                            $cursorStr = 'pointer';
                                                        }
                                                        if (!$tableData['configuration']['allowManualOrder'] || $column['role'] == 'deleteButton') {
                                                            $onclickStr = '';
                                                            $cursorStr = 'default';
                                                        }
                                                    endif;
                                                    ?>

                                                    <?php if ($orderByChevron): ?>
                                                    <th style="cursor: pointer;">

    <?php
        // dump($conditionPosts);
        $conditionPosts = $tableData['search']['conditionPosts'];
        if (isset($conditionPosts[$column['propertyName']]['type']) && $conditionPosts[$column['propertyName']]['type'] == 'text'):
    ?>
                    <input class="form-control dataGrid-text" autocomplete="false" type="text" id="<?php echo $tableData['data']['dataGridId']; ?>_<?php echo $column['propertyName']; ?>" name="<?php echo $tableData['data']['dataGridId']; ?>_<?php echo $column['propertyName']; ?>" value="<?php echo isset($conditionPosts[$column['propertyName']]['value']) ? $conditionPosts[$column['propertyName']]['value'] : ''; ?>">
    <?php
        elseif (isset($conditionPosts[$column['propertyName']]['type']) && $conditionPosts[$column['propertyName']]['type'] == 'multiselect'):
    ?>
                    <select id="<?php echo $tableData['data']['dataGridId']; ?>_<?php echo $column['propertyName']; ?>" name="<?php echo $tableData['data']['dataGridId']; ?>_<?php echo $column['propertyName']; ?>[]" multiple class="multiselect-input">
    <?php
            foreach ($tableData['configuration']['multiselectValues'][$column['propertyName']] as $multiselect):
                $multiValues = $conditionPosts[$column['propertyName']]['value'];
                $selectedStr = is_array($multiValues) && in_array($multiselect['value'], $multiValues) ? ' selected' : '';
    ?>
                        <option value="<?php echo $multiselect['value']; ?>"<?php echo $selectedStr; ?>><?php echo $multiselect['displayed']; ?></option>
    <?php
            endforeach;
    ?>
                    </select>
    <?php
        elseif (isset($conditionPosts[$column['propertyName']]['type']) && $conditionPosts[$column['propertyName']]['type'] == 'date'):
    ?>
                    <input class="form-control" type="text" id="<?php echo $tableData['data']['dataGridId']; ?>_<?php echo $column['propertyName']; ?>" name="<?php echo $tableData['data']['dataGridId']; ?>_<?php echo $column['propertyName']; ?>" value="<?php echo isset($conditionPosts[$column['propertyName']]['value']) ? $conditionPosts[$column['propertyName']]['value'] : ''; ?>">
    <?php
        endif;
    ?>
                                                        <div style="width: 100%; margin-top: 10px;"<?php echo $onclickStr; ?>>
                                                            <?php echo trans($column['title']); ?>
                                                            <img src="/public_folder/plugin/Bootstrap-icons/chevron<?php echo $orderByChevron; ?>.svg">
                                                        </div>
                                                    </th> 
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                                    <!-- <th>
                                                        <?php echo trans('operations'); ?>
                                                    </th> -->
                                                </tr>
                                            </thead>
                                            <?php 
                                            // $actualPage = isset($tableData['pager']['currentPage']) ? $tableData['pager']['currentPage'] : 1;
                                            // $itemsCount = count($listDataPriorized);
                                            // $maxItemsOnPage = $configuredMaxItemsOnPage;
                                            // $pagesCount = ceil($itemsCount / $maxItemsOnPage);
                                            // $category = 'priorized';
                                            // $pageCounter = 1;
                                            // $pageItemCounter = 1;
                                            ?>
                                            <tbody>
                                                <?php foreach ($tableData['data']['gridData'] as $dataRow): ?>
                                                    <?php 
                                                    $id = isset($dataRow['id']) ? $dataRow['id'] : null;
                                                    // dump($dataRow['id']);
                                                    ?>
                                                <tr class="DataGrid-row">
                                                    <?php foreach ($dataRow as $dataCellProperty => $dataCellValue): ?>

                                                        <!-- <td><?php echo $dataCellValue; ?></td> -->
                                                    
                                                    <?php if (in_array($dataCellProperty, $tableData['configuration']['displayedProperties'])): ?>

                                                        <td style="cursor: pointer;" onclick="<?php echo $tableData['data']['dataGridId']; ?>.edit(event, '<?php echo $id; ?>');"><?php echo $dataCellValue; ?></td>
                                                        

                                                    <?php endif; ?>
                                                <?php 
                                                    // if ($pageItemCounter == ($maxItemsOnPage + 1)) {
                                                    //     $pageCounter++;
                                                    //     $pageItemCounter = 1;
                                                    // }
                                                    // $label = $dataRow['label'];
                                                    // include('EventActualityListTableRow_closable.php');
                                                    // $pageItemCounter++;
                                                ?>
                                                    
                                                    <?php endforeach; ?>
                                                    <!-- <td>
                                                        <div class="card-tableCell-iconContainer" style="line-height: 1;">
                                                            <a class="" href="" onclick="<?php echo $tableData['data']['dataGridId']; ?>.edit(event, '<?php echo $id; ?>');">
                                                                <img src="/public_folder/plugin/Bootstrap-icons/edit.svg">
                                                            </a>
                                                            <a class="" href="" onclick="<?php echo $tableData['data']['dataGridId']; ?>.deleteRequest(event, '<?php echo $id; ?>');">
                                                                <img src="/public_folder/plugin/Bootstrap-icons/delete.svg">
                                                            </a>
                                                        </div>
                                                    </td> -->


                                                    <?php if (isset($tableData['configuration']['columnParams']['deleteButton'])): ?>
                                                    <td>
                                                        <?php  
                                                        // echo isset($dataRow['deletable']) && $dataRow['deletable'] ? $dataRow['deletable'] : 'alma';
                                                        ?>
                                                        <?php if (isset($dataRow['deletable']) && $dataRow['deletable']): ?>
                                                            <a class="" href="" onclick="<?php echo $tableData['data']['dataGridId']; ?>.deleteRequest(event, '<?php echo $id; ?>');">
                                                                <img src="/public_folder/plugin/Bootstrap-icons/delete.svg">
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <?php endif; ?>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </form>
                                    <?php  
                                    // dump($actualPage);
                                    if ($tableData['pager']['totalPages'] > 1) {
                                        include('tableParts/pager.php');
                                    }
                                    ?>
                                </div>

                            </div> <!-- /table-responsive -->

                        </div> <!-- /card-body -->

                    </div>
                    
                </div>

            </div>
        </div>
<script>
    $('document').ready(function() {
        // $('.multiselect-input').select2({
        //     placeholder: 'Select an option'
        // });
        $('.multiselect-input').select2({
            placeholder: 'Select an option'
        }).on('select2:select', function (e) {
            // Itt írd meg a kiválasztott érték kezelését vagy stílusváltoztatásokat.
            var selectedValue = e.params.data.text; // A kiválasztott érték szövege

            // Például, itt hozzáadhatsz egy "tag" stílust a kiválasztott elemhez.
            // $(this).next('.select2-container').find('.select2-selection__choice:last-child').addClass('selected-tag');
        });
    });
</script>
    <!-- </div>
</div> -->