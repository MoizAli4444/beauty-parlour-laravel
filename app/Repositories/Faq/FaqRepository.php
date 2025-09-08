<?php

namespace App\Repositories\Faq;

use App\Models\Faq;

use App\Repositories\Faq\FaqRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\TracksUser;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class FaqRepository implements FaqRepositoryInterface
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
            $query = Faq::with(['creator', 'updater'])
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
                            class="rounded border js-media-preview" 
                            data-url="' . $url . '" 
                            data-type="image"
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
                    view('admin.faqs.action', ['deal' => $row])->render()
                )

                ->rawColumns(['checkbox', 'status', 'image_preview', 'validity', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function all()
    {
        return Faq::latest()->get();
    }

    public function find($id)
    {
        return Faq::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return Faq::where('slug', $slug)->first();
    }


    public function create(array $data)
    {
        $data = $this->addCreatedBy($data);

        if (isset($data['image'])) {
            $file = $data['image'];
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $data['image'] = $file->storeAs('faqs', $filename, 'public');
        }

        $deal = Faq::create($data);

        if (!empty($data['service_variant_ids'])) {
            $deal->serviceVariants()->attach($data['service_variant_ids']);
        }

        return $deal;
    }


    public function update($id, array $data)
    {
        $deal = Faq::findOrFail($id);
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
        $deal = Faq::findOrFail($id);

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
        $deal = Faq::findOrFail($id);

        $deal->status = $deal->status === Faq::STATUS_ACTIVE
            ? Faq::STATUS_INACTIVE
            : Faq::STATUS_ACTIVE;

        $deal->save();

        return $deal;
    }

    public function bulkDelete(array $ids)
    {
        $faqs = Faq::whereIn('id', $ids)->get();

        foreach ($faqs as $deal) {
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
        return Faq::whereIn('id', $ids)->update(['status' => $status]);
    }
}
