<?php

namespace App\Interfaces;

interface CustomerRepositoryInterface
{
    public function asll();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
