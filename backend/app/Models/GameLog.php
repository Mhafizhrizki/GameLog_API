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

    protected $hidden = ['user_id'];

    protected function casts(): array
    {
        return [
            'rawg_id'         => 'integer',
            'personal_rating' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
