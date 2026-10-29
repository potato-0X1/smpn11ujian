<?php
namespace App\Filament\Widgets;
use App\Models\Exam;
use App\Models\ExamCode;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Icons\Heroicon;
class SchoolStats extends StatsOverviewWidget {
 protected function getStats():array{return [
  Stat::make('Total ujian',Exam::count())->icon(Heroicon::OutlinedDocumentText)->color('primary'),
  Stat::make('Ujian aktif',Exam::where('status','active')->count())->icon(Heroicon::OutlinedPlayCircle)->color('success'),
  Stat::make('Total siswa',Student::count())->icon(Heroicon::OutlinedUsers)->color('info'),
  Stat::make('Kode kelas',ExamCode::count())->icon(Heroicon::OutlinedKey)->color('warning'),
 ];}
 protected function getColumns():int|array|null{return 4;}
}
