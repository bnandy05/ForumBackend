<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function save(array $options=[])
    {
        $saved = parent::save($options);
    }
}