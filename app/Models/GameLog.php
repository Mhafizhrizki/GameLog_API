<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rawg_id',
        'title',
        'status',
        'personal_rating'
    ];

    // Sembunyikan user_id dari response JSON agar persis seperti contoh Github
    protected $hidden = ['user_id'];

    /**
     * Cast attributes to native types.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rawg_id'         => 'integer',
            'personal_rating' => 'integer',
        ];
    }

    /**
     * Get the user that owns the game log.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}