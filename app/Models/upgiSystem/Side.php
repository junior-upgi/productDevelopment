<?php

namespace App\Models\upgiSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\upgiSystem\System;

class Side extends Model
{
    use SoftDeletes;
    
    protected $connection = 'DB_upgiSystem';
    protected $table = "side";
    protected $softDelete = true;

    public function getSystem()
    {
        return $this->hasOne(System::class, 'ID', 'system');
    }
    public function getParent()
    {
        return $this->hasOne(Side::class, 'ID', 'parentID');
    }
    public function search($Search)
    {
        return $this
            ->whereHas('getSystem', function ($q) use ($Search) {
                $q->Where('systemName', 'like', "%{$Search}%");
            })
            ->orWhere('sideName', 'like', "%{$Search}%")
            ->with(['getSystem', 'getParent']);
    }
    public function getBySystem($SystemID)
    {
        is_null($SystemID) ? $SystemID = 1 : true;
        return $this->where('system', $SystemID)->with(['getSystem', 'getParent'])->orderBy('seq');
    }
    public function maxSeq($SystemID)
    {
        return $this->where('system', $SystemID)->max('seq');
    }
}