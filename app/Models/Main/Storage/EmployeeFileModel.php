<?php

namespace App\Models\Main\Storage;

use App\Core\Config\ListDatabaseTable;
use App\Models\Main\Storage\ListFileTagModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EmployeeFileModel extends Model
{
    protected $table = ListDatabaseTable::TABLE_EMPLOYEE_FILES;
    protected $primaryKey = 'idEmployeeFile';
    protected $date_format = 'd.m.Y / H:i';

    protected $fillable = [
        'idEmployee', 'idFileTag',  'path', 'directory', 'filename', 'extension'
    ];

    public function getDirectory() {
        return $this->directory;
    }

    public function getPath() {
        return $this->path;
    }

    public function getFilename() {
        return $this->filename;
    }

    public function getSize() {
        if (Storage::exists($this->path)) {
            return round((Storage::size($this->path)/1024)/1024, 2);
        }

        return 0;
    }

    public function getExtension() {
        return $this->extension;
    }

    public function getFileTag() {
        return $this->hasOne(ListFileTagModel::class,'idFileTag', 'idFileTag')->first();
    }

    public function getCreatedDate() {
        return $this->created_at->format($this->date_format);
    }

    public function getUpdatedDate() {
        return $this->updated_at->format($this->date_format);
    }

}