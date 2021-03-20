<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'airline',
        'airline_id',
        'source_airport_id',
        'destination_airport_id',
        'codeshare',
        'stops',
        'equipment',
        'price',
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sourceAirport()
    {
        return $this->belongsTo(Airport::class, 'source_airport_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destinationAirport()
    {
        return $this->belongsTo(Airport::class, 'destination_airport_id');
    }
}
