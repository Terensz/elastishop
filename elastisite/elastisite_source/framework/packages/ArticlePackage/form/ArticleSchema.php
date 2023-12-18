<?php
namespace framework\packages\ArticlePackage\form;

use framework\component\parent\FormSchema;

class ArticleSchema extends FormSchema
{
    public function getEntitySpecs()
    {
        return [
            'primaryEntity:ArticlePackage/entity/Article' => [
                'id' => array(
                    'technical' => true,
                    'primaryKey' => true
                ),
                'title' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                // 'hardCodedOverTeaser' => array(
                //     'validatorRules' => array(
                //         'required' => false
                //     ),
                //     'type' => 'select'
                // ),
                'teaser' => array(
                    'validatorRules' => array(
                        'required' => true
                    )
                ),
                'teaserType' => array(
                    'validatorRules' => array(
                        'required' => false
                    ),
                    'type' => 'select'
                ),
                'hardCodedSlug' => array(
                    'validatorRules' => array(
                        'required' => false
                    ),
                    'type' => 'select'
                ),
                'body' => array(
                    'validatorRules' => array(
                        'required' => false
                    )
                )
            ]
        ];
    }

    public function getSchemaConfig()
    {
        return array(
            'dataRepository' => 'ArticleRepository',
            'selectDataMethod' => 'getArticleSchemaData',
            'storeDataMethod' => 'storeArticleSchemaData'
        );
    }

    // public function getEntityConfig()
    // {
    //     return array(
    //         'Article' => array(
    //             // 'primaryKey' => 'id',
    //             'entityPath' => 'framework/packages/ArticlePackage/entity/Article',
    //             'repositoryPath' => 'framework/packages/ArticlePackage/repository/ArticleRepository'
    //         )
    //     );
    // }
}
