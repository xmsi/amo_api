<?php

namespace App\Services\AmoCrm\Utils;

use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\ContactModel;
use App\Services\AmoCrm\Catalogs;
use App\Services\AmoCrm\Leads;
use App\Services\AmoCrm\Links;
use App\Services\AmoCrm\Tasks;

class IntersectedEntities
{
    public static function createLeadTaskCatalogs(ContactModel $contact): void
    {
        $lead = new Leads($contact->getId());
        $lead->save();
        Links::link(
            $contact,
            $lead->getLead(),
            EntityTypesInterface::CONTACTS
        );

        $task = new Tasks($lead->getLead()->getId(), $lead->getLead()->getResponsibleUserId());
        $task->save();

        $catalog = Catalogs::setCatalogsQuantity();
        Links::link($lead->getLead(), $catalog, EntityTypesInterface::LEADS);
    }
}
