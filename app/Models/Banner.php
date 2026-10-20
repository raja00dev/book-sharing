<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'text',
        'image',
        'isBig',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'isBig' => 'boolean',
            'status' => 'boolean',
        ];
    }
}
