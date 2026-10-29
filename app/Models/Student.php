<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Student extends Model {
 protected $fillable=['nis','name','class_name','password'];
 protected $hidden=['password'];
 protected function casts():array{return ['password'=>'hashed'];}
}
