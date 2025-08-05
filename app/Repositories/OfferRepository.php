<?php

namespace App\Repositories;

use App\Interfaces\OfferRepositoryInterface;
use App\Models\Offer;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\View;
use App\Traits\TracksUser;
use Carbon\Carbon;

class OfferRepository  implements OfferRepositoryInterface
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
            return DataTables::of(Offer::query()->latest())

                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })

                ->editColumn('name', function ($row) {
                    return strlen($row->name) > 20 ? substr($row->name, 0, 20) . '...' : $row->name;
                })

                ->editColumn('starts_at', function ($row) {
                    return Carbon::parse($row->starts_at)->format('d M Y, h:i A'); // Example: 29 Jun 2025, 10:30 AM
                })
                ->editColumn('ends_at', function ($row) {
                    return Carbon::parse($row->ends_at)->format('d M Y, h:i A'); // Example: 05 Aug 2025, 03:15 PM
                })

                ->editColumn('type', function ($row) {
                    return ucfirst($row->type);
                })

                ->editColumn('value', function ($row) {
                    return $row->formatted_value;
                })

                ->editColumn('status', function ($row) {
                    // dd($row->status);
                    return $row->status_badge; // uses model accessor
                })

                ->editColumn('offer_code', function ($deal) {
                    return $deal->offer_code_badge;
                })

                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y'); // Example: 29 Jun 2025
                })

                ->addColumn('action', function ($row) {
                    return view('admin.offer.action', ['offer' => $row])->render();
                })
                ->rawColumns(['checkbox', 'action', 'status', 'offer_code']) // allow HTML rendering
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function all()
    {
        return Offer::latest()->get();
    }

    public function find($id)
    {
        return Offer::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return Offer::where('slug', $slug)->first();
    }


    public function create(array $data)
    {
        $data = $this->addCreatedBy($data);
        return Offer::create($data);
    }

    public function update($id, array $data)
    {
        $offer = Offer::findOrFail($id);
        $data = $this->addUpdatedBy($data);
        $offer->update($data);
        return $offer;
    }

    public function delete($id)
    {
        $offer = Offer::findOrFail($id);
        return $offer->delete(); // uses softDeletes
    }

    public function toggleStatus($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->status = $offer->status === 'active' ? 'inactive' : 'active';
        $offer->save();

        return $offer;
    }

    public function bulkDelete(array $ids)
    {
        return Offer::whereIn('id', $ids)->delete();
    }

    public function bulkStatus(array $ids, string $status)
    {
        return Offer::whereIn('id', $ids)->update(['status' => $status]);
    }
}
