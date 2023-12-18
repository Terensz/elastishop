<?php
namespace Borgun;


interface iOrderItem
{
  // Vissza kell tudni adni az egyetlen termékre vonatkozó értéket.
  public function get_gross();

  // Viassza kell tudni adni a teljes sorra vonatkozó teljes összértéket.
  public function get_total();

  /**
   * A fő Getter metódusnak a következő property-kel kell tudni szolgálni.
   * description  Ez a rendeli iOrderItem leírása.
   * count        A rendelt iOrderItem mennyisége.
   */
  public function __get($name);
}