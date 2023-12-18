<?php 

?>
        <div class="row row-cols-1 row-cols-sm-3 row-cols-md-4 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 g-4">
        <?php foreach ($unitDataArray as $subject => $unitDataArrayOfSubject): ?>
            <?php 
                    // dump($unitDataArrayOfSubject);
                    // continue;
            ?>
            <?php foreach ($unitDataArrayOfSubject as $columnIndex => $columnUnitDataArray): ?>
            <div class="col">
                <div class="card">
                    <?php 
                    // dump($columnUnitDataArray);
                    ?>
                    <?php if ($columnUnitDataArray['parentUnitData']): ?>
                    <div class=" card-header d-flex justify-content-between align-items-center">
                        <div class="card-header-textContainer ellipsis-container" style="width: 100%">
                            <h6 class="mb-0 ellipsis-text">
                                <a class="ajaxCallerLink link-underlined" href="/asc/scaleBuilder/columnView/scale/<?php echo $ascScaleId; ?>/child/<?php echo $columnUnitDataArray['parentUnitData']['data']['ascUnitId']; ?>">
                                    <?php echo $columnUnitDataArray['parentUnitData']['data']['mainEntryTitle'] ?>
                                </a>
                            </h6>
                        </div>
                    </div>
                    <?php elseif (isset($columnUnitDataArray['headerData'])): ?>
                    <div class=" card-header d-flex justify-content-between align-items-center">
                        <div class="card-header-textContainer ellipsis-container" style="width: 100%">
                            <h6 class="mb-0 ellipsis-text">
                                <?php if ($columnUnitDataArray['headerData']['link']): ?>
                                <a class="ajaxCallerLink link-underlined" href="<?php echo $columnUnitDataArray['headerData']['link']; ?>">
                                    <?php echo $columnUnitDataArray['headerData']['title'] ?>
                                </a>
                                <?php else: ?>
                                    <?php echo $columnUnitDataArray['headerData']['title'] ?>
                                <?php endif; ?>
                            </h6>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="card-body m-0 p-0">
                        <?php foreach ($columnUnitDataArray['columnUnitsData'] as $unitData): ?>
                        <?php 
                            $titleStr = $unitData['data']['mainEntryTitle'] && !empty(trim($unitData['data']['mainEntryTitle'])) ? $unitData['data']['mainEntryTitle'] : '['.trans('no.title').']';
                        ?>
                            
                        <div class="columnView-cell<?php echo $unitData['data']['selected'] ? ' cell-selected' : ''; ?>">
                            <a class="ajaxCallerLink link-underlined" href="/asc/scaleBuilder/columnView/scale/<?php echo $ascScaleId; ?>/child/<?php echo $unitData['data']['ascUnitId']; ?>">
                                <p class="card-text">
                                    <?php echo $titleStr; ?>
                                </p>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </div>