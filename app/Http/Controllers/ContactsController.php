<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactsRequest;
use App\Services\AmoCrm\Contacts;
use App\Services\AmoCrm\Utils\IntersectedEntities;

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

        IntersectedEntities::createLeadTaskCatalogs($contact->getContact());

        return back()->with('success', 'Успешно сохранён');
    }
}
