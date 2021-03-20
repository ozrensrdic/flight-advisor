<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;

class City extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'country',
        'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @return HasMany
     */
    public function airports(): HasMany
    {
        return $this->hasMany(Airport::class);
    }

    /**
     * @return HasMany
     */
    public function comments(int $limit = 5): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @param int $limit
     * @return Collection
     */
    public function getCommentsPreview(int $limit = 5): Collection
    {
        return $this->hasMany(Comment::class)->limit($limit)->orderByDesc('updated_at')->get();
    }
}
