<?php
/**
 * Ezt hívja vissza a Borgun szervere out-bound hívással (server-to-server)
 * Itt történik sikeres fizetés esetén a rendelés kiegyenlítése.
 */


\Utils::doLog(LOG_INFO, sprintf('Borgun payment callback. REQUEST: %s', \Utils::dumpVar($_REQUEST)));

$orders_id = intval($r_orderid);
$oO = new Orders($orders_id);

if (! $oO->is_valid())
{
  \Utils::addNotification(new Notification(_('Borgun error'), _('Order Number invalid! Payment FAILED!'), Notification::TYPE_ERROR) );
  
}
// file_put_contents('/tmp/1.log', $oO->id, FILE_APPEND);
//file_put_contents('/tmp/1.log', \Utils::dumpVar($notifications), FILE_APPEND);

if ( ! \Utils::isErrorNotif())
{
  
  $db->begin();
  // Fizetés feldolgozása.
  $oP = new \Borgun\Payment( $oO );
  $r = $oP->callback( null, $_REQUEST );

  // Rendelést frissíteni.
  if ($r === true)
  {
    // Fizetés mail kiküldése
    if (! $oO->date_payment )
    {
      $oO->addPayment($r_amount, $oCL->getIDByName($r_currency), PaymentType::$CARD_CODE, sprintf('Borgun payment. AUTHCODE: %s', $_REQUEST['authorizationcode'], 'borgun_callback', $oP->callback_id));
      $oO->sendOrderEmail();
    }
  }
  $db->commit();
  
}

exit;