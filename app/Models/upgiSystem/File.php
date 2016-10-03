<?php

namespace App\Models\upgiSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\upgiSystem\System;

class File extends Model
{
    use SoftDeletes;
    
    protected $connection = 'DB_upgiSystem';
    protected $table = "file";
    protected $softDelete = true;

    public function getFileCode($id)
    {
        try{
            $data = $this->where('ID', $id)->first();
            $file = "data:$data->type;base64,$data->code";
            return $file; 
        } catch (\Exception $e) {
            return null;
        }
    }

    public function saveFile($file, $type, $name, $fe, $id)
    {
        try {
            $conn = $this->getConnection();
            $conn->beginTransaction();
            $data = file_get_contents($file);
            $code = base64_encode($data);
            $params =  array(
                'id' => $id,
                'type' => $type,
                'name' => $name,
                'fe' => $fe,
                'code' => $code,
            );
            $this->insert($params);
            $conn->commit();
        } catch (\Exception $e) {
            $conn->rollback();
            return false;
        }
        return true;
    }
}