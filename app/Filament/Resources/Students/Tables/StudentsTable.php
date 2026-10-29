<?php
namespace App\Filament\Resources\Students\Tables;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
class StudentsTable {public static function configure(Table $table):Table{return $table->columns([
 TextColumn::make('name')->label('Nama siswa')->searchable()->sortable(),
 TextColumn::make('nis')->label('NIS')->searchable()->sortable(),
 TextColumn::make('class_name')->label('Kelas')->badge()->sortable(),
])->filters([SelectFilter::make('class_name')->label('Kelas')->options(array_combine(['7A','7B','7C','8A','8B','8C','9A','9B','9C'],['7A','7B','7C','8A','8B','8C','9A','9B','9C']))])->recordActions([EditAction::make()->label('Ubah'),DeleteAction::make()->label('Hapus')]);}}
