<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactMessage extends Model
{
    use HasFactory, SoftDeletes;

    // ✅ Fillable fields
    protected $fillable = [
        'name',
        'phone',
        'email',
        'subject',
        'message',
        'priority',
        'status',
        'response',
    ];

    // ✅ Casts
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ✅ Accessor for status badge
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'  => '<span class="badge bg-primary">Pending</span>',
            'in_progress' => '<span class="badge bg-info">In Progress</span>',
            'closed'   => '<span class="badge bg-success">Closed</span>',
            default    => '<span class="badge bg-dark">Unknown</span>',
        };
    }

    // ✅ Accessor for priority badge
    public function getPriorityBadgeAttribute(): string
    {
        return match ($this->priority) {
            'low'    => '<span class="badge bg-info">Low</span>',
            'medium' => '<span class="badge bg-primary">Medium</span>',
            'high'   => '<span class="badge bg-danger">High</span>',
            default  => '<span class="badge bg-dark">Unknown</span>',
        };
    }

    // ✅ Helper for combined contact info
    public function getContactInfoAttribute(): string
    {
        
        $phone = $this->phone ? "{$this->phone}" : '';
        $email = $this->email ? "{$this->email}" : '';
        return trim("<small> Phone: {$phone} </small><br> <small> Email: {$email} </small>");
    }

     ///////////// fixed model functions //////////////

    public function getEditButtonAttribute()
    {
        return render_edit_button(route('contact-messages.edit', $this->id));
    }

    public function getViewButtonAttribute()
    {
        return render_view_button(route('contact-messages.show', $this->id));
    }


    public function getDeleteButtonAttribute()
    {
        return render_delete_button($this->id, route('contact-messages.destroy', $this->id));
    }

    ///////////// fixed model functions //////////////
}
