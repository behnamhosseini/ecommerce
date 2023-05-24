<?php

namespace PERSON\Service\v1;


class PersonService implements PersonServiceInterface
{
    private $personRepository;

    public function __construct(PersonRepositoryInterface $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function createPerson(array $data)
    {
        return $this->personRepository->create($data);
    }

    public function updatePerson(int $id, array $data)
    {
        return $this->personRepository->update($id, $data);
    }

    public function deletePerson(int $id)
    {
        return $this->personRepository->delete($id);
    }

    public function getPersonById(int $id)
    {
        return $this->personRepository->findById($id);
    }

    public function getAllPeople()
    {
        return $this->personRepository->getAll();
    }
}
