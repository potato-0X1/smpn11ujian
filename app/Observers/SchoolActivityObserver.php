<?php
namespace App\Observers;
use App\Models\Exam;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class SchoolActivityObserver {
 public function created(Model $model):void {$this->record('created',$model);}
 public function updated(Model $model):void {$this->record('updated',$model);}
 public function deleted(Model $model):void {$this->record('deleted',$model);}
 private function record(string $action,Model $model):void {
  $type=match(true){$model instanceof Exam=>'exam',$model instanceof Student=>'student',default=>throw new \LogicException('Unknown audit subject')};
  DB::table('audit_logs')->insert(['actor_id'=>auth()->id(),'action'=>$action,'subject_type'=>$type,'subject_id'=>$model->getKey(),'created_at'=>now()]);
 }
}
