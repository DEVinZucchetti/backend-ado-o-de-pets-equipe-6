<?php

namespace App\Http\Services\Pet;

use App\Mail\SendWelcomePet;
use App\Models\People;
use App\Models\Pet;
use Illuminate\Support\Facades\Mail;

class SendEmailWelcomeService
{

    public function handle(Pet $pet)
    {
        if (!empty($pet->client_id)) {
            $people = People::find($pet->client_id);
            Mail::to($people->email, $people->name)
                ->send(new SendWelcomePet($pet->name, 'Henrique Douglas'));
        }
    }
}
