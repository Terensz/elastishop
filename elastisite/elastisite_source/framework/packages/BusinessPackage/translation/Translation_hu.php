<?php

namespace framework\packages\BusinessPackage\translation;

class Translation_hu
{
    public function getTranslation()
    {
        return array(
            'invoice.id.not.existing' => 'A számlaszám ([invoiceId]) nem létezik',
            'organization.name' => 'A szervezet neve',
            'organization.tax.id' => 'A szervezet adószáma',
            'organization.country' => 'A székhely országa',
            'organization.zip.code' => 'A székhely irányítószáma',
            'organization.city' => 'A székhely városa',
            'organization.street' => 'A székhely közterület-neve',
            'organization.street.suffix' => 'A székhely közterület-jellege',
            'organization.house.number' => 'A székhely házszáma',
            'responsible.person' => 'Felelős személy',
            'no.responsible.person.selected' => 'Nincs felelős személy',
            'administration.stance' => 'Ügyintézés módja',
            'no.administration.stance.selected' => 'Nincs ügyintézés módja kiválasztva',
            'phone.call' => 'Telefonhívás',
            'personal' => 'Személyes találkozó',
            'correspondence' => 'Levelezés',
            'concept.creation' => 'Koncepció létrehozása'
        );
    }
}
