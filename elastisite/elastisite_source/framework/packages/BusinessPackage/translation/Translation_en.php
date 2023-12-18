<?php

namespace framework\packages\BusinessPackage\translation;

class Translation_en
{
    public function getTranslation()
    {
        return array(
            'invoice.id.not.existing' => 'Invoice id ([invoiceId]) not existing',
            'organization.address' => 'Address of the organization',
            'organization.name' => 'Name of the organization',
            'organization.tax.id' => 'Tax ID of the organization',
            'organization.country' => 'Country of the headquarters of the organization',
            'organization.zip.code' => 'Zip code of the headquarters of the organization',
            'organization.city' => 'City of the headquarters of the organization',
            'organization.street' => 'Street name of the headquarters of the organization',
            'organization.street.suffix' => 'Street suffix of the headquarters of the organization',
            'organization.house.number' => 'House number of the headquarters of the organization',
            'responsible.person' => 'Responsible person',
            'no.responsible.person.selected' => 'No responsible person',
            'administration.stance' => 'Administration method',
            'no.administration.stance.selected' => 'No administration stance selected',
            'phone.call' => 'Phone call',
            'personal' => 'Personal meeting',
            'correspondence' => 'Correspondence',
            'concept.creation' => 'Concept creation'
        );
    }
}
