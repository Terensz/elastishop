<?php

\Utils::doLog(LOG_INFO, sprintf('Borgun payment error. REQUEST: %s', \Utils::dumpVar($_REQUEST)));

$orders_id = intval($r_orderid);
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

