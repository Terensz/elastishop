<?php

use framework\packages\FrameworkPackage\service\BasicConstants;
use framework\packages\UXPackage\service\ViewTools;

$configItems = [
    'FooterPackage_ownWebsiteLink' => [
        'label' => trans('own.website.link'),
        'type' => 'text',
        'options' => null
    ],
    'FooterPackage_ownWebsiteName' => [
        'label' => trans('own.website.name'),
        'type' => 'text',
        'options' => null
    ],
    'FooterPackage_facebookLink' => [
        'label' => trans('facebook.link'),
        'type' => 'text',
        'options' => null
    ],
    'FooterPackage_twitterLink' => [
        'label' => trans('twitter.link'),
        'type' => 'text',
        'options' => null
    ],
    'FooterPackage_googleLink' => [
        'label' => trans('google.link'),
        'type' => 'text',
        'options' => null
    ],
    'FooterPackage_instagramLink' => [
        'label' => trans('instagram.link'),
        'type' => 'text',
        'options' => null
    ],
    'FooterPackage_linkedinLink' => [
        'label' => trans('linkedin.link'),
        'type' => 'text',
        'options' => null
    ],
    'FooterPackage_githubLink' => [
        'label' => trans('github.link'),
        'type' => 'text',
        'options' => null
    ],
];
// dump($settings);
?>


<form name="FooterPackage_editConfig_form" id="FooterPackage_editConfig_form" method="POST" action="" enctype="multipart/form-data">

<?php 
foreach ($configItems as $requestKey => $configRow):
    $options = [];
    $displayedValue = '';
    if ($configRow['type'] == 'select') {
        foreach ($configRow['options'] as $optionKey => $rawOptions) {
            // dump($rawOptions);exit;
            if (!isset($rawOptions['rawValue'])) {
                dump($rawOptions);
            }
            $displayedOptionValue = $rawOptions['translateDisplayedValue'] ? trans($rawOptions['rawValue']) : $rawOptions['rawValue'];
            $options[$optionKey] = [
                'translateDisplayedValue' => $rawOptions['translateDisplayedValue'],
                'optionKey' => $rawOptions['optionKey'],
                'displayedValue' => $displayedOptionValue,
                'selected' => $settings[$requestKey] == $displayedOptionValue
            ];
        }
    }
    if ($configRow['type'] == 'text') {
        // if ()
        $displayedValue = $settings[$requestKey];
    }
    ViewTools::displayComponent('dashkit/inputs/'.$configRow['type'], [
        'requestKey' => $requestKey,
        'displayedValue' => $displayedValue,
        'label' => $configRow['label'],
        'options' => $options,
        'isInvalidClassString' => '' // Other situation: ' is-invalid' 
    ]);
endforeach;
?>
</form>

<div class="mb-3">
    <button name="FooterPackage_editConfig_submit" id="FooterPackage_editConfig_submit" type="button" class="btn btn-primary" onclick="AdminFooterConfig.edit(event, true);" value="">
        <?php echo trans('save'); ?>
    </button>
</div>