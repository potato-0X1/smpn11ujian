<?php
namespace App\Filament\Resources\ExamCodes\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
class ExamCodesTable {public static function configure(Table $table):Table{return $table->columns([
 TextColumn::make('exam.title')->label('Ujian')->searchable()->sortable(),
 TextColumn::make('class_name')->label('Kelas')->badge()->sortable(),
 TextColumn::make('code')->label('Kode')->copyable()->copyMessage('Kode disalin')->searchable(),
 TextColumn::make('exam.status')->label('Status')->badge()->formatStateUsing(fn(string $state)=>$state==='active'?'Aktif':'Draf')->color(fn(string $state)=>$state==='active'?'success':'gray'),
]);}}
