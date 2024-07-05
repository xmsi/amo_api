<?php

namespace App\Http\Controllers;

use App\Services\AmoCrm\ApiClient;
use App\Services\AmoCrm\Contacts;
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

        // $contact = Contacts::add($validatedData);
    }
}
