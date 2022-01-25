<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharityTicker extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'charity_organization_id',
        'donation_amount',
        'tick_frequency',
        'tick_frequency_unit',
        'total_donation_amount',
        'is_subscribed',
        'timezone',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'donation_amount' => 'double',
        'total_donation_amount' => 'double',
    ];

    /**
     * Get user information.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get event information.
     */
    public function charity_organization()
    {
        return $this->belongsTo(CharityOrganization::class, 'charity_organization_id', 'id');
    }
}
