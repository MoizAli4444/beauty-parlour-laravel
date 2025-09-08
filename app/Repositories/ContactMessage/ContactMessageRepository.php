<?php

namespace App\Repositories\ContactMessage;

use App\Models\ContactMessage;

use App\Repositories\ContactMessage\ContactMessageRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\TracksUser;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ContactMessageRepository implements ContactMessageRepositoryInterface
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
            $query = ContactMessage::latest();

            // ✅ Filters
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (!empty($filters['priority'])) {
                $query->where('priority', $filters['priority']);
            }

            if (!empty($filters['start_date'])) {
                $query->whereDate('created_at', '>=', $filters['start_date']);
            }

            if (!empty($filters['end_date'])) {
                $query->whereDate('created_at', '<=', $filters['end_date']);
            }

            // ✅ DataTable response
            return DataTables::of($query)

                // Checkbox
                ->addColumn(
                    'checkbox',
                    fn($row) =>
                    '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">'
                )

                // Customer Info
                ->addColumn('customer', function ($row) {
                    return '<strong>' . e($row->name) . '</strong><br>' .
                        ($row->contact_info);
                })
                ->filterColumn('customer', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%")
                            ->orWhere('phone', 'like', "%{$keyword}%");
                    });
                })


                // Subject
                ->editColumn(
                    'subject',
                    fn($row) =>
                    $row->subject ? e($row->subject) : '<em>No Subject</em>'
                )

                // Message (trimmed)
                ->addColumn('message', function ($row) {
                    return strlen($row->message) > 50
                        ? substr($row->message, 0, 50) . '...'
                        : $row->message;
                })

                // Priority badge
                ->editColumn('priority', function ($row) {
                    return $row->priority_badge;
                })

                // Status badge
                ->editColumn('status', function ($row) {
                    return $row->status_badge;
                })

                // Created At
                ->addColumn(
                    'created_at',
                    fn($row) =>
                    $row->created_at->format('d M Y, h:i A')
                )

                // Actions
                ->addColumn(
                    'action',
                    fn($row) =>
                    view('admin.contact-messages.action', ['message' => $row])->render()
                )

                ->rawColumns(['checkbox', 'customer', 'subject', 'priority', 'status', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function all()
    {
        return ContactMessage::latest()->get();
    }

    public function find($id)
    {
        return ContactMessage::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return ContactMessage::where('slug', $slug)->first();
    }


    public function create(array $data)
    {
        // $data = $this->addCreatedBy($data);

        return ContactMessage::create($data);
    }


    public function update($id, array $data)
    {
        $deal = ContactMessage::findOrFail($id);
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
        $addon = ContactMessage::findOrFail($id);
        return $addon->delete(); // uses softDeletes
    }

    public function toggleStatus($id)
    {
        $deal = ContactMessage::findOrFail($id);

        // $deal->status = $deal->status === ContactMessage::STATUS_ACTIVE
        //     ? ContactMessage::STATUS_INACTIVE
        //     : ContactMessage::STATUS_ACTIVE;

        $deal->save();

        return $deal;
    }

    public function bulkDelete(array $ids)
    {
        return ContactMessage::whereIn('id', $ids)->delete();
    }

    public function bulkStatus(array $ids, string $status)
    {
        return ContactMessage::whereIn('id', $ids)->update(['status' => $status]);
    }
}
