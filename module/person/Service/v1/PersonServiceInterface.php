<?php

namespace PERSON\Service\v1;

interface PersonServiceInterface
{
    public function createPerson(array $data);

    public function updatePerson(int $id, array $data);

    public function deletePerson(int $id);

    public function getPersonById(int $id);

    public function getAllPeople();

}
