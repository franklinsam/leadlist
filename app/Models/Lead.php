<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'source',
        'notes',
        'status',
        'value',
        'expected_close_date',
        'assigned_to',
    ];

    protected $casts = [
        'expected_close_date' => 'date',
        'value' => 'decimal:2',
    ];

    public static function getStatusOptions()
    {
        return [
            'new' => 'New',
            'contacted' => 'Contacted',
            'qualified' => 'Qualified',
            'proposal' => 'Proposal',
            'negotiation' => 'Negotiation',
            'closed_won' => 'Closed Won',
            'closed_lost' => 'Closed Lost',
        ];
    }

    public function getStatusBadgeAttribute()
    {
        $colors = [
            'new' => 'primary',
            'contacted' => 'info',
            'qualified' => 'success',
            'proposal' => 'warning',
            'negotiation' => 'dark',
            'closed_won' => 'success',
            'closed_lost' => 'danger',
        ];

        return '<span class="badge bg-' . ($colors[$this->status] ?? 'secondary') . '">' 
               . ucfirst($this->status) . '</span>';
    }
} 