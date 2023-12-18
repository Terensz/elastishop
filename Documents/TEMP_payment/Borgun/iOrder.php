<?php
namespace Borgun;


/**
 * A Borgun\Payment API osztály számára szükséges iOrder objektum definiálása.
 */
interface iOrder
{
  /**
   * A fő Getter metódusnak a következő property-kel kell tudni szolgálni
   * id       Rendelés egyedi ID-ja
   * currency A rendelés alapértelmezett pénzneme pl.:"EUR" (Payment::currencies Array)
   * total    A rendelés teljes összértéke
   * _items   A rendelés iOrderItem típusú elemeinek gyüjteménye.
   */
  public function __get($name);
}