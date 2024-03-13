<?php

namespace App\Http\Controllers;

use App\Mail\SendApproveAdoption;
use App\Mail\SendWelcomePet;
use App\Models\Adoption;
use App\Models\Client;
use App\Models\People;
use App\Models\Pet;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class AdoptionController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        try {

            $filters = $request->query();

            $pets = Pet::query()
                ->select(
                    'id',
                    'pets.name as pet_name',
                    'pets.age as age'
                )
                ->where('client_id', null);


            if ($request->has('name') && !empty($filters['name'])) {
                $pets->where('name', 'ilike', '%' . $filters['name'] . '%');
            }

            if ($request->has('age') && !empty($filters['age'])) {
                $pets->where('age', $filters['age']);
            }

            if ($request->has('size') && !empty($filters['size'])) {
                $pets->where('size', $filters['size']);
            }

            if ($request->has('weight') && !empty($filters['weight'])) {
                $pets->where('weight', $filters['weight']);
            }

            if ($request->has('specie_id') && !empty($filters['specie_id'])) {
                $pets->where('specie_id', $filters['specie_id']);
            }

            return $pets->orderBy('created_at', 'desc')->get();
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    {
        $pet = Pet::with("race")->with("specie")->find($id);

        if ($pet->client_id) return $this->error('Dados confidenciais', Response::HTTP_FORBIDDEN);

        if (!$pet) return $this->error('Dado não encontrado', Response::HTTP_NOT_FOUND);

        return $pet;
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $request->validate([
                'name' => 'string|required|max:255',
                'email' => 'string|required|email|max:255',
                'cpf' => 'string|required|max:14|regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/',
                'contact' => 'string|required|max:20',
                'observations' => 'string|required',
                'pet_id' => 'integer|required',
            ]);

            $adoption = Adoption::create([...$data, 'status' => 'PENDENTE']);

            return $adoption;

        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function getAdoptions(Request $request)
    {
        $search = $request->input('search');

        $adoptions = Adoption::query()
        ->with('pet')
        ->where('name', 'ilike', "%$search%")
        ->orWhere('email', 'ilike', "%$search%")
        ->orWhere('contact', 'ilike', "%$search%")
        ->orWhere('status', 'ilike', "%$search%");

        return $adoptions->get();
    }

    public function approve(Request $request)
    {

        try {

            $data = $request->all();

            $request->validate([
                'adoption_id' => 'integer|required',
            ]);

            $adoption = Adoption::find($data['adoption_id']);

            if (!$adoption)  return $this->error('Dado não encontrado', Response::HTTP_NOT_FOUND);

            $adoption->update(['status' => 'APROVADO']);
            $adoption->save();

            $people = People::create([
                'name' => $adoption->name,
                'email' => $adoption->email,
                'cpf' => $adoption->cpf,
                'contact' => $adoption->contact,
            ]);

            $client = Client::create([
                'people_id' => $people->id,
                'bonus' => true
            ]);

            $pet = Pet::find($adoption->pet_id);
            $pet->update(['client_id' => $client->id]);
            $pet->save();

            $email = $adoption->email;

            Mail::to($email)
            ->send(new SendApproveAdoption($adoption, $pet));

            return $client;

        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
