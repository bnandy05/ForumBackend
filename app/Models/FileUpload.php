<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FileUpload extends Model
{
    protected $table = 'file_uploads';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'filename',
        'path',
        'user_id'
    ];

    public function save(array $options=[])
    {
        $saved = parent::save($options);
    }
}