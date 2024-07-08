<?php

namespace App\Services\AmoCrm;

use AmoCRM\Collections\LinksCollection;
use AmoCRM\Models\BaseApiModel;

class Links
{
    public static function link(
        BaseApiModel $mainModel,
        BaseApiModel $subModel,
        string $mainEntity
    ): void {
        
        $links = new LinksCollection();
        $links->add($subModel);
        try {
            ApiClient::get()->{$mainEntity}()->link($mainModel, $links);
        } catch (AmoCRMApiException $e) {
            throw new \Exception("Could not link", 1);
        }
    }
}
