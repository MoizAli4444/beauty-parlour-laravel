<?php

namespace App\Repositories\Deal;

use App\Models\Deal;

use App\Repositories\Deal\DealRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\TracksUser;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DealRepository implements DealRepositoryInterface
{

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    use TracksUser;

    public function getDatatableData(array $filters)
    {
        try {
            $query = Deal::with(['creator', 'updater'])
                ->latest();

            // ✅ Filters
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (!empty($filters['start_date'])) {
                $query->whereDate('start_date', '>=', $filters['start_date']);
            }

            if (!empty($filters['end_date'])) {
                $query->whereDate('end_date', '<=', $filters['end_date']);
            }

            if (!empty($filters['created_by'])) {
                $query->where('created_by', $filters['created_by']);
            }

            // ✅ DataTable response
            return DataTables::of($query)

                ->addColumn(
                    'checkbox',
                    fn($row) =>
                    '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">'
                )

                ->addColumn('id', fn($row) => $row->id)

                ->addColumn('name', fn($row) => $row->name)

                ->addColumn('slug', fn($row) => $row->slug)

                ->addColumn('price', fn($row) => number_format($row->price, 2))

                ->addColumn(
                    'services_total',
                    fn($row) =>
                    $row->services_total
                        ? number_format($row->services_total, 2)
                        : 'N/A'
                )

                ->editColumn('status', function ($row) {
                    return $row->status_badge; // uses model accessor
                })

                ->addColumn(
                    'start_date',
                    fn($row) => $row->start_date ? $row->start_date->format('d M Y') : 'N/A'
                )

                ->addColumn(
                    'end_date',
                    fn($row) => $row->end_date ? $row->end_date->format('d M Y') : 'N/A'
                )

                ->addColumn('validity', function ($row) {
                    $start = $row->start_date ? $row->start_date->format('d M Y') : 'N/A';
                    $end = $row->end_date ? $row->end_date->format('d M Y') : 'N/A';
                    return $start . ' - ' . $end;
                })


                ->addColumn('image_preview', function ($row) {
                    if (!$row->image) {
                        return 'N/A';
                    }

                    $url = asset('storage/' . $row->image);
                    return '<img src="' . $url . '" width="60" height="60" 
                class="rounded border" 
                style="object-fit:cover;cursor:pointer;">';
                })


                ->addColumn(
                    'created_by',
                    fn($row) => $row->creator?->name ?? 'N/A'
                )

                ->addColumn(
                    'updated_by',
                    fn($row) => $row->updater?->name ?? 'N/A'
                )

                ->addColumn(
                    'created_at',
                    fn($row) => $row->created_at->format('d M Y')
                )

                ->addColumn(
                    'action',
                    fn($row) =>
                    view('admin.deals.action', ['deal' => $row])->render()
                )

                ->rawColumns(['checkbox', 'status', 'image_preview', 'validity', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function all()
    {
        return Deal::latest()->get();
    }

    public function find($id)
    {
        return Deal::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return Deal::where('slug', $slug)->first();
    }


    public function create(array $data)
    {
        $data = $this->addCreatedBy($data);

        if (isset($data['image'])) {
            $file = $data['image'];
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $data['image'] = $file->storeAs('deals', $filename, 'public');
        }

        $deal = Deal::create($data);

        if (!empty($data['service_variant_ids'])) {
            $deal->serviceVariants()->attach($data['service_variant_ids']);
        }

        return $deal;
    }


    public function update($id, array $data)
    {
        $deal = Deal::findOrFail($id);
        $data = $this->addUpdatedBy($data);

        if (isset($data['file'])) {
            $file = $data['file'];
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $data['image'] = $file->storeAs('uploads/deals', $filename, 'public');
            unset($data['file']);
        }

        $deal->update($data);

        if (isset($data['service_variant_ids'])) {
            $deal->serviceVariants()->sync($data['service_variant_ids']);
        }

        return $deal;
    }



    public function delete($id)
    {
        $deal = Deal::findOrFail($id);

        // remove image if exists
        if ($deal->image && Storage::disk('public')->exists($deal->image)) {
            Storage::disk('public')->delete($deal->image);
        }

        // detach services
        $deal->serviceVariants()->detach();

        return $deal->delete();
    }

    public function toggleStatus($id)
    {
        $deal = Deal::findOrFail($id);

        $deal->status = $deal->status === Deal::STATUS_ACTIVE
            ? Deal::STATUS_INACTIVE
            : Deal::STATUS_ACTIVE;

        $deal->save();

        return $deal;
    }

    public function bulkDelete(array $ids)
    {
        $deals = Deal::whereIn('id', $ids)->get();

        foreach ($deals as $deal) {
            // remove image if exists
            if ($deal->image && Storage::disk('public')->exists($deal->image)) {
                Storage::disk('public')->delete($deal->image);
            }

            // detach related services
            $deal->serviceVariants()->detach();

            $deal->delete();
        }

        return true;
    }

    public function bulkStatus(array $ids, string $status)
    {
        return Deal::whereIn('id', $ids)->update(['status' => $status]);
    }
}
