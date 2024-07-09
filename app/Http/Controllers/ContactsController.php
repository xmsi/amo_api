<?php

namespace App\Http\Controllers;

use App\Services\AmoCrm\Catalogs;
use App\Services\AmoCrm\Contacts;
use App\Services\AmoCrm\Leads;
use App\Services\AmoCrm\Links;
use App\Services\AmoCrm\Tasks;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): void
    {
        $validatedData = $request->validate([
            "firstname" => "required",
            "lastname" => "required",
            "age" => "required|numeric",
            "gender" => "required",
            "phone" => "required",
            "email" => "required|email",
        ]);

        $contact = new Contacts($validatedData);
        $lead = Leads::getOne(1551521);
        $catalog = Catalogs::getOneByName('Компьютер');

        $catalog->setQuantity(2.0);
        Links::link($lead, $catalog, 'leads');

        $task = new Tasks($lead->getId(), $lead->getResponsibleUserId());
        $task->save();
        dd($task);
        
        $contact->save();
        Links::link($contact->getContact(), $lead, 'contacts');
    }
}
