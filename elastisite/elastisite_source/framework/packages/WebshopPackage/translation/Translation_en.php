<?php

namespace framework\packages\WebshopPackage\translation;

class Translation_en
{
    public function getTranslation()
    {
        return array(
            'last.viewed.products' => 'Last viewed products',
            'altogether' => 'Altogether',
            'cart' => 'Cart',
            'is.recommended' => 'Recommended product',
            'recommended.product' => 'Recommended product',
            // 'trigger.off.gross.limit' => 'Trigger off gross limit',
            'must.be.empty.if.automatic' => 'Must be empty, if the effect causing stuff is automatic.',
            'direction.of.change.is.not.permitted' => 'Direction of change contains inproper value',
            'effect.causing.stuff.is.not.permitted' => 'Effect causing stuff contains inproper value',
            'effect.operator.is.not.permitted' => 'Effect operator contains inproper value',
            'country.and.zip.effect.operator.can.only.be.equals.and.not.equals' => 'Country and zip type effect operators can only be "equals" and "not equals"',
            'price.effect.operator.can.only.be.less.and.more.than' => 'Price type effect operator can only be "less than" or "more than"',
            'invalid.alpha2.code' => 'Invalid alpha 2 code',
            'invalid.zip.code.mask' => 'Invalid zip code mask (only numbers and * characters are allowed, max digits: 4.)',
            'invalid.gross.total.price' => 'Invalid gross total price. Numbers only, please.',

            'direction.of.change' => 'Direction of change',
            'effect.causing.stuff' => 'Effect causing stuff',
            'effect.operator' => 'Effect operator',
            'effect.operator.description' => 'Effect operator: the Effect causing stuff is ... than the Effect causing value',
            'direction.of.change.apply' => 'Apply',
            'direction.of.change.discard' => 'Discard',
            'effect.causing.stuff.country.alpha2' => 'Country alpha2 code (e.g.: GB, HU, ES)',
            'effect.causing.stuff.zip.code.mask' => 'Zip code mask (e.g. 11**)',
            'effect.causing.stuff.gross.total.price' => 'Gross total price',
            'effect.causing.stuff.automatic' => 'Triggers automatically when anything has been put to the cart',
            'effect.causing.value' => 'Effect causing value',
            'equals' => 'Equals',
            'not.equals' => 'Not equals',
            'less.than' => 'Less than',
            'more.than' => 'More than',

            'create.new.cart.trigger' => 'Create new cart trigger',
            'edit.cart.trigger' => 'Edit cart trigger',
            'admin.webshop.cart.triggers' => 'Cart triggers',
            'special.purpose' => 'Special purpose',
            'delivery.fee' => 'Delivery fee',
            'gift' => 'Gift',
            'short.info' => 'Short info (default language)',
            'english.short.info' => 'English short info',
            'administrators.unfinished.orders.info' => 'You are currently logged in as an administrator at the moment.<br>
            Administrators have additional features here, but no possibility to finish a purchase. 
            If you want to test the webshop, please register a user, than log in as an administrator, go to the administrators area (cogwheel icon on the top-right corner), 
            than go to Users, edit your registered account, and promote that to "tester".',
            'ordered' => 'Ordered',
            'futile.attempt' => 'This was a futile attempt',
            'gross.price' => 'Gross price',
            'vat.cannot.differ.from.other.prices.of.this.product' => 'VAT cannot differ from other prices\' VAT of this product',
            'must.be.whole.number' => 'Must be a whole number',
            'gross.must.be.whole.number' => 'Gross price calculated from this net price must be a whole number',
            'paying.for.the.order' => 'Paying for the order',
            'i.pay' => 'I\'m paying',
            'user.type.anyone' => 'Anyone',
            'user.type.authenticated.user' => 'Authenticated user',
            'user.type.guest' => 'Guest',
            'permitted.user.type' => 'Permitted user type',
            'barion.cookie.consents' => 'Barion cookie consents',
            'szamlazz.hu.cookie.consents' => 'Szamlazz.hu cookie consents',
            'resetting.cookie.consents' => 'Resetting cookie consents',
            'unfinished.order' => 'Unfinished order',
            'selecting.payment.method' => 'Selecting payment method',
            'next.step' => 'Next step',
            'help.panel' => 'Help panel',
            'please.select.payment.method' => 'Please select payment method',
            'lets.handle.this.shipment' => 'Let\'s handle this order',
            'cancelled' => 'Cancelled',
            'prepared' => 'Prepared',
            'payment.method.selected' => 'Payment method selected',
            'unfinished.orders' => 'Unfinished orders',
            'order.in.progress' => 'Order in progress',
            'ordered.at' => 'Order date',
            'order.started.at' => 'Order started at',
            'missing.recipient.name' => 'Missing recipient name',
            'missing.contact.email' => 'Missing contact email',
            'missing.contact.mobile' => 'Missing contact mobile',
            'tax.id' => 'Tax ID',
            'search.has.no.results' => 'This search has no results',
            'contact.data' => 'Recipient\'s data',
            'contact.email' => 'Contact e-mail address',
            'contact.mobile' => 'Contact cellphone number',
            'delivery.information' => 'Delivery information',
            'save.delivery.information' => 'Save delivery information',
            'organization.address' => 'Address of the organization',
            'customer.organization' => 'Customer organization',
            'organizations' => 'List of organizations',
            'add.new.organization' => 'Add new organization',
            'edit.organization' => 'Edit organization',
            'please.select.customer.type' => 'Please select customer type',
            'please.select.organization' => 'Please select organization',
            'please.select.delivery.address' => 'Please select delivery address',
            'please.accept.terms.and.conditions' => 'Please accept the terms and conditions',
            'private.person' => 'Private person',
            'products.in.cart' => 'Products in the cart',
            'customer.type' => 'Customer type',
            'delivery.data' => 'Delivery data',
            'product.description' => 'Product description',
            'search.in.the.webshop' => 'Search in the webshop',
            'cart.updated' => 'Cart has been updated',
            'product.categories' => 'Product categories',
            'allow.cart.quantity' => 'Allow buying multiple quantity',
            'webshop.package' => 'Webshop package',
            'cancel.order.failed' => 'Cancel order failed',
            'cancel.order.successful' => 'Cancel order successful',
            'webshop' => 'Webshop',
            'product' => 'Product',
            'parent.product.category' => 'Parent category',
            'main.category' => 'Main category',
            'category' => 'Category',
            'product.category' => 'Product category',
            'product.code' => 'Product code',
            'webshop.administration' => 'Webshop administration',
            'admin.webshop.config' => 'Configuration',
            'admin.webshop.product.categories' => 'Product categories',
            'admin.webshop.products' => 'Products',
            'admin.webshop.storages' => 'Storages',
            'admin.webshop.inward.processing' => 'Inward processing',
            'admin.webshop.stock' => 'Stock',
            'admin.webshop.discounts' => 'Discounts',
            'admin.webshop.orders' => 'Orders',
            'admin.webshop.running.orders' => 'Running orders',
            'reset.webshop' => 'Reset webshop',
            'out.of.stock' => 'Out of stock',
            'discontinued' => 'Discontinued',
            // 'description' => 'Description',
            'english.name' => 'English name',
            'english.description' => 'English description',
            'net.price' => 'Net price',
            'short.note' => 'Short note',
            'vat' => 'VAT (%)',
            'vat2' => 'VAT',
            'total' => 'Total',
            'gross' => 'gross',
            'gross.piece.price' => 'gross piece price',
            'save.price' => 'Save price',
            'create.price' => 'Create price',
            'price.type' => 'Price type',
            'list.price' => 'List price',
            'discount.price' => 'Discount price',
            'set.as.active' => 'Set as active',
            'product.property.settings' => 'Properties',
            'product.price.settings' => 'Prices',
            'product.image.settings' => 'Images',
            'product.email.settings' => 'E-mails',
            'price' => 'Price',
            'activating' => 'Activating',
            'set.as.main' => 'Set as main',
            'main' => 'Primary',
            'setting.cart.item.quantity' => 'Setting quantity',
            'put.to.cart' => 'Put to cart',
            'cart.quantity' => 'Cart',
            'pcs.of' => 'pcs of',
            'not.in.cart' => 'Not in cart yet',
            'in.cart' => 'In cart',
            'per.piece' => '/piece',
            'checkout' => 'Checkout',
            'cart.content' => 'Content of the cart',
            'cart.is.empty' => 'Cart is empty',
            'product.added.to.cart' => 'Product added to cart',
            'product.removed.from.cart' => 'Product removed from cart',
            'missing.active.product.price' => 'Missing active product price',
            'missing.or.corrupted.product.price'  => 'Missing or currupted product price',
            'payment.method' => 'Payment method',
            'cash.on.delivery' => 'Cash on delivery',
            'delivery.address' => 'Delivery address',
            'recipient' => 'Recipient',
            'recipient.if.differs.from.order.maker' => 'Recipient (if differs from the customer\'s)',
            'recipient.or.customer' => 'Recipient / customer',
            'finish.checkout' => 'Finish checkout',
            'accepting.terms' => 'Accepting terms',
            'i.order' => 'I order',
            'order.agreement' => 'I declare that I have read the terms and conditions, and agree all of them',
            'add.new.delivery.address' => 'Add new delivery address',
            'edit.delivery.address' => 'Edit delivery address',
            'change.delivery.address' => 'Change delivery address',
            'select.delivery.address' => 'Select delivery address',
            'missing.delivery.address' => 'Missing delivery address',
            'cart.lost.error' => 'Cart is empty',
            'discounted.products' => 'Discounted products',
            'discounted.product' => 'Discounted product',
            'special.offers' => 'Special offers',
            'i.understood.the.risk.of.resetting.webshop' => 'I understood the risks of resetting the webshop',
            'reset.webshop.risks' => 'With resetting the webshop all information of all products and orders will be permanently deleted without the chance of restoring the lost data.',
            'my.orders' => 'My orders',


            'order.cancelled' => 'Order cancelled',
            'order.prepared' => 'Order prepared',
            'order.placed' => 'Order placed',
            'shipment.posted' => 'Shipment posted',
            'shipment.delivered' => 'Shipment delivered',

            'customer.is.unreachable' => 'Customer is unreachable',
            'waiting.for.product' => 'Waiting for product',
            'prepared.for.delivery' => 'Prepared for delivery',
            'courier.took.over' => 'Courier took over',

            // 'cancelled' => 'Cancelled',
            // 'ordered' => 'Ordered',
            // 'posted' => 'Posted',
            // 'delivered' => 'Delivered',

            'shipping.address' => 'Shipping address',
            'shipment.identifier' => 'Shipment identifier',
            'order.successful' => 'Order successful',
            'order.failed' => 'Order failed',
            'order.summary' => 'Order summary',
            'ordered.products' => 'Ordered products',
            'checkout.is.unavailable.with.this.user' => 'Checkout is unavailable with this user',
            'all.products' => 'All products',
            'all_products' => 'All products',
            'search.product' => 'Search product',
            'search.product.in.all.categories' => 'Search product - in all categories',
            'search.in.all.products' => 'Search in all products',
            'search.in.this.category' => 'Search in this category',
            'most.popular.products' => 'Most popular products',
            'most_popular_products' => 'Most popular products',
            'discounted.products' => 'Discounted products',
            'discounted_products' => 'Discounted products',
            'recommended.products' => 'Recommended products',
            'recommended_products' => 'Recommended products',
            'discount.price.must.be.less.than.list.price' => 'Discount price must be less than list price',
            'no.product.selected' => 'No product selected',
            'must.be.numeric' => 'This data must be numeric',
            'price.type.not.allowed' => 'Price type not allowed',
            'must.be.between.1.and.100' => 'Must be between 1 and 100',
            'list' => 'List',
            'discount' => 'Discnt.',
            'already.at.cart' => 'Already at cart',
            'return.to.webshop' => 'Return to webshop',
            'customer' => 'Customer',
            'email.send.error' => 'Valami probléma van az Ön által megadott e-mail címmel: <b>[email]</b>. Kérem, ellenőrizze, hogy nem történt-e elgépelés, és próbálja újra a fizetést.',
            'finalize.order' => 'Finalize order',
            'order.remove.successful' => 'Order removed successfully',
            'order.remove.fail' => 'Failed to remove order',
            'non.listable.products' => 'Non-listable (faulty) products',
            'demo.webshop.payment.method.info' => 'Cash on delivery is the basic payment method. With ordering a Webshop Package, the customer can choose one online payment service provider from the web developers\' list.',
            'information.about.initaion.of.a.webshop.order' => 'Information about initation of a webshop order',
            'product.code.not.required.info' => '"Code" field is not required. If you want to use is, all you have to do is to fill this field, and from that moment, "Code" will also appear in the search grid.',
            'product.pricing.rules' => '
                <i>Please read before pricing!</i><br>
                <b>Pricing rules:</b><br>
                <b>1.</b> The added price cannot be modified, just deleted.<br>
                <b>2.</b> If a price already exists in an order or in a cart, it cannot be deleted.<br>
                <b>3.</b> You can assing only 1 list price for a product.<br>
                <b>4.</b> You can assing as many discount prices for a product, as you like.<br>
                <b>5.</b> Until a list price not exists, a discount price cannot be added.<br>
                <b>6.</b> If a discount price exists, list price cannot be deleted.<br>
                <b>7.</b> If a product has ever been sold, the list price cannot be deleted anymore. (Even if it hadn\'t been sold on the list price ever.) This rule also a result of rules 2. and 6.<br>
                <b>8.</b> A product which in not priced will never show in the webshop of guests and users, just if a website operator is logged in. The website operator also sees the reason why it\'s not visible for the customers.<br>
                <b>9.</b> The discount price cannot be higher than the list price. If you need a new list price, you only can record the product again with the new list price, and set the status of the old product to inactive.<br>
                <b>10.</b> If you click on "Set to active" link of a price, and if the product is valid, than phe price will immediately be displayed in the webshop. 
                If you are not completely sure what are you doing, just start with the price changing process with setting the product to inactive. As a result, the product will not be present in the customers\' product list 
                until it will not be set to active again.',
            'no.notice' => 'No notice',
            'customer.notice' => 'Customer\'s notice',
            'admin.note' => 'Website operator\'s notice',
            'order.close.warning' => 'Warning! Once you set the order closed or deleted, all the non-registered personal data will be deleted. Only the country, the city and the zip code will remain, as they are not personal datas. 
                <br>Also: noone can reopen this order once it\'s closed.',
            'close.order' => 'Close order',
            'required' => 'Required',
            'order.time.price.changed' => 'The price at the time of ordering has changed',
            'actual.net.price' => 'Actual net price',
            'admin.webshop.statistics' => 'Statistics',
            'edit.order' => 'Edit order',
            'create.new.product' => 'Create new product',
            'edit.product' => 'Edit product',
            'create.new.product.category' => 'Create new product category',
            'edit.product.category' => 'Edit product category',
            'discounted' => 'discounted',
            'running.orders.info' => 'This page lists the first [displayedRunningOrders] pieces of unhandled orders. 
            The list will automatically refresh when a new order occurs, and there is room for the new one.',
            'homepage.list.type' => 'Webshop homepage listing type',
            'remove.temporary.person.on.close.shipment' => 'Remove personal data when closing order',
            'only.registrated.users.can.checkout' => 'Only registrated users can checkout',
            'closed.shipment.is.editable' => 'Closed order is editable',
            'reopen.shipment.is.allowed' => 'Reopen closed order is allowed',
            'displayed.running.orders' => 'Displayed number of running orders',
            'edit.webshop.settings' => 'Edit webshop settings',
            'max.products.on.page' => 'Max products displayedon a page',
            'product.list.max.cols' => 'Max column number of listed products',
            'remove.cart.on.login' => 'Remove cart on login',
            'webshop.config.advices' => 'Few advices about the settings:<br>
            <br>
            "Remove cart on login" is better to be set "false" when the "Only registrated users can checkout" is set to "true".<br>
            <br>
            "Remove personal data when closing order" have to be in an accurate synchron with your Privacy Statement. 
            ',
            'webshop.checkout.user.registration.title' => 'Checkout - Registration required',
            'webshop.checkout.user.registration.info' => 'Registration required for the checkout. If you already have an account, please log in. 
            If you don\'t have one, please fill the form below. 
            During the registration we will require some personal datas, to get information what will we do with those, please read our Privacy Policy. ',
            'payment.result' => 'Payment result',
            'payment.successful' => 'Payment successful',
            'payment.failed' => 'Payment failed',
            'payment.error' => 'Payment error',
            'site.operation.details' => 'Site operation',
            'test.webshop' => 'TEST webshop',
            'test.webshop.warning' => 'Warning!!! This webshop serves only TEST purposes, we could say, it\'s only a sandbox, a toy. 
            Any product you buy here will not arrive to you in the real life.',
            'please.add.false.name' => 'This is a test webshop, and the ordered products will never be delivered. Please add a false name, because we don\'t want to store unnecessary personal data.',
            'please.add.real.email.to.recieve.confirmation' => 'If you want to test if confirmation arrives, please add your real e-mail address. This e-mail address will be never used for marketing or other purposes.',
            'trigger.corporate' => 'Choose corporate order',
            'trigger.corporate.description' => 'If you check this checkbox, your order will be corporate. In that case you also have to fill the corporate data.',
            'corporate.order.data' => 'Corporate order',
            'you.have.a.started.payment' => 'You already have a started payment. You can continue that clicking here:',
            'if.you.want.to.cancel.payment' => 'If you want to cancel payment, you can do that on the payment provider\'s interface. You can navigate there by clicking the link above.',
            'invalid.hungarian.taxid' => 'Invalid Hungarian tax number',
            'corporate.customer' => 'Corporate customer',
            'webshop.is.active' => 'Webshop is active',
            'webshop.is.inactive.info' => 'The webshop is temporary unavailable.',
            'independent.from.webshop' => 'Webshop-independent',
            'is.independent' => 'Webshop-independent',
            'no.displayable.items' => 'No displayable item'
        );
    }
}
