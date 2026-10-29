<?php
namespace App\Filament\Resources\ExamCodes;
use App\Filament\Resources\ExamCodes\Pages\ListExamCodes;
use App\Filament\Resources\ExamCodes\Tables\ExamCodesTable;
use App\Models\ExamCode;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
class ExamCodeResource extends Resource {
 protected static ?string $model=ExamCode::class;
 protected static string|BackedEnum|null $navigationIcon=Heroicon::OutlinedKey;
 protected static ?string $navigationLabel='Kode ujian';
 protected static ?string $modelLabel='kode ujian';
 protected static ?string $pluralModelLabel='kode ujian';
 protected static ?int $navigationSort=2;
 public static function table(Table $table):Table{return ExamCodesTable::configure($table);}
 public static function getPages():array{return ['index'=>ListExamCodes::route('/')];}
}
