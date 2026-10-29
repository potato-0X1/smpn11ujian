<?php
namespace App\Filament\Resources\AuditLogs\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
class AuditLogsTable {
 public static function configure(Table $table):Table{return $table->columns([
  TextColumn::make('created_at')->label('Waktu (WIB)')->dateTime('d M Y H:i:s', 'Asia/Jakarta')->sortable(),
  TextColumn::make('actor.name')->label('Guru')->placeholder('Sistem / akun dihapus'),
  TextColumn::make('action')->label('Tindakan')->badge()->formatStateUsing(fn(string $state)=>match($state){'created'=>'Dibuat','updated'=>'Diubah','deleted'=>'Dihapus',default=>$state}),
  TextColumn::make('subject_type')->label('Data')->formatStateUsing(fn(string $state)=>match($state){'exam'=>'Ujian','student'=>'Siswa',default=>$state}),
  TextColumn::make('subject_id')->label('ID data')->placeholder('—'),
 ])->filters([SelectFilter::make('subject_type')->label('Jenis data')->options(['exam'=>'Ujian','student'=>'Siswa'])])->defaultSort('id','desc');}
}
