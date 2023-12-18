<?php

namespace framework\packages\WebshopPackage\translation;

class Translation_huFor
{
    public function getTranslation()
    {
        return array(
            'order.close.warning' => 'Figyelmeztetés! Ha a megrendelést "Törölve" vagy "Kézbesítve" státuszúra állítja:<br>
            - Minden olyan személyes adat törlődni fog, ami kapcsolódik a megrendeléshez, és ami nem regisztráció útján rögzült. <br>
            (A GDPR miatt. A nem regisztrált felhasználók nem tudják a személyes adataikat törölni, mert nem tudnak bejelentkezni.) <br>
            A kiszállítási ország, város és az irányítószám megmaradnak, mivel ezek nem személyes adatok. <br>
            - A megrendelés többé nem lesz visszanyitható és szerkeszthető (pont az adatok törlése miatt)'
        );
    }
}
