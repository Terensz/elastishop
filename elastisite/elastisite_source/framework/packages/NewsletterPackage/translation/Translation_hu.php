<?php
namespace framework\packages\NewsletterPackage\translation;

class Translation_hu
{
    public function getTranslation()
    {
        return array(
            'newsletter' => 'Hírlevél',
            
            'newsletter.administration' => 'Hírlevelek adminisztrációja',

            'admin.newsletters' => 'Hírlevelek',
            'create.new.newsletter' => 'Új hírlevél létrehozása',
            'edit.newsletter' => 'Hírlevél szerkesztése',

            'newsletter.campaign' => 'Hírlevél-kampány',
            'admin.newsletter.campaigns' => 'Hírlevél-kampányok',
            'create.new.newsletter.campaign' => 'Új hírlevél-kampány létrehozása',
            'edit.newsletter.campaign' => 'Hírlevél-kampány szerkesztése',

            'admin.newsletter.dispatch.processes' => 'Hírlevél-kiküldési folyamatok',
            'create.new.newsletter.dispatch.process' => 'Új hírlevél-kiküldési folyamat létrehozása',
            'edit.newsletter.dispatch.process' => 'Hírlevél-kiküldési folyamat szerkesztése',
            'total.dispatches.count' => 'Összes kiküldendő levél száma',
            'dispatches.sent' => 'Elküldött levelek száma',
            'newsletter.create.dispatch.process.info' => 'Mielőtt elmenti a hírlevél-kiküldési folyamatot, többször is ellenőrizze, hogy pontosan ezt szeretné-e. 
            A mentés gomb elkészíti az összes feliratkozott számára a kiküldés előjegyzését, amit a megnyitott Kiküldés-feldolgozó el is kezd kiküldeni.',
            // 'admin.newsletter.init.campaign' => 'Hírlevél-kampány elindítása',
            'admin.newsletter.process.sending' => 'Kiküldés-feldolgozó',
            'newsletter.dispatch.process.status.info' => 'Az "Aktív" státuszú kiküldési folyamatok levelei azonnal a kiküldési sorba kerülnek a Kiküldés-feldolgózóban. 
                A "Szüneteltetve" sátuszúak megjelennek a Kiküldés-feldolgózóban, de az ehhez tartozó levelekből nem megy ki több, amíg a státuszt nem állítja vissza "Aktív"-ra. 
                Az "Inaktív" státuszú folyamatok meg sem jelennek a Kiküldés-feldolgózóban, amíg át nem állítja a státuszukat a másik két státusz valamelyikére, pont itt, ez alatt a szövegdoboz alatt.',
            'newsletter.create.dispatch.process.create.info' => 'Ha elmenti a folyamatot, a program előkészíti a kiküldéseket az összes feliratkozott felhasználó számára.',
            'created' => 'Létrehozva',
            'paused' => 'Szüneteltetve'
        );
    }
}
