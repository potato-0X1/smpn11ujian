<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Exam extends Model {
 protected $fillable=['title','subject','form_url','exam_date','status'];
 protected function casts():array{return ['exam_date'=>'date'];}
 public function codes():HasMany{return $this->hasMany(ExamCode::class);}
}
