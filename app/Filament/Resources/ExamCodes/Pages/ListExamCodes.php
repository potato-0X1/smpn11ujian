<?php
namespace App\Filament\Resources\ExamCodes\Pages;
use App\Filament\Resources\ExamCodes\ExamCodeResource;
use Filament\Resources\Pages\ListRecords;
class ListExamCodes extends ListRecords {protected static string $resource=ExamCodeResource::class;protected function getHeaderActions():array{return [];}}
