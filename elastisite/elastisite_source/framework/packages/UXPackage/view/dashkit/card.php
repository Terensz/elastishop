<?php 
if (!isset($viewData)) {
    throw new \Exception('viewData is not defined');
}

// dump($viewData);
/* Example
$viewData = [
    'title' => 'Title',
    'body' => 'Body',
    'displayEditIcon' => true,
    'displayDeleteIcon' => true,
    'editIconSrc' => '/public_folder/plugin/Bootstrap-icons/edit.svg',
    'deleteIconSrc' => '/public_folder/plugin/Bootstrap-icons/edit.svg',
    'additionalCardClassString => ''
];
*/

/**
 * Setting default values
*/
if (!isset($viewData['additionalCardClassString'])) {
    $viewData['additionalCardClassString'] = '';
}
if (!isset($viewData['additionalCardHeaderClassString'])) {
    $viewData['additionalCardHeaderClassString'] = '';
}
if (!isset($viewData['title'])) {
    $viewData['title'] = '[title] is not defined';
}
if (!isset($viewData['titleLink'])) {
    $viewData['titleLink'] = '';
}
if (!isset($viewData['body'])) {
    $viewData['body'] = '[body] is not defined';
}
if (!isset($viewData['displayEditIcon'])) {
    $viewData['displayEditIcon'] = true;
}
if (!isset($viewData['displayDeleteIcon'])) {
    $viewData['displayDeleteIcon'] = true;
}
if (!isset($viewData['editIconSrc'])) {
    $viewData['editIconSrc'] = '/public_folder/plugin/Bootstrap-icons/edit.svg';
}
if (!isset($viewData['editOnClick'])) {
    $viewData['editOnClick'] = '';
}
if (!isset($viewData['deleteIconSrc'])) {
    $viewData['deleteIconSrc'] = '/public_folder/plugin/Bootstrap-icons/x.svg';
}
if (!isset($viewData['deleteOnClick'])) {
    $viewData['deleteOnClick'] = '';
}
if (!isset($viewData['footerImageSources'])) {
    $viewData['footerImageSources'] = [];
}
if (!isset($viewData['displayFooter']) || count($viewData['footerImageSources']) == 0) {
    $viewData['displayFooter'] = false;
}
$attributesString = '';
if (isset($viewData['attributes']) && is_array($viewData['attributes'])) {
    foreach ($viewData['attributes'] as $attributeName => $attributeValue) {
        $attributesString .= ' '.$attributeName.'="'.$attributeValue.'"';
    }
}

$containerAttributesString = '';
$wrapInContainer = false;
if (isset($viewData['containerAttributes']) && is_array($viewData['containerAttributes'])) {
    $wrapInContainer = true;
    foreach ($viewData['containerAttributes'] as $attributeName => $attributeValue) {
        $containerAttributesString .= ' '.$attributeName.'="'.$attributeValue.'"';
    }
}
?>
<?php if ($wrapInContainer): ?>
<div <?php echo $containerAttributesString; ?>>
<?php endif; ?>
    <div class="card<?php echo empty($viewData['additionalCardClassString']) ? '' : ' '.$viewData['additionalCardClassString']; ?>"<?php echo $attributesString; ?>>
        <div class="<?php echo empty($viewData['additionalCardHeaderClassString']) ? '' : $viewData['additionalCardHeaderClassString'].' '; ?>card-header d-flex justify-content-between align-items-center">
            <?php if (!empty($viewData['editOnClick']) && !empty($viewData['editIconSrc'])): ?>
            <div class="card-header-iconContainer-left" style="line-height: 1;">
                <a class="" href="" onclick="<?php echo $viewData['editOnClick']; ?>">
                    <img src="<?php echo $viewData['editIconSrc']; ?>">
                </a>
                <!-- <img src="<?php echo $viewData['editIconSrc']; ?>"> -->
            </div>
            <?php endif; ?>
            <div class="card-header-textContainer">
                <h6 class="mb-0">
                    <?php if (!empty($viewData['titleLink'])): ?>
                    <a class="link-underlined ajaxCallerLink <?php echo empty($viewData['additionalCardHeaderLinkClassString']) ? '' : ' '.$viewData['additionalCardHeaderLinkClassString']; ?>" href="<?php echo $viewData['titleLink']; ?>">
                        <?php echo $viewData['title']; ?>
                    </a>
                    <?php else: ?>
                        <?php echo $viewData['title']; ?>
                    <?php endif; ?>
                </h6>
            </div>
            <?php if (!empty($viewData['deleteOnClick']) && !empty($viewData['deleteIconSrc'])): ?>
            <div class="card-header-iconContainer-right" style="line-height: 1;">
                <a href="" onclick="<?php echo $viewData['deleteOnClick']; ?>">
                    <img src="<?php echo $viewData['deleteIconSrc']; ?>">
                </a>
            </div>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <span>
                <?php echo $viewData['body']; ?>
            </span>
        </div>
        <?php if ($viewData['displayFooter']): ?>
        <div class="card-footer">
            <?php foreach ($viewData['footerImageSources'] as $footerImageSource): ?>
                <img class="card-footer-img" src="<?php echo $footerImageSource; ?>">
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
<?php if ($wrapInContainer): ?>
</div>
<?php endif; ?>