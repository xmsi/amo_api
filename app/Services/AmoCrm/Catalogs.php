<?php

namespace App\Services\AmoCrm;

use AmoCRM\Collections\CatalogElementsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\CatalogElementsFilter;
use AmoCRM\Models\CatalogElementModel;

class Catalogs
{
    public static function getOneByName(string $title): CatalogElementModel
    {
        $catalog = ApiClient::get()->catalogs()->get()->getBy('catalogType', 'products');
        $catalogElementsCollection = new CatalogElementsCollection();
        $catalogElementsService = ApiClient::get()->catalogElements($catalog->getId());
        $catalogElementsFilter = new CatalogElementsFilter();
        $catalogElementsFilter->setQuery($title);
        try {
            $catalogElementsCollection = $catalogElementsService->get($catalogElementsFilter);
        } catch (AmoCRMApiException $e) {
            throw new \Exception($e->message());
            die;
        }

        return $catalogElementsCollection->getBy('name', $title);
    }
}
