<?php
namespace framework\packages\BasicPackage\repository;

use framework\component\parent\DbRepository;

class CountryRepository extends DbRepository
{
    public function findAllAvailable()
    {
        $availableCountries = $this->getContainer()->getConfig()->getProjectData('availableCountries');
        if ($availableCountries && is_array($availableCountries)) {
            $result = array();
            foreach ($availableCountries as $availableCountry) {
                $foundCountry = $this->findOneBy(['conditions' => [['key' => 'alpha_two', 'value' => $availableCountry]]]);
                if ($foundCountry) {
                    $result[] = $foundCountry;
                }
            }
            return $result;
        } else {
            return $this->findAll();
        }
    }
}
