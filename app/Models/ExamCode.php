<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ExamCode extends Model {
 public $timestamps=false;
 protected $fillable=['exam_id','class_name','code'];
 public function exam():BelongsTo{return $this->belongsTo(Exam::class);}
}
