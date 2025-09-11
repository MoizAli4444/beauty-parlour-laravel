<?php

namespace App\Repositories\Testimonial;

interface TestimonialRepositoryInterface
{
    public function getDatatableData(array $data);
    public function all();
    public function find($id);
    public function findBySlug($slug);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);

    public function toggleStatus($id);
    public function toggleFeatured($id);
    public function bulkDelete(array $ids);
    public function bulkStatus(array $ids, string $status);
}
