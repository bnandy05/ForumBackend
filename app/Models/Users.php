<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    public $timestamps = false;
    

    protected $fillable = [
        'is_banned',
        'is_admin',
        'password'
    ];

    public function save(array $options=[])
    {
        $saved = parent::save($options);
    }
}