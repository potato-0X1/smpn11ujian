<?php
namespace App\Filament\Resources\Exams\Schemas;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
class ExamForm {
 public static function configure(Schema $schema): Schema { return $schema->components([
  TextInput::make('title')->label('Nama ujian')->required()->maxLength(150),
  TextInput::make('subject')->label('Mata pelajaran')->required()->maxLength(100),
  TextInput::make('form_url')->label('Tautan Google Form')->url()->required()->maxLength(2048)->rules([function () {return function(string $attribute,$value,\Closure $fail):void {$p=parse_url($value); if(($p['scheme']??'')!=='https'||!in_array(strtolower($p['host']??''),['docs.google.com','forms.gle'],true)) $fail('Gunakan tautan HTTPS docs.google.com atau forms.gle.');};}])->helperText('Hanya tautan docs.google.com atau forms.gle.'),
  DatePicker::make('exam_date')->label('Tanggal ujian')->required()->native(false),
  Select::make('status')->label('Status')->options(['draft'=>'Draf','active'=>'Aktif'])->default('draft')->required(),
  CheckboxList::make('classes')->label('Kelas peserta')->options(array_combine(['7A','7B','7C','8A','8B','8C','9A','9B','9C'],['7A','7B','7C','8A','8B','8C','9A','9B','9C']))->columns(3)->required()->visibleOn('create')->helperText('Kode berbeda dibuat otomatis untuk tiap kelas. Pilih dengan teliti; kelas tidak bisa diedit pada versi ini.')
 ]); }
}
