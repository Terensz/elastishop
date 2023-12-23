<?php

$adminMenuItems = array();

// $adminMenuItems[] = [
//     'title' => 'navigation',
//     'items' => [        
//         [
//             'routeName' => 'homepage',
//             'paramChain' => '',
//             'title' => 'return.to.homepage'
//         ]
//     ]
// ];

if ($viewSystemAdminContentGranted) {
    $index = count($adminMenuItems);
    $adminMenuItems[$index] = [
        'title' => 'developers.tools',
        'permissionRequired' => 'viewSystemAdminContent',
        'items' => [
            [
                'routeName' => 'admin_database_info',
                'paramChain' => 'admin/database/info',
                'title' => 'database.info'
            ]
        ]
    ];
    
    if ($isWebshopPackageInstalled) {
        $adminMenuItems[$index]['items'][] = [
            'routeName' => 'admin_webshop_reset',
            'paramChain' => 'admin/webshop/reset',
            'title' => 'reset.webshop'
        ];
    }

    // $adminMenuItems[$index]['items'][] = [
    //     'routeName' => 'admin_mailer_test',
    //     'paramChain' => 'admin/mailer/test',
    //     'title' => 'admin.mailer.test'
    // ];
    
    // $adminMenuItems[$index]['items'][] = [
    //     'routeName' => 'down_listener',
    //     'paramChain' => 'admin/downListener',
    //     'title' => 'admin.down.listener'
    // ];

    $adminMenuItems[$index]['items'][] = [
        'routeName' => 'admin_intrusionAttempts',
        'paramChain' => 'admin/intrusionAttempts',
        'title' => 'intrusion.attempts'
    ];
}

// $adminMenuItems[] = [
//     'title' => 'documentation',
//     'items' => [
//         [
//             'routeName' => 'admin_favicon',
//             'paramChain' => 'admin/favicon',
//             'title' => 'documentation'
//         ],
//         [
//             'routeName' => 'admin_openGraph',
//             'paramChain' => 'admin/openGraph',
//             'title' => 'admin.open.graph'
//         ]
//     ]
// ];

$adminMenuItems[] = [
    'title' => 'appearance.administration',
    'items' => [
        [
            'routeName' => 'admin_favicon',
            'paramChain' => 'admin/favicon',
            'title' => 'admin.favicon'
        ],
        [
            'routeName' => 'admin_openGraphs',
            'paramChain' => 'admin/openGraphs',
            'title' => 'admin.open.graph'
        ],
        // [
        //     'routeName' => 'admin_videos',
        //     'paramChain' => 'admin/videos',
        //     'title' => 'admin.videos'
        // ],
        // [
        //     'routeName' => 'admin_backgrounds',
        //     'paramChain' => 'admin/backgrounds',
        //     'title' => 'admin.list.backgrounds'
        // ],
        [
            'routeName' => 'admin_footer',
            'paramChain' => 'admin/footer',
            'title' => 'admin.footer'
        ]
    ]
];

$adminMenuItems[] = [
    'title' => 'wording',
    'items' => [        
        // [
        //     'routeName' => 'admin_wordExplanation',
        //     'paramChain' => 'admin/wordExplanation',
        //     'title' => 'admin.word.explanation'
        // ],
        [
            'routeName' => 'admin_emailContentTexts',
            'paramChain' => 'admin/emailContentTexts',
            'title' => 'admin.email.contents'
        ],
        [
            'routeName' => 'admin_entryContentTexts',
            'paramChain' => 'admin/entryContentTexts',
            'title' => 'admin.entry.contents'
        ],
        [
            'routeName' => 'admin_articleContentTexts',
            'paramChain' => 'admin/articleContentTexts',
            'title' => 'admin.article.contents'
        ]
    ]
];

$adminMenuItems[] = [
    'title' => 'page.administration',
    'items' => [
        [
            'routeName' => 'admin_customPages',
            'paramChain' => 'admin/customPages',
            'title' => 'custom.pages'
        ],
        // [
        //     'routeName' => 'admin_handleVideos',
        //     'paramChain' => 'admin/handleVideos',
        //     'title' => 'admin.handle.videos'
        // ]
        // [
        //     'routeName' => 'admin_background_bindings',
        //     'paramChain' => 'admin/background/bindings',
        //     'title' => 'admin.background.bindings'
        // ]
        // [
        //     'routeName' => 'admin_keywords',
        //     'paramChain' => 'admin/keywords',
        //     'title' => 'admin.keywords'
        // ]
        // [
        //     'routeName' => 'admin_mails',
        //     'paramChain' => 'admin/mails',
        //     'title' => 'admin.mails'
        // ]
    ]
];

// $adminMenuItems[] = [
//     'title' => 'pages',
//     'items' => [
//         [
//             'routeName' => 'admin_pages',
//             'paramChain' => 'admin/pages',
//             'title' => 'admin.pages'
//         ],
//         [
//             'routeName' => 'admin_openGraphs',
//             'paramChain' => 'admin/openGraphs',
//             'title' => 'admin.open.graph'
//         ],
//         [
//             'routeName' => 'admin_keywords',
//             'paramChain' => 'admin/keywords',
//             'title' => 'admin.keywords'
//         ]
//     ]
// ];


$adminMenuItems[] = [
    'title' => 'users.and.security',
    'items' => [
        [
            'routeName' => 'admin_FBSUsers',
            'paramChain' => 'admin/FBSUsers',
            'title' => 'admin.admins.menuitem'
        ],
        [
            'routeName' => 'admin_userAccounts',
            'paramChain' => 'admin/userAccounts',
            'title' => 'admin.users.menuitem'
        ]
    ]
];

// $adminMenuItems[] = [
//     'title' => 'word.explanations',
//     'items' => [
//         // [
//         //     'routeName' => 'document_wordExplanation',
//         //     'paramChain' => 'document/wordExplanation',
//         //     'title' => 'document.word.explanations'
//         // ],
//         [
//             'routeName' => 'admin_wordExplanation',
//             'paramChain' => 'admin/wordExplanation',
//             'title' => 'admin.word.explanation'
//         ]
//     ]
// ];

// [
//     'title' => 'appearance.administration',
//     'items' => [
//         // [
//         //     'routeName' => 'admin_appearance_backgrounds',
//         //     'paramChain' => 'admin/appearance/backgrounds',
//         //     'title' => 'admin.backgrounds'
//         // ],
//         [
//             'routeName' => 'admin_appearance_skins',
//             'paramChain' => 'admin/appearance/skins',
//             'title' => 'admin.appearance.skins'
//         ]
//     ]
// ],

// $adminMenuItems[] = [
//     'title' => 'background.administration',
//     'items' => [
//         [
//             'routeName' => 'admin_backgrounds',
//             'paramChain' => 'admin/backgrounds',
//             'title' => 'admin.handle.backgrounds'
//         ],
//         [
//             'routeName' => 'admin_background_bindings',
//             'paramChain' => 'admin/background/bindings',
//             'title' => 'admin.background.bindings'
//         ]
//     ]
// ];

$adminMenuItems[] = [
    'title' => 'site.statistics',
    'items' => [
        [
            'routeName' => 'admin_visitsAndPageLoads',
            'paramChain' => 'admin/visitsAndPageLoads',
            'title' => 'visits.and.page.loads'
        ],
        [
            'routeName' => 'admin_mostUsedKeywords',
            'paramChain' => 'admin/mostUsedKeywords',
            'title' => 'most.used.keywords'
        ]
    ]
];


// [
//     'title' => 'uploads.administration',
//     'items' => [
//         [
//             'routeName' => 'admin_uploads_images',
//             'paramChain' => 'admin/uploads/images',
//             'title' => 'admin.uploads.images'
//         ],
//         [
//             'routeName' => 'admin_uploads_attachments',
//             'paramChain' => 'admin/uploads/files',
//             'title' => 'admin.uploads.files'
//         ]
//     ]
// ]
foreach ($adminMenuSections as $adminMenuSection) {
    $adminMenuItems[] = $adminMenuSection;
}

// $adminMenuItems[] = [
//     'title' => 'calendar.administration',
//     'items' => [
//         [
//             'routeName' => 'admin_events',
//             'paramChain' => 'admin/events',
//             'title' => 'admin.edit.events'
//         ]
//     ]
// ];
?>
<ul class="pc-navbar sideBar-container">
            <!--
                            
                <li class="pc-item pc-caption">
                    <label><?php echo trans('documentation'); ?></label>
                </li>

                <li class="pc-item active">
                    <a href="http://elastishop/admin/database/info" class="ajaxCallerLink pc-link ">
                        <span class="pc-mtext">
                            Entitás-info                        </span>
                    </a>
                    <div class="show" style="padding-left: 20px;">
                        <ul class="nav nav-sm flex-column">
                          <li class="nav-item">
                            <a href="./account-general.html" class="nav-link ">
                              General
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="./account-billing.html" class="nav-link ">
                              Billing
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="./account-members.html" class="nav-link ">
                              Members
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="./account-security.html" class="nav-link ">
                              Security
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="./account-notifications.html" class="nav-link ">
                              Notifications
                            </a>
                          </li>
                        </ul>
                      </div>

                </li> -->

<?php
for ($i=0; $i < count($adminMenuItems); $i++) {
    if (isset($adminMenuItems[$i]['permissionRequired']) && !App::getContainer()->isGranted($adminMenuItems[$i]['permissionRequired'])) {
        continue;
    }
?>
                <li class="pc-item pc-caption">
                    <label><?php echo trans($adminMenuItems[$i]['title']); ?></label>
                </li>
    <?php
    foreach ($adminMenuItems[$i]['items'] as $submenuItem):
        $permission = App::getContainer()->getRoutingHelper()->searchRoute($submenuItem['paramChain'])->getPermission();
        if (App::getContainer()->isGranted($permission)):
            $liClassAddStr = '';
            if (App::getContainer()->getUrl()->getParamChain() == $submenuItem['paramChain']) {
                $liClassAddStr = ' active';
            }
    ?>
                <li class="pc-item<?php echo $liClassAddStr; ?>">
                    <a href="<?php echo App::getContainer()->getRoutingHelper()->getLink($submenuItem['routeName']); ?>" class="ajaxCallerLink pc-link ">
                        <span class="nav-link-icon">
                            <img src="/public_folder/plugin/Bootstrap-icons/Dashkit-light/<?php echo 'emoji-neutral'; ?>.svg">
                        </span>
                        <span class="pc-mtext">
                            <?php echo trans($submenuItem['title']); ?>
                        </span>
                    </a>
                </li>
    <?php
        endif;
    endforeach;
    ?>
<?php
}
?>

            </ul>
<?php 
// dump($adminMenuItems);//exit;
// dump('helo');
?>
<style>
/* .admin-sideNavbar-container {
    overflow-y: auto;
} */
.admin-sideNavbar-scroll {
    height: 90vh;
    overflow-y: scroll;
    background: linear-gradient(180deg, transparent, #fff 60%, #fff);
}
</style>
