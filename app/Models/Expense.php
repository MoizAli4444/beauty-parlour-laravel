<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'expense_type',
        'amount',
        'payment_method',
        'date',
        'receipt_path',
        'notes',
        'moderator_id',
        'moderator_type',
    ];

    /**
     * Polymorphic relation - who/what the expense was paid to (Admin, Staff, Employee, Supplier, etc.).
     */
    public function moderator()
    {
        return $this->morphTo();
    }


    ///////////// fixed model functions //////////////
    

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getStatusBadgeAttribute()
    {
        return render_status_badge($this->status, $this->id, route('expenses.toggle-status', $this->id));
    }

    public function getEditButtonAttribute()
    {
        return render_edit_button(route('expenses.edit', $this->id));
    }

    public function getViewButtonAttribute()
    {
        return render_view_button(route('expenses.show', $this->id));
    }


    public function getDeleteButtonAttribute()
    {
        return render_delete_button($this->id, route('expenses.destroy', $this->id));
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    ///////////// fixed model functions //////////////
}
