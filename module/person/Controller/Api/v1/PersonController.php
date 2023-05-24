<?php

namespace PERSON\Controller\Api\v1;

use App\Http\Controllers\Controller;
use PERSON\Requests\CreatePersonRequest;
use PERSON\Requests\UpdatePersonRequest;
use PERSON\Resources\PersonResource;
use PERSON\Service\v1\PersonServiceInterface;

class PersonController extends Controller
{
    private $personUseCase;

    public function __construct(PersonServiceInterface $personUseCase)
    {
        $this->personUseCase = $personUseCase;
    }

    public function index()
    {
        $people = $this->personUseCase->getAllPeople();
        return PersonResource::collection($people);
    }

    public function show($id)
    {
        $person = $this->personUseCase->getPersonById($id);
        return new PersonResource($person);
    }

    public function store(CreatePersonRequest $request)
    {
        $personData = $request->validated();
        $person = $this->personUseCase->createPerson($personData);
        return new PersonResource($person);
    }

    public function update(UpdatePersonRequest $request, $id)
    {
        $personData = $request->validated();
        $person = $this->personUseCase->updatePerson($personData, $id);
        return new PersonResource($person);
    }

    public function destroy($id)
    {
        $this->personUseCase->deletePerson($id);
        return response()->noContent();
    }
}
