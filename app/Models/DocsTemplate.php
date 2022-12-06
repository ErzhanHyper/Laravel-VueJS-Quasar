<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DocsTemplateFiles
 *
 * @property int $id
 * @property int $file_type_id
 * @property string $name
 **/

class DocsTemplate extends Model
{
    use HasFactory;

    public function files(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(DocsTemplateFiles::class, 'docs_template_id', 'id');
    }

    public function docs_template_file(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(DocsTemplateFiles::class, 'docs_template_id', 'id')->orderByDesc('created_at')->latest();
    }

}
