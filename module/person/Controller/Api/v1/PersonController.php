<?php

namespace PERSON\Controller\Api\v1;

use App\Http\Controllers\Controller;
use PERSON\Requests\CreatePersonRequest;
use PERSON\Requests\UpdatePersonRequest;
use PERSON\Resources\PersonResource;
use PERSON\Service\v1\PersonServiceInterface;

class PersonController extends Controller
{
    private $personService;

    public function __construct(PersonServiceInterface $personService)
    {
        $this->personService = $personService;
    }

    public function index()
    {
        $people = $this->personService->getAllPeople();
        return PersonResource::collection($people);
    }

    public function show($id)
    {
        $person = $this->personService->getPersonById($id);
        return new PersonResource($person);
    }

    public function store(CreatePersonRequest $request)
    {
        $personData = $request->validated();
        $person = $this->personService->createPerson($personData);
        return new PersonResource($person);
    }

    public function update(UpdatePersonRequest $request, $id)
    {
        $personData = $request->validated();
        $person = $this->personService->updatePerson($id, $personData);
        return new PersonResource($person);
    }

    public function destroy($id)
    {
        $this->personService->deletePerson($id);
        return response()->noContent();
    }
}
