<?php


/**
 * BorgunOrder
 */
class BorgunOrder implements Borgun\iOrder
{
  private
    $db,            // Globalis PDO db objektum
    $_items,        // OrderItem tipusú objektumok listája

    $id,            // Az új orders tábla rekord ID-ja. Ez lesz beküldve a Borgun-ba [egyedi kell legyen]
    $status,        // Status id az orders_status táblából
    $customer,      // Customer id a customer táblából
    $address_bill,  // Számlázási cím address ID-ja
    $address_dlv,   // Szállítási cím address ID-ja
    $total,         // Összes rendelt item összértéke
    $currency,      // A rendelés pénzneme, currency ID
    $date_payment;  // A fizetés ideje

  private $db_table = "orders";

  // A property aktuális értéke valós e. Ha nem, újraszámolódik a getter-en keresztül
  private $total_valid  = FALSE;


  /**
   * Konstruktor.
   * @global PDO object $db PDO adatbázis objektum.
   * @param number $customer_id Customer azonosító a customer tábla ID-ja. Ha létező
   * rendelést (Order) szeretnék inicializálni akkor csak az első paramétert adjuk meg
   * ami ekkor nem a CustomerID hanem az OrderID.
   * @param number $currency_id (optional) Pénznem azonosító, a currency tábla ID-ja. Abban
   * az esetben ha létező order-t inicializálunk nem újat, akkor az első paramétert adjuk csak meg
   * ami maga a rendelés azonosítója(OrderID).
   * @param number $status_id (optional) A rendelés státus azonosítója, az orders_status tábla ID-ja.
   */
  public function __construct($customer_id, $currency_id = NULL, $status_id = 1)
  {
    global $db;
    
    \Utils::doLog(LOG_DEBUG, sprintf('%s', __METHOD__));

    $this->db       =& $db;
    $this->_items   = array();

    if (is_null($currency_id))
    {
      $this->_initialize_load($customer_id);
    }
    else
    {
      $this->customer = $customer_id;
      $this->currency = $currency_id;
      $this->status   = $status_id;
      $this->_initialize();
    }
  }


  /**
   * Ha már létező Order-t inicializálunk.
   */
  private function _initialize_load($id)
  {
    $order_row  = $this->db->getRow(
      "SELECT * FROM {$this->db_table}
      WHERE id=?",
      array(
        $id
      )
    );
    if ($order_row)
    {
      $this->id           = $order_row['id'];
      $this->status       = $order_row['orders_status_id'];
      $this->customer     = $order_row['customer_id'];
      $this->address_bill = $order_row['address_id_bill'];
      $this->address_dlv  = $order_row['address_id_dlv'];
      $this->total        = $order_row['total'];
      $this->currency     = $order_row['currency_id'];
      $this->date_payment = $order_row['date_payment'];
    }
  }


  /**
   * Alapértelmezett tulajdonságok beállítása.
   */
  private function _initialize()
  {
    $this->db->execute(
      "INSERT INTO {$this->db_table}
      (`orders_status_id`, `customer_id`, `currency_id`, `created`) VALUES (?,?,?,?)",
      array(
        $this->status,
        $this->customer,
        $this->currency,
        date('Y-m-d H:i:s')
      )
    );
    $this->id = $this->db->lastInsertId();
  }


  /**
   * A végösszeg újra számítása az összes megrendelt tétel alapján.
   */
  private function _recalculate_total()
  {
    $tmp_total  = 0;
    // Minden rendelési tétel (sor) összértékének összegzése
    foreach ($this->_items as $oItem)
    {
      $tmp_total  =+ $oItem->get_total();
    }
    // A rendelési total frissítése
    $this->total  = $tmp_total;

    // DB total frissítés
    $this->db->execute(
      "UPDATE {$this->db_table}
      SET `total`=?
      WHERE id=?",
      array(
        $this->total,
        $this->id
      )
    );
    $this->total_valid  = TRUE;
  }


  private function _get_currency_code()
  {
    $code = "EUR";
    return $code;
  }


  /**
   * A rendelési azonosító létezik e már az orders táblában.
   * @param numeric $id A rendelési azonosító Order->id
   * @return bool TRUE: létezik az Order, egyébként FALSE.
   */
  public static function valid_order($id)
  {
    global $db;
    $count = $db->getOne("SELECT COUNT(*) FROM orders WHERE id=?", array($id));
    return (boolval($count));
  }


  /**
   * Új rendelési darabok felvitele a rendelésbe.
   * @param Array $oItems A rendelési darab objektumok tömbje.
   */
  public function add_items(Array $oItems)
  {
    // Gyüjteménybe rakjuk az új rendelési darabot.
    foreach ($oItems as $oItem)
    {
      $this->add_item($oItem);
    }
  }


  /**
   * Új rendelési darab felvitele a rendelésbe.
   * @param \Borgun\OrderItem $oItem A rendelési darab objektum.
   * @return Order Method chaining
   */
  public function add_item(\Borgun\OrderItem $oItem)
  {
    // Gyüjteménybe rakjuk az új rendelési darabot.
    $oItem->set_order($this->id);
    $this->_items[] = $oItem;
    $this->total_valid  = FALSE;
    return $this;
  }


  /**
   * Order Fizetettre állítása.
   */
  public function payed()
  {
    // Fizetés dátumának beállítása
    $this->db->execute(
      "UPDATE {$this->db_table}
      SET date_payment=?,
      SET orders_status_id=?
      WHERE id=?",
      array(
        date('Y-m-d H:i:s'),
        10,   // Paid status-ra állítás
        $this->id
      )
    );
  }


  /**
   * Objektum property getter.
   * @param string $name Property neve.
   */
  public function __get($name)
  {
    if ($name === 'total' AND $this->total_valid === FALSE)
    {
      $this->_recalculate_total();
    }
    if ($name === 'currency_id')
    {
      return $this->currency;
    }
    if ($name === 'currency')
    {
      return $this->_get_currency_code();//Convert id to currency
    }
    return $this->{$name};
  }
}