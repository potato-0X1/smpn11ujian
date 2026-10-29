<?php
namespace App\Filament\Resources\Students\Schemas;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
class StudentForm {public static function configure(Schema $schema):Schema{return $schema->components([
 TextInput::make('name')->label('Nama lengkap')->required()->maxLength(120),
 TextInput::make('nis')->label('NIS')->required()->maxLength(30)->regex('/^[a-zA-Z0-9-]+$/')->unique(ignoreRecord:true),
 Select::make('class_name')->label('Kelas')->options(array_combine(['7A','7B','7C','8A','8B','8C','9A','9B','9C'],['7A','7B','7C','8A','8B','8C','9A','9B','9C']))->required(),
 TextInput::make('password')->label('Kata sandi')->password()->revealable()->minLength(8)->maxLength(128)->required(fn (string $operation): bool => $operation === 'create')->dehydrated(fn($state)=>filled($state))->helperText('Saat mengubah data, kosongkan untuk mempertahankan kata sandi.'),
]);}}
