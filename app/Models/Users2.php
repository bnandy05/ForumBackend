<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users2 extends Model
{
    protected $table = 'Users';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function save(array $options=[])
    {
        $saved = parent::save($options);
    }
}