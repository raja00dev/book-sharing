<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OTP extends Model
{
    use SoftDeletes;

    protected $table = 'o_t_p_s';

    protected $fillable = [
        'user_id',
        'otp',
        'expire_at',
    ];

    protected $casts = [

        'expire_at' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
