<?php
namespace framework\packages\FinancePackage\menu;

class AdminMenuSection
{
    public function getConfig()
    {
        return [
            'title' => 'finance.administration',
            'items' => [
                [
                    'routeName' => 'admin_finance_invoices',
                    'paramChain' => 'admin/finance/invoices',
                    'title' => 'invoices'
                ],
                [
                    'routeName' => 'admin_finance_vatDeclarationTest',
                    'paramChain' => 'admin/finance/vatDeclarationTest',
                    'title' => 'vat.declaration.test'
                ]
            ]
        ];
    }
}