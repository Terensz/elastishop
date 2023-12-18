<?php
namespace Borgun;


/**
 * Használati példák:
 * FIZETÉS
 * $oOrder = (new Order(12, "EUR")) // CustomerID és Currency
 *    ->add_item(new OrderItem())   // Két rendelési tétel felvitele (method-chain)
 *    ->add_item(new OrderItem());
 * // Fizetési API objektum létrehozása a rendeléshez
 * $oPayment  = new Payment($oOrder);
 * // Esetleges opcionális paraméterek
 * $oPayment->set_buyername($oCustomer->name)
 *      ->set_buyeremail($oCustomer->email)
 *      ->set_reference($my_reference);
 * // Checkout
 * $oPayment->checkout();
 *
 * CALLBACK KEZELÉS
 * if (Order::valid_order($_POST['orderid']))
 * {
 *    $result = (new Payment(new Order($_POST['orderid'])))->callback();
 * }
 *
 * Reference:
 * https://docs.borgun.is/hostedpayments/securepay/
 *
 * Kártyaszám: 4741 5200 0000 0003
 * CVC: 000
 *
 */
class Payment
{
  // Globális konfig és adatbázis elemek priváttá tétele
  private
    $config,
    $db;

  // Az aktuálisan összeállított rendelés objektum
  private $oOrder;

  // A checkout metódus
  private $checkout_method;

  // A checkout adatai
  private $checkout_data;

  // A checkout eredménye
  private $checkout_result;

  // Érvényes pénznemek
  public static $currencies  = array('GBP', 'USD', 'EUR', 'DKK', 'NOK', 'SEK', 'CHF',
    'CAD', 'HUF', 'BHD', 'AUD', 'RUB', 'PLN', 'RON', 'HRK', 'CZK', 'ISK');

  // Érvényes nylevek
  public static $languages   = array('IS', 'EN', 'DE', 'FR', 'RU', 'ES', 'IT', 'PT',
    'SI', 'HU', 'SE', 'NL', 'PL', 'NO', 'CZ', 'SK', 'HR', 'SR','RO', 'DK', 'FI', 'FO');


  /**
   * Alapértelmezett konstruktor.
   * @global array $cfg Configurations
   * @global PDO database $db PDO database object
   * @param \Borgun\iOrder $oOrder Rendelés objektum.
   */
  public function __construct(\Orders $oOrder)
  {
    global $cfg, $db;
    
    \Utils::doLog(LOG_DEBUG, sprintf('%s', __METHOD__));

    // Globálisok egységbe zárása
    $this->config =& $cfg;
    $this->db     =& $db;

    $this->checkout_method  = '_post_curl';
    $this->oOrder = $oOrder;

    $this->_initialize_checkoutdata();
  }


  
  private function _initialize_checkoutdata()
  {
    global $oCL, $oL;
    
    // Kötelező paraméterek
    $this->checkout_data  = array(
      'merchantid'        => $this->config['borgun_merchant_id'],
      'paymentgatewayid'  => $this->config['borgun_gateway_id'],
      'orderid'           => $this->oOrder->id,
      'currency'          => $oCL->getNameById($this->oOrder->currency_id),
      'language'          => $oL->iso,
      'returnurlsuccess'  => $this->config['borgun_callback']['success'],
      
      // Pár opcionális ami rendelkezésre áll szóval beállítjuk mindig
      'returnurlsuccessserver'  => $this->config['borgun_callback']['server'],
      'returnurlcancel'         => $this->config['borgun_callback']['cancel'],
      'returnurlerror'          => $this->config['borgun_callback']['error'],
      'skipreceiptpage'         => $this->config['borgun_skipreceiptpage'],
      'merchantlogo'            => $this->config['borgun_merchantlogo'],
      'merchantemail'           => $this->config['borgun_merchantemail']
    );
  }


  private function _build_itemsdata()
  {
    $data  = array();
    $i  = 0;
    foreach ($this->oOrder->getOrderItems() as $item)
    {
      $data["itemdescription_{$i}"] = $item['name'];
      $data["itemcount_{$i}"]       = $item['qty'];
      $data["itemunitamount_{$i}"]  = round($item['price'] * (100 + $item['tax_value']) / 100, 2);  // bruttó értéke egy terméknek
      $data["itemamount_{$i}"]      = round($item['price'] * $item['qty'] * (100 + $item['tax_value']) / 100, 2);  // teljes rendelés sor értéke
      $i++;
    }
    
    // Trükk. Ha Weboffice-ból kifizették a rendelés egy részét, akkor felveszünk egy Discount-t, hogy a tételek összege és a total azonos legyen.
    $total_to_pay = $this->oOrder->getTotalToPay();
    if ( $this->oOrder->total != $total_to_pay)
    {
      // Discount
      $disc = $this->oOrder->total - $total_to_pay;
      
      $data["itemdescription_{$i}"] = _('Discount');
      $data["itemcount_{$i}"]       = 1;
      $data["itemunitamount_{$i}"]  = -1*round($disc, 2);  // bruttó értéke egy terméknek
      $data["itemamount_{$i}"]      = -1*round($disc, 2); // teljes rendelés sor értéke
      
      $i++;
    }
    
    return $data;
  }


  /**
   * Order-Hash generátor és validátor.
   * @param FALSE|string $hash False ha a generált hash-t akarjuk vissza kapni,
   * vagy a hash értéke ha ellenőrizni szeretnénk, ekkor TRUE ha valid, egyébként FALSE a visszatérési érték.
   * @return string|bool Ha alapértelmezett paraméterrel(FALSE)-val hívtuk a függvény string(hash),
   * ha egy hash kulcsot adtunk át akkor bool, értéke valid (TRUE), invalid (FALSE)
   */
  private function _orderhash($order_id, $amount, $currency)
  {
    global $oCL;
    
    $message  = sprintf('%s|%s|%s', $order_id, $amount, $currency);
    $tmp_hash = hash_hmac('sha256', $message, $this->config['borgun_secret_key']);
    return $tmp_hash;
  }

  /**
   * Orderhash ellenőrzés.
   * Azt kell ellenőrizni, hogy a Borguntól kapott adatok sértetlenek-e.
   *
   * @param string $hash $_REQUEST['orderhash'] Borguntól kapott adat.
   * @param array $data	$_REQUEST teljes tömb. amount, order_id, currency értékekkel.
   */
  private function _checkorderhash($hash, $data=array())
  {
    global $oCL;
    
    //$hash_calc = $this->_orderhash($this->oOrder->id, $this->oOrder->getTotalToPay(), $oCL->getNameById($this->oOrder->currency_id));
    $hash_calc = $this->_orderhash($data['orderid'], $data['amount'], $data['currency']);
    
    if ($hash_calc === $hash)
    {
      \Utils::doLog(LOG_INFO, sprintf('Borgun: "orderhash" MATCH! ORDERHASH: %s, ORDERHASH_CALC: %s', $data['orderhash'], $hash_calc));
      return true;
    }
    
    /**
     * Jelezni kell logolni nem a BORGUN-tól jött
     * Utils::doLog();
     */
    \Utils::doLog(LOG_INFO, sprintf('Borgun: "orderhash" mismatch! ORDERHASH_HTTP: %s, ORDERHASH_CALC: %s', $data['orderhash'], $hash_calc));
    
    return false;
  }


  /**
   * Check-Hash generátor és validátor.
   * @param FALSE|string $hash False ha a generált hash-t akarjuk vissza kapni,
   * vagy a hash értéke ha ellenőrizni szeretnénk, ekkor TRUE ha valid, egyébként FALSE a visszatérési érték.
   * @return string|bool Ha alapértelmezett paraméterrel(FALSE)-val hívtuk a függvény string(hash),
   * ha egy hash kulcsot adtunk át akkor bool, értéke valid (TRUE), invalid (FALSE)
   */
  private function _checkhash($hash = FALSE)
  {
    global $oCL;
    
    $message  = sprintf('%s|%s|%s|%s|%s|%s',
      $this->config['borgun_merchant_id'],
      $this->config['borgun_callback']['success'],
      $this->config['borgun_callback']['server'],
      $this->oOrder->id,
      $this->oOrder->getTotalToPay(),
      $oCL->getNameById($this->oOrder->currency_id)
    );
    
    $tmp_hash = hash_hmac('sha256', $message, $this->config['borgun_secret_key']);
    return (($hash === FALSE) ? $tmp_hash : ($hash === $tmp_hash));
  }


  /**
   * CURL metódussal történő POST végrehajtás
   * @return mixed A hívás eredménye.
   */
  private function _post_curl()
  {
    $ch = curl_init($this->config['borgun_gateway_uri']);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->checkout_data);

    // execute!
    $response = curl_exec($ch);

    // close the connection, release resources used
    curl_close($ch);

    return $response;
  }


  /**
   * STREAM metódussal történő POST végrehajtás
   * @return mixed A hívás eredménye.
   */
  private function _post_stream()
  {
    $_options  = array(
      'https' => array(
        'header'  => 'Content-type: application/x-www-form-urlencoded\r\n',
        'method'  => 'POST',
        'content' => http_build_query($this->checkout_data)
      )
    );
    $context  = stream_context_create($_options);
    $response = file_get_contents($this->config['borgun_gateway_uri'], FALSE, $context);

    return $response;
  }


  /**
   |----------------------------------------------------------------------------
   |
   |OPCIONÁLIS PARAMÉTEREK BEÁLLÍTÁSA.
   |
   |----------------------------------------------------------------------------
   */
  
  
  /**
   * Vásárló nevének beállítása.
   * @param string $buyer_name A vásárló neve. Ha nincs kitöltve, a vásárló adja meg a fizetési oldalon.
   * @return \Borgun\Payment Method chaining
   */
  public function set_buyername($buyer_name)
  {
    $this->checkout_data['buyername'] = $buyername;
    return $this;
  }


  /**
   * Vásárló email címének beállítása. Ha szerepel az üzenetben, a sikeres fizetést követően
   * erre a címre e-mail értesítést küldünk. Az üzenetben szerepelnek a kereskedő és a vásárló adatai, és a kosár tartalma.
   * @param type $buyer_email A vásárló email címe.
   * @return \Borgun\Payment Method chaining
   */
  public function set_buyeremail($buyer_email)
  {
    $this->checkout_data['buyeremail']  = $buyer_email;
    return $this;
  }


  /**
   * Értéke lehet „iframe“, ekkor a fizetési oldal kompaktabb kivitelben jelenik meg,
   * iframe-es megoldásokhoz igazítva. Minimum támogatott szélesség 370px, maximum 755px.
   * @return \Borgun\Payment Method chaining
   */
  public function set_displaymode()
  {
    $this->checkout_data['displaymode'] = "iframe";
    return $this;
  }


  /**
   * Értéke lehet „false“, ekkor a fizetési oldalon csak a MasterCard, MaestroCard, Visa és Visa Electron logók jelennek meg.
   * @param bool $showadditionalbrands
   * @return \Borgun\Payment Method chaining
   */
  public function set_showadditionalbrands($showadditionalbrands = FALSE)
  {
    $this->checkout_data['showadditionalbrands'] = $showadditionalbrands;
    return $this;
  }


  /**
   * A hivatkozási szám bármilyen karaktersor lehet, azonos érték kerül visszaküldésre is.
   * Fő funkciója, hogy külső megrendelés-azonosítóként egyszerűsítse a kereskedői rendszerhez való illeszkedést.
   * @param string $reference Rendelési referencia szám
   * @return \Borgun\Payment Method chaining
   */
  public function set_reference($reference)
  {
    $this->checkout_data['reference'] = $reference;
    return $this;
  }


  /**
   * Ez opcionális paraméter. Meghívásával beállítjuk a "recurrent_payment"-et,
   * a fizetés ismétlődő fizetési megbízás lesz. Ha használatban van,
   * akkor be kell állítani a recurrence_count paramétert is.
   * Az amount paraméterben megadott összeg havonta kerül terhelésre,
   * a teljes összeg a recurrence_count * amount szorzat lesz.
   * Az első fizetésre a következő hónap első napján kerül sor.
   * @return \Borgun\Payment Method chaining
   */
  public function set_payment_type()
  {
    $this->checkout_data['payment_type'] = "recurrent_payment";
    return $this;
  }


  /**
   * REGISZTRÁCIÓ! Választható paraméter. Ha TRUE-ra van állítva, a kártyabirtokosoknak e-mail-címet,
   * mobilszámot és lakcímet is meg kell adniuk. Ebben az esetben a merchantemail paraméter is kell
   * mivel a kártyabirtokosok adatait a kereskedő erre a címre kapja meg. (Ez ugyan opcionális paraméter,
   * de a konfigban be van állítva)
   * @param bool $pagetype TRUE: adja meg a telszámot, címet is, FALSE: kevesebb adat is elég.
   * @return \Borgun\Payment Method chaining
   */
  public function set_pagetype($pagetype = FALSE)
  {
    $this->checkout_data['pagetype'] = (($pagetype === FALSE) ? "0" : "1");
    return $this;
  }


  /**
   * Az ismételt kifizetések száma.
   * @param int $count Az ismételt kifizetések száma.
   * @return \Borgun\Payment Method chaining
   */
  public function set_recurrence_count($count)
  {
    $this->checkout_data['recurrence_count'] = $count;
    return $this;
  }


  /**
   * Választható paraméter. Ismétlődő kifizetés esetén az első fizetés napja,
   * ha nem kerül beállításra, az első terhelésre a következő hónap első napján kerül sor.
   * @param date $date A dátumformátum a következő: nn.HH.éééé.
   * @return \Borgun\Payment Method chaining
   */
  public function set_recurrence_start_date($date)
  {
    $this->checkout_data['recurrence_start_date'] = $date;
    return $this;
  }


  /**
   |----------------------------------------------------------------------------
   |
   |EGYÉB PUBLIKUS FÜGGVÉNYEK
   |
   |----------------------------------------------------------------------------
   */


  /**
   * A BORGUN válasz callback kezelése.
   * @param \Borgun\callable $callback Saját callback-et is megadhatunk sikeres(Success)
   * Payment kezelésére, ami a lib success kezelése után fut le.
   * @return mixed A callback feldolgozás eredménye.
   */
  public function callback(callable $callback = NULL, $data=array() )
  {
    \Utils::doLog(LOG_INFO, sprintf('Borgun payment return. REQUEST: %s', \Utils::dumpVar($_REQUEST)));
    
    // Leellenőrizzük hogy tényleg a BORGUN küldte e a választ. Ha nem egyezik a fv. FALSE-vel tér vissza!
    if ($this->_checkorderhash($data['orderhash'], $data) === FALSE)
    {
      return FALSE;
    }
    
    $result = FALSE;
    
    \Utils::doLog(LOG_INFO, sprintf('STATUS: %s', $data['status']));
    
    // Milyen tipusú callback hívás érkezett
    switch ( strtoupper(trim($data['status'])) )
    {
      // Success
      case "OK":
        // Server callback, Ide csak a server callbeck jön be, a fizetés utáni redirect az else-be megy
        if ($data['step'] === "Payment")  // „Payment”: A fizetési tranzakció lezárult. További információkért ld. a C szakaszt
        {
          $result = $this->_success($data);
          // Adtunk meg saját callback fv.-t
          if ( ! is_null($callback))
          {
            call_user_func($callback);
          }
        }
        elseif ($data['step'] === 'Confirmation')
        {
          $result = true;  // „Confirmation”: A vásárló a fizetési oldalról visszatért a webshop oldalára.
        }
        // Redirect
        else
        {
          // Sikeres fizetés utáni redirect page ide.
        }
        break;
      // Error - hiba esetén az _error kezeli a callback hívást
      case "ERROR":
        $result = $this->_error();
        break;
      // Cancel - törlés esetén a _cancel kezeli a callback hívást
      case "CANCEL":
        $result = $this->_cancel($data);
        break;

      default:
        break;
    }
    
    //\Utils::doLog(LOG_INFO, sprintf('RESULT: %s', ($result === true ? 'TRUE' : 'FALSE')));
    
    return $result;
  }


  /**
   * Sikeres callback által végrehajtott metódus.
   */
  private function _success( $data=array() )
  {
    // Callback rögzítése db-ben.
    $this->db->execute(
      "INSERT INTO borgun_callback (status, orderid, orderhash, authorizationcode, creditcardnumber)
      VALUES(?,?,?,?,?)",
      array(
        $data['status'],
        $data['orderid'],
        $data['orderhash'],
        $data['authorizationcode'],
        $data['creditcardnumber']
      )
    );
    $this->callback_id = $this->db->lastInsertId();
    
    return true;
  }


  /**
   * Error callback esetén végrehajtott metódus.
   */
  private function _error( $data=array() )
  {
    // Callback rögzítése db-ben.
    $this->db->execute(
      "INSERT INTO borgun_callback (status, orderid, orderhash, errordescription, errorcode)
      VALUES(?,?,?,?,?)",
      array(
        $data['status'],
        $data['orderid'],
        $data['orderhash'],
        $data['errordescription'],
        $data['errorcode']
      )
    );
    $this->callback_id = $this->db->lastInsertId();
    
    // Rendelés állapota: Error.
    $this->oOrder->setNextStatus(\OrdersStatus::STATUS_ERROR, array());
    
    return false;
  }


  /**
   * Törlés callback esetén végrehajtott metódus.
   */
  private function _cancel( $data = array() )
  {
    // Callback rögzítése db-ben.
    $this->db->execute(
      "INSERT INTO borgun_callback (status, orderid, orderhash)
      VALUES(?,?,?)",
      array(
        $data['status'],
        $data['orderid'],
        $data['orderhash'],
      )
    );
    $this->callback_id = $this->db->lastInsertId();
    
    // Rendelés állapota: Error.
    $this->oOrder->setNextStatus(\OrdersStatus::STATUS_IN_PROGRESS, array());
    
    return false;
  }


  /**
   * Getter
   * @param string $name Property neve.
   * @return mixed
   */
  public function __get($name)
  {
    return $this->$name;
  }


  /**
   * Fizetés lebonyolítása.
   * @return mixed A fizetés során kapott eredmény.
   */
  public function checkout()
  {
    $this->checkout_data    = array_merge($this->checkout_data, $this->_build_itemsdata());
    
    // Kötelező attribútumok, amik azért vannak itt hogy akár checkout előtt is módosítható legyen az order
    $this->checkout_data['amount']    = $this->oOrder->getTotalToPay();
    $this->checkout_data['checkhash'] = $this->_checkhash();

    $this->db->execute(
      "INSERT INTO borgun_payment (merchantid, paymentgatewayid, checkhash, orderid, currency, language, returnurlsuccess, amount, sent)
      VALUES(?,?,?,?,?,?,?,?,?)",
      array(
        $this->checkout_data['merchantid'],
        $this->checkout_data['paymentgatewayid'],
        $this->checkout_data['checkhash'],
        $this->checkout_data['orderid'],
        $this->checkout_data['currency'],
        $this->checkout_data['language'],
        $this->checkout_data['returnurlsuccess'],
        $this->checkout_data['amount'],
        date('Y-m-d H:i:s'),
      )
    );
//    $this->checkout_result  = call_user_func(array($this, $this->checkout_method));
    //$this->checkout_result  = $this->_post_curl();
    
    \Utils::doLog(LOG_INFO, sprintf('Borgun payment: Checkout DATA: %s', \Utils::dumpVar($this->checkout_data)));
    
    return $this->checkout_result;
  }
}