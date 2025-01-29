<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FileUpload extends Model
{
    protected $table = 'webshop';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function save(array $options=[])
    {
        $saved = parent::save($options);
    }
}