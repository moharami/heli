<?php

namespace App\Models;

use App\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $attributes = [
        'status' => StatusEnum::PENDING,
    ];

}
