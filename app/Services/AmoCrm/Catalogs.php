<?php

namespace App\Services\AmoCrm;

use AmoCRM\Collections\CatalogElementsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\CatalogElementsFilter;
use AmoCRM\Models\CatalogElementModel;
use App\Services\AmoCrm\Constants\Catalogs as CatalogsValues;

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

    public static function setCatalogsQuantity(): CatalogElementModel
    {
        $catalog = Catalogs::getOneByName(CatalogsValues::COMPUTER);
        $catalog->setQuantity(CatalogsValues::QUANTITY_DEFAULT); 

        return $catalog;
    }
}
