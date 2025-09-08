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

    public function getDatatableData()
    {
        try {
            $query = Faq::latest();

            return DataTables::of($query)

                // Checkbox for bulk delete
                ->addColumn(
                    'checkbox',
                    fn($row) =>
                    '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">'
                )

                // Question
                ->addColumn('question', function ($row) {
                    return strlen($row->question) > 100 ? substr($row->question, 0, 100) . '...' : $row->question;
                })

                // Status badge (active / inactive)
                ->editColumn('status', function ($row) {
                    return $row->status_badge; // uses model accessor
                })

                // Created At
                ->addColumn(
                    'created_at',
                    fn($row) =>
                    $row->created_at->format('d M Y, h:i A')
                )

                // Actions (edit, delete, view)
                ->addColumn(
                    'action',
                    fn($row) =>
                    view('admin.faqs.action', ['faq' => $row])->render()
                )

                ->rawColumns(['checkbox', 'status', 'action'])
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
        return Faq::whereIn('id', $ids)->delete();
    }

    public function bulkStatus(array $ids, string $status)
    {
        return Faq::whereIn('id', $ids)->update(['status' => $status]);
    }
}
