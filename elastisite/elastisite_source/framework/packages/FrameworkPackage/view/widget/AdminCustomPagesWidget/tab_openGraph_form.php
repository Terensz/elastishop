<form name="FrameworkPackage_customPageOpenGraph_form" id="FrameworkPackage_customPageOpenGraph_form" method="POST" action="" enctype="multipart/form-data">
<?php 
// $formView = $viewTools->create('form')->setForm($form);
// $formView->add('hidden')->setPropertyReference('customPageId')->setLabel(trans('custom.page'));
// $formView->add('text')->setPropertyReference('openGraphId')->setLabel(trans('open.graph'));
// $formView->setFormMethodPath('admin/customPage/basic/editForm');
// $formView->displayForm(false, false)->displayScripts();
// dump($customPageOpenGraph);
if ($customPageOpenGraph):
    $openGraph = $customPageOpenGraph->getOpenGraph();
    $imageFile = $openGraph->getMainImageFile('thumbnail_w120');

    if ($imageFile) {
        // dump($openGraph);
    }
?>

<a href="" onclick="CustomPageOpenGraph.removeCustomPageOpenGraph(event)"><?php echo trans('modify'); ?></a>
<div class="card tagFrame-fullWidth-auto" id="customPageOpenGraph_input_<?php echo $openGraph->getId() ?>" data-opengraphid="<?php echo $openGraph->getId() ?>">
    <div class="card-footer">
        <div class="tag-light">
            <div class="row">
                <div class="" style="width: 130px; float: left;">
                    <?php if ($imageFile): ?>
                    <img src="/openGraph/image/<?php echo $imageFile->getFile()->getFileName().'.'.$imageFile->getFile()->getExtension(); ?>">
                    <?php else: ?>
                        (No image)
                    <?php endif; ?>
                </div>
                <div class="">
                    <table style="width: 100%;">
                        <tbody>
                            <tr>
                                <td id="customPageOpenGraph_input_<?php echo $openGraph->getId() ?>_title">
                                <b><?php echo $openGraph->getTitle(); ?></b>
                                </td>
                            </tr>
                            <tr>
                                <td id="customPageOpenGraph_input_<?php echo $openGraph->getId() ?>_description">
                                <?php echo strip_tags($openGraph->getDescription()); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
else:
?>

<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <?php echo trans('information'); ?>
    </div>
    <div class="card-body">
        <div class="">
            <?php echo trans('please.choose.open.graph.info', [['from' => '[createOpenGraphLink]', 'to' => $container->getUrl()->getHttpDomain().'/admin/openGraphs']]); ?>
        </div>
    </div>
</div>

<?php 
// dump($openGraphs);
    foreach ($openGraphs as $openGraph):
    // $imageFile = $openGraph->getMainImageFile('fullSize');
    // dump($imageFile);
    if ($openGraph->getOpenGraphImageHeader()):
        $imageFile = $openGraph->getMainImageFile('thumbnail_w120');
    // dump($imageFile);
?>
<div class="alert alert-dark alert-dark-hover tagFrame-fullWidth-auto customPageOpenGraph_input" id="customPageOpenGraph_input_<?php echo $openGraph->getId() ?>" data-opengraphid="<?php echo $openGraph->getId() ?>">
    <div class="">
        <div class="tag-ultraLight" style="cursor: pointer;">
            <div class="row">
                <div class="" style="width: 130px; float: left;">
                    <img src="/openGraph/image/<?php echo $imageFile->getFile()->getFileName().'.'.$imageFile->getFile()->getExtension(); ?>">
                </div>
                <div class="">
                    <table style="width: 100%;">
                        <tbody>
                            <tr>
                                <td id="customPageOpenGraph_input_<?php echo $openGraph->getId() ?>_title">
                                <b><?php echo $openGraph->getTitle(); ?></b>
                                </td>
                            </tr>
                            <tr>
                                <td id="customPageOpenGraph_input_<?php echo $openGraph->getId() ?>_description">
                                <?php echo strip_tags($openGraph->getDescription()); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
        endif;
    endforeach;
endif;
?>

<!-- <div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
    </div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="form-group">
            <button name="FrameworkPackage_customPageOpenGraph_submit" id="FrameworkPackage_customPageOpenGraph_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="CustomPageOpenGraph.submitForm();" value=""><?php echo trans('save'); ?></button>
        </div>
    </div>
</div> -->

</form>