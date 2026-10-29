<?php
namespace App\Filament\Resources\Exams\Pages;
use App\Filament\Resources\Exams\ExamResource;
use App\Models\Exam;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class CreateExam extends CreateRecord {
 protected static string $resource=ExamResource::class;
 protected function handleRecordCreation(array $data): Model {
  $classes=$data['classes']??[];
  if(!$classes || count(array_diff($classes,['7A','7B','7C','8A','8B','8C','9A','9B','9C'])) || count($classes)!==count(array_unique($classes))) throw ValidationException::withMessages(['data.classes'=>'Pilih kelas yang valid.']);
  unset($data['classes']);
  return DB::transaction(function()use($data,$classes){$exam=Exam::create($data);foreach($classes as $class){$exam->codes()->create(['class_name'=>$class,'code'=>$class.'-'.strtoupper(bin2hex(random_bytes(4)))]);}return $exam;});
 }
}
