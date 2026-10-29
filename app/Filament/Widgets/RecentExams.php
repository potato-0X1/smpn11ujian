<?php
namespace App\Filament\Widgets;
use App\Models\Exam;
use Filament\Widgets\TableWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
class RecentExams extends TableWidget {
 protected int|string|array $columnSpan='full';
 public function table(Table $table):Table{return $table->heading('Ujian terbaru')->query(Exam::query()->latest())->columns([
 TextColumn::make('title')->label('Nama ujian')->searchable(),
 TextColumn::make('subject')->label('Mata pelajaran'),
 TextColumn::make('exam_date')->label('Tanggal')->date('d M Y'),
 TextColumn::make('codes.class_name')->label('Kelas')->badge(),
 TextColumn::make('status')->label('Status')->badge()->formatStateUsing(fn(string $state)=>$state==='active'?'Aktif':'Draf')->color(fn(string $state)=>$state==='active'?'success':'gray'),
 ])->paginated([5]);}
}
