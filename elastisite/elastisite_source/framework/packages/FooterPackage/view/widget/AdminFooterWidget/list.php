<?php  
// $headers = [
//     trans('appellation'),
//     trans('value')
// ];
// 'FooterPackage_facebookLink' => null,
// 'FooterPackage_twitterLink' => null,
// 'FooterPackage_googleLink' => null,
// 'FooterPackage_instagramLink' => null,
// 'FooterPackage_linkedinLink' => null,
// 'FooterPackage_githubLink' => null
$tableData = [
    [
        'apellation' => trans('own.website.link'),
        'value' => $settings['FooterPackage_ownWebsiteLink']
    ],
    [
        'apellation' => trans('own.website.name'),
        'value' => $settings['FooterPackage_ownWebsiteName']
    ],
    [
        'apellation' => trans('facebook.link'),
        'value' => $settings['FooterPackage_facebookLink']
    ],
    [
        'apellation' => trans('twitter.link'),
        'value' => $settings['FooterPackage_twitterLink']
    ],
    [
        'apellation' => trans('google.link'),
        'value' => $settings['FooterPackage_googleLink']
    ],
    [
        'apellation' => trans('instagram.link'),
        'value' => $settings['FooterPackage_instagramLink']
    ],
    [
        'apellation' => trans('linkedin.link'),
        'value' => $settings['FooterPackage_linkedinLink']
    ],
    [
        'apellation' => trans('github.link'),
        'value' => $settings['FooterPackage_githubLink']
    ]
];

?>

<div class="card table-card">
    <div class="pro-scroll">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-b-0">
                    <thead>
                        <tr>
                            <th><?php echo trans('appellation'); ?></th>
                            <th><?php echo trans('value'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tableData as $tableDataRow): ?>
                        <tr>
                            <td><?php echo $tableDataRow['apellation']; ?></td>
                            <td><?php echo $tableDataRow['value']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
