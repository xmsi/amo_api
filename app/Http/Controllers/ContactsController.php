<?php

namespace App\Http\Controllers;

use AmoCRM\Helpers\EntityTypesInterface;
use App\Http\Requests\ContactsRequest;
use App\Services\AmoCrm\Catalogs;
use App\Services\AmoCrm\Constants\Catalogs as CatalogsValue;
use App\Services\AmoCrm\Contacts;
use App\Services\AmoCrm\Leads;
use App\Services\AmoCrm\Links;
use App\Services\AmoCrm\Tasks;

class ContactsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ContactsRequest $request)
    {
        $contact = new Contacts($request->validated());

        if ($contact->getIsHaveDouble()) {
            return back()->with('success', 'Успешно проведена операция');
        }

        $contact->save();

        $lead = new Leads($contact->getContact()->getId());
        $lead->save();
        Links::link(
            $contact->getContact(),
            $lead->getLead(),
            EntityTypesInterface::CONTACTS
        );

        $task = new Tasks($lead->getLead()->getId(), $lead->getLead()->getResponsibleUserId());
        $task->save();

        $catalog = Catalogs::getOneByName(CatalogsValue::NAME);

        $catalog->setQuantity(CatalogsValue::QUANTITY); //
        Links::link($lead->getLead(), $catalog, EntityTypesInterface::LEADS);

        return back()->with('success', 'Успешно сохранён');
    }
}
