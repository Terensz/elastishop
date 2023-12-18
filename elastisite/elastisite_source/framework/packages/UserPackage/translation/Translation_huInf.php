<?php

namespace framework\packages\UserPackage\translation;

class Translation_huInf
{
    public function getTranslation()
    {
        return array(
            'registration.successful.body' => 'Hamarosan megérkezik az aktiváláshoz szükséges e-mail a regisztrációkor megadott [reg_email] címedre.
            Kérlek, kövesd a levélben szereplő instrukciókat a regisztrációd véglegesítéséhez!

            Amennyiben 3 percen belül nem érkezik meg a levél, ellenőrizd, hogy a levelezőprogramod nem minősítette-e levélszemétnek a regisztrációs visszaigazolást.',
            'registration.token.redeem.error' => 'A címsorban szereplő regisztrációs tokent (egyszer felhasználható zseton) hibásan adtad meg,
            vagy korábban már felhasználtad. Kérlek, ellenőrizd az e-mailben kapott linket, hogy esetleg hiányosan másoltad-e be, vagy
            próbálj belépni a regisztrációkor megadott adataiddal, mert elképzelhető, hogy már aktiváltad a hozzáférésedet.',
            'registration.token.redeem.success1' => 'A továbbiakban már a bejelentkezést követően fogod tudni használni a hozzáférésedet. Felhasználóneved: <b>[username]</b>',
            'registration.token.redeem.success2' => 'Sikeresen megerősítetted a regisztrációdat, de a hozzáférésed még nem aktív. Kérlek, vedd fel a kapcsolatot a webhely üzemeltetőjével. Felhasználóneved: <b>[username]</b>',
            'modify.password.info' => 'Nem sokkal azután, hogy az "Elküldés" gombra kattintottál, egy levelet fogsz kapni az e-mail címedre, ami egy ideiglenes, biztonságos linket fog tartalmazni a jelszómódosításhoz. Kérlek, használd fel a linket, amint lehetséges.'
        );
    }
}
