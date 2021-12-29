<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharityOrganization extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get charity tickers information.
     */
    public function charity_tickers()
    {
        return $this->hasMany(CharityTicker::class, 'charity_organization_id', 'id');
    }
}
