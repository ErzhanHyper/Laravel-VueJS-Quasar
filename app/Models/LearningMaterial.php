<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningMaterial extends Model
{
    use HasFactory;

    protected $table = 'learning_materials';

    protected $guarded = [];

    public function catalogs(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(LearningMaterialCatalog::class, 'catalog_id', 'id');
    }

    public function file_type(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(FileType::class, 'file_type_id', 'id');
    }
}
