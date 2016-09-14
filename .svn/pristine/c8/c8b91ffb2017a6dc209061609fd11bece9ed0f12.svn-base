<?php

namespace App\Models\upgiSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\upgiSystem\Side;

class System extends Model
{
    use SoftDeletes;
    
    protected $connection = 'DB_upgiSystem';
    protected $table = "system";
    protected $softDelete = true;

    public function Side()
    {
        return $this->belongsTo(Side::class, 'system', 'ID');
    }
}