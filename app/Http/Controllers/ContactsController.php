<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactsRequest;
use App\Services\AmoCrm\Catalogs;
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

        if ($contact->isCustomerCreated) {
            return back()->with('success', "Успешно проведена операция");
        }

        $lead = Leads::getOne(config('services.amocrm.leadId'));
        $catalog = Catalogs::getOneByName('Компьютер');

        $catalog->setQuantity(2.0);
        Links::link($lead, $catalog, 'leads');

        $task = new Tasks($lead->getId(), $lead->getResponsibleUserId());
        $task->save();
        
        $contact->save();
        Links::link($contact->getContact(), $lead, 'contacts');

        return back()->with('success', "Успешно сохранён");
    }
}
