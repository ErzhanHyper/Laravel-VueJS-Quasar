<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningMaterialCatalog extends Model
{
    use HasFactory;

    protected $table = 'learning_material_catalogs';

    protected $guarded = [];

    public function learning_material(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(LearningMaterial::class, 'catalog_id', 'id');
    }

}
