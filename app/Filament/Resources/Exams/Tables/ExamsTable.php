<?php
namespace App\Filament\Resources\Exams\Tables;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
class ExamsTable {public static function configure(Table $table):Table{return $table->columns([
 TextColumn::make('title')->label('Nama ujian')->searchable()->sortable(),
 TextColumn::make('subject')->label('Mata pelajaran')->searchable(),
 TextColumn::make('exam_date')->label('Tanggal')->date('d M Y')->sortable(),
 TextColumn::make('codes.class_name')->label('Kelas')->badge(),
 TextColumn::make('status')->label('Status')->badge()->formatStateUsing(fn(string $state)=>$state==='active'?'Aktif':'Draf')->color(fn(string $state)=>$state==='active'?'success':'gray'),
])->defaultSort('exam_date','desc')->recordActions([EditAction::make()->label('Ubah'),DeleteAction::make()->label('Hapus')]);}}
