<?php

namespace App\Services\AmoCrm;

use AmoCRM\Collections\LinksCollection;
use AmoCRM\Models\CatalogElementModel;
use AmoCRM\Models\LeadModel;

class Leads{
    public static function getOne(int $leadId): LeadModel
    {
        try {
            $lead = ApiClient::get()->leads()->getOne($leadId);
        } catch (AmoCRMApiException $e) {
            throw new \Exception("Lead not founded");
            die;
        }

        return $lead;
    }

    public static function linkCatalog(
        LeadModel $leadModel, 
        CatalogElementModel $catalogModel)
    {
        //Привяжем к сделке наш элемент
        $links = new LinksCollection();
        $links->add($catalogModel);
        try {
            ApiClient::get()->leads()->link($leadModel, $links);
        } catch (AmoCRMApiException $e) {
            throw new \Exception("Could not link", 1);
            die;
        }
    }
}