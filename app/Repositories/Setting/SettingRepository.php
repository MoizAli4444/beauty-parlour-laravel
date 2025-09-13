<?php

namespace App\Repositories\Setting;

use App\Models\SiteSetting;
use App\Repositories\Setting\SettingRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\TracksUser;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SettingRepository implements SettingRepositoryInterface
{


    public function all()
    {
        return SiteSetting::latest()->get();
    }

    public function find($id)
    {
        return SiteSetting::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return SiteSetting::where('slug', $slug)->first();
    }


    public function create(array $data)
    {
        // $data = $this->addCreatedBy($data);

        return SiteSetting::create($data);
    }


    public function update($id, array $data)
    {
        $deal = SiteSetting::findOrFail($id);
        $data = $this->addUpdatedBy($data);

        if (isset($data['image'])) {
            $file = $data['image'];
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $data['image'] = $file->storeAs('faqs', $filename, 'public');
        }

        $deal->update($data);

        if (isset($data['service_variant_ids'])) {
            $deal->serviceVariants()->sync($data['service_variant_ids']);
        }

        return $deal;
    }



    public function delete($id)
    {
        $addon = SiteSetting::findOrFail($id);
        return $addon->delete(); // uses softDeletes
    }

    public function toggleStatus($id)
    {
        $deal = SiteSetting::findOrFail($id);

        $deal->status = $deal->status === SiteSetting::STATUS_ACTIVE
            ? SiteSetting::STATUS_INACTIVE
            : SiteSetting::STATUS_ACTIVE;

        $deal->save();

        return $deal;
    }

    public function bulkDelete(array $ids)
    {
        return SiteSetting::whereIn('id', $ids)->delete();
    }

    public function bulkStatus(array $ids, string $status)
    {
        return SiteSetting::whereIn('id', $ids)->update(['status' => $status]);
    }
}
