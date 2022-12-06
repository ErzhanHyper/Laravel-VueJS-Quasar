<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocsContract extends Model
{
    use HasFactory;

    public function file(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(DocsContractFile::class, 'docs_contract_id', 'id');
    }

    public function agent(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }

    public function department(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
