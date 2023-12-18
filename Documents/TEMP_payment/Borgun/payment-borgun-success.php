<?php
/**
 * Borgun success oldal a vásárlónak.
 * A sikeres fizetés rögzítését a "payment-borgun-callback.php" végzi el!
 * 
 */


\Utils::doLog(LOG_INFO, sprintf('Borgun payment SUCCESS. REQUEST: %s', \Utils::dumpVar($_REQUEST)));

$orders_id = intval($r_orderid);

if (! $orders_id)
{
  header('Location: /');
  exit;
}


$oO = new Orders($orders_id);

if (! $oO->is_valid())
{
  \Utils::addNotification(new Notification(_('Borgun error'), _('Order Number invalid! Payment FAILED!'), Notification::TYPE_ERROR) );
  
}

if ( ! Utils::isErrorNotif())
{
  // Fizetés feldolgozása.
  $oP = new \Borgun\Payment( $oO );
  $result = $oP->callback( null, $_REQUEST );
}

