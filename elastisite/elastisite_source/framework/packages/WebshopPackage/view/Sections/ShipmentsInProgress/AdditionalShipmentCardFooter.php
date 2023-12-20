<?php

use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\service\ShipmentService;

App::getContainer()->wireService('WebshopPackage/entity/Shipment');
App::getContainer()->wireService('WebshopPackage/service/ShipmentService');

?>
<?php if ($packDataSet['pack']['permittedForCurrentUser'] && in_array($packDataSet['pack']['status'], Shipment::STATUS_COLLECTION_SHIPMENTS_HANDLING_PROMPTED_TO_USER)): ?>
<div class="card-footer">
    <a class="ajaxCallerLink" href="/webshop/shipment/handling/<?php echo $shipmentCode; ?>"><?php echo trans('lets.handle.this.shipment'); ?></a>
</div>
<?php else: ?>
    <?php if ($packDataSet['pack']['permittedUserType'] == ShipmentService::PERMITTED_USER_TYPE_GUEST): ?>
    <div class="card-footer">
        Ez a megrendelés regisztráció nélkül került rögzítésre, így azért, hogy az Ön anonimitását meg tudja őrizni, nem kapcsolódik hozzá felhasználói fiók. A megrendelés folytatására két lehetősége van:<br>
        1.: <a class="" href="/logout">Kijelentkezik</a> a programból, így tudja folytatni a regisztráció nélküli megrendlést. A regisztráció nélküli megrendelések a böngésző munkamenetéhez kapcsolódnak, 
        így pl. az "Előzmények törlése" funkció használata után már nem lesznek többé elérhetőek.<br>
        2.: <a class="" href="" onclick="Webshop.bindShipmentToAccount(event, '<?php echo $packDataSet['pack']['code']; ?>')">A megrendeléshez hozzákapcsoljuk ezt a felhasználói fiókot</a>, amivel épp be van jelentkezve, ám ebben az esetben kijelentkezve soha többé nem lesz elérhető a megrendelése.<br>
    </div>
    <?php else: ?>
        <?php  
        /**
         * This should never happen.
         */    
        ?>
    <?php endif; ?>
<?php endif; ?>