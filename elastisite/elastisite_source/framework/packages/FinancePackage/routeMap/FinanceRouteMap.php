<?php
namespace framework\packages\FinancePackage\routeMap;

class FinanceRouteMap
{
    public static function get()
    {
        return array(
            /**
             * Invoices
            */
            array(
                'name' => 'admin_finance_invoices',
                'paramChains' => array(
                    'admin/finance/invoices' => 'default'
                ),
                'controller' => 'framework/packages/FinancePackage/controller/FinanceController',
                'action' => 'basicAdminAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'invoices',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'FinancePackage/view/widget/AdminFinanceInvoicesWidget'
                )
            ),
            array(
                'name' => 'admin_finance_invoicesWidget',
                'paramChains' => array(
                    'admin/financeInvoicesWidget' => 'default'
                ),
                'controller' => 'framework/packages/FinancePackage/controller/FinanceWidgetController',
                'action' => 'adminFinanceInvoicesWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_finance_editInvoice',
                'paramChains' => array(
                    'admin/finance/editInvoice' => 'default'
                ),
                'controller' => 'framework/packages/FinancePackage/controller/FinanceWidgetController',
                'action' => 'adminFinanceEditInvoiceAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_finance_createCreditNote',
                'paramChains' => array(
                    'admin/finance/createCreditNote/{invoiceHeaderId}' => 'default'
                ),
                'controller' => 'framework/packages/FinancePackage/controller/FinanceWidgetController',
                'action' => 'adminFinanceCreateCreditNoteAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_finance_downloadInvoice',
                'paramChains' => array(
                    'admin/finance/downloadInvoice/{invoiceHeaderId}' => 'default'
                ),
                'controller' => 'framework/packages/FinancePackage/controller/FinanceController',
                'action' => 'adminFinanceDownloadInvoiceAction',
                'permission' => 'viewProjectAdminContent'
            ),


            /**
             * VAT declaration test
            */
            array(
                'name' => 'admin_finance_vatDeclarationTest',
                'paramChains' => array(
                    'admin/finance/vatDeclarationTest' => 'default'
                ),
                'controller' => 'framework/packages/FinancePackage/controller/FinanceController',
                'action' => 'basicAdminAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'vat.declaration.test',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'FinancePackage/view/widget/AdminVatDeclarationTestWidget'
                )
            ),
            array(
                'name' => 'admin_finance_vatDeclarationTestWidget',
                'paramChains' => array(
                    'admin/vatDeclarationTestWidget' => 'default'
                ),
                'controller' => 'framework/packages/FinancePackage/controller/FinanceWidgetController',
                'action' => 'adminVatDeclarationTestWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
        );
    }
}
