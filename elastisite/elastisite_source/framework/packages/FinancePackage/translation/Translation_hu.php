<?php

namespace framework\packages\FinancePackage\translation;

class Translation_hu
{
    public function getTranslation()
    {
        return array(
            'invoice.header.data' => 'Számlázási adatok',
            'total.payable' => 'Összesen fizetendő',
            'total.paid' => 'Összesen kifizetve',
            'product.name' => 'A termék neve',
            'invoice.data' => 'Számlaadatok',
            'invoice.item.data' => 'Számlatétel-adatok',
            'tax.office' => 'Adóhivatal',
            'vat.profile' => 'ÁFA-profil',
            'in.case.of.missing.vat.percent.check.tax.office.settings' => 'Hiányzó ÁFA-kulcs esetén a webfejlesztővel ellenőriztesse az adóhivatal beállításait.',
            'finance.administration' => 'Pénzügyi adminisztráció',
            'invoices' => 'Számlák',
            'tax.office.comm.status' => 'Adóhivatal válasza',
            'corrected.invoice.number' => 'Ellenszámla száma',
            'buyer.name' => 'Vásárló neve',
            'year.of.issue' => 'Kiállítás éve',
            'invoice.items.count' => 'Tételek száma',
            'handling.invoice' => 'Számla kezelése',
            'total.net' => 'Nettó végösszeg',
            'download.invoice' => 'Számla letöltése',
            'invoice' => 'Számla',
            'issuer.of.this.invoice' => 'A számla kiállítója',
            'issuer.name' => 'Név',
            'issuer.address' => 'Cím',
            'issuer.tax.id' => 'Adószám',
            'issuer.bank.account.number' => 'Bank',
            'invoice.number' => 'Számlaszám',
            'buyer.data' => 'A vevő adatai',
            'amount.to.be.paid' => 'Fizetendő',
            'item.name' => 'Tétel megnevezése',
            'quantity' => 'Mennyiség',
            'unit.of.measure' => 'Egység',
            'piece' => 'darab',
            'net.unit.price' => 'Nettó egységár',
            'vat.simple' => 'ÁFA',
            'item.gross' => 'Bruttó fizetendő',
            'sum' => 'Összesen',
            'create.credit.note' => 'Számla sztornózása',
            'correction.invoice' => '<b>Helyesbítő- vagy sztornószámla</b> (A számla további korrekciójára nincs lehetőség)',
            'making.credit.note.warning' => '<b>Figyelmeztetés!</b><br>A sztornószámla elkészítése során a rendszer a NAV-hoz azonnal elküldi a sztornózási kérelmet, vagyis ha megerősíti a művelet elvégzését, az a jövőben visszavonhatatlan lesz.<br>
                Csakis akkor tegye ezt, ha pontosan tudja, mit csinál!',
            'making.credit.note.confirm' => 'Megértettem, és továbbra is szeretném elkészíteni ennek a számlának a sztornószámláját.',
            'fully.credited' => 'Sztornózott számla',
            'credit.note.created.successfully' => 'Sztornószámla sikeresen létrehozva',
            'credit.note.create.failed' => 'Sztornószámla létrehozása sikertelen',
            'vat.declaration.test' => 'ÁFA-lejelentés teszt'
            // 'buyer.name' => ''
            // 'issuer.' => '',
            // 'issuer.' => '',
            // 'issuer.' => '',
            // 'issuer.' => '',
            // 'issuer.' => '',
            // 'issuer.' => '',
        );
    }
}
