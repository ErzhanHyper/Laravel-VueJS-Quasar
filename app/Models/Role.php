<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status'
    ];
    public function permission(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(Permission::class, 'role_id', 'id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }

}
