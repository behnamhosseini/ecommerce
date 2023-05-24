<?php


namespace PERSON\Repository\v1;


use PERSON\Models\Person;

class PersonRepository
{
    public function create(array $data)
    {
        return Person::create($data);
    }

    public function update(int $id, array $data)
    {
        $person = $this->findById($id);
        $person->update($data);
        return $person;
    }

    public function delete(int $id)
    {
        $person = $this->findById($id);
        $person->delete();
    }

    public function findById(int $id)
    {
        return Person::findOrFail($id);
    }

    public function getAll()
    {
        return Person::all();
    }
}
