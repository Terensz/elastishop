<?php

namespace framework\packages\UserPackage\translation;

class Translation_huFor
{
    public function getTranslation()
    {
        return array(
            'registration.successful.body' => 'Hamarosan megérkezik az aktiváláshoz szükséges e-mail az Ön által megadott [reg_email] címre.
            Kérem, kövesse a levélben szereplő instrukciókat a regisztrációja véglegesítéséhez!

            Amennyiben 3 percen belül nem érkezik meg a levél, ellenőrizze, hogy a levelezőprogramja nem minősítette-e levélszemétnek a regisztrációs visszaigazolást.',
            'registration.token.redeem.error' => 'A címsorban szereplő regisztrációs tokent (egyszer felhasználható zseton) hibásan adta meg,
            vagy korábban már felhasználásra került. Kérem, ellenőrizze az e-mailben kapott linket, hogy esetleg hiányosan másolta-e be, vagy
            próbáljon belépni a regisztrációkor megadott adataival, mert elképzelhető, hogy már aktiválta a hozzáférését.',
            'registration.token.redeem.success1' => 'A továbbiakban már a bejelentkezést követően használhatja a hozzáférését. Felhasználóneve: <b>[username]</b>',
            'registration.token.redeem.success2' => 'Sikeresen megerősítette a regisztrációját, de a hozzáférése még nem aktív. Kérjük, vegye fel a kapcsolatot a webhely üzemeltetőjével. Felhasználóneve: <b>[username]</b>.',
            'modify.password.info' => 'Nem sokkal azután, hogy az "Elküldés" gombra kattintott, egy levelet fog kapni az e-mail címére, ami egy ideiglenes, biztonságos linket fog tartalmazni a jelszómódosításhoz. Kérem, használja fel a linket, amint lehetséges.',
            'remove.presonal.data.warning' => 'A személyes adatok törlése felelős döntés, következményeként visszaállíthatatlanul megszűnik az Ön felhasználói fiókja, és az ahhoz kapcsolódó összes kedvezmény is. 
            Döntése előtt javasoljuk, hogy <a class="ajaxCallerLink" href="[httpDomain]/documents/how-do-we-protect-personal-data">olvassa 
            el a személyes adatok törléséről szóló cikkünket</a>.<br>
            <br>
            Amennyiben vállalja, hogy döntése végleges, és következményei visszavonhatatlanok, kérjük, gépelje be az alábbi mezőbe a jelszavát, fogadja el a nyilatkozatot, 
            és nyomja meg a "Törlöm a felhasználói fiókomat" gombot.',
            'click.here.to.recover.password' => 'Ha elfelejtette a felhasználói fiókhoz tartozó jelszavát, <a onclick="Login.recoverPasswordModalOpen(event);" href="">ide kattintva lehetősége van újat kérni</a>.'
        );
    }
}
