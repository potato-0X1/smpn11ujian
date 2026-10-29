<?php
namespace App\Filament\Resources\AuditLogs\Pages;
use App\Filament\Resources\AuditLogs\AuditLogResource;
use Filament\Resources\Pages\ListRecords;
class ListAuditLogs extends ListRecords {
 protected static string $resource=AuditLogResource::class;
 protected function getHeaderActions():array{return [];}
 public function getSubheading():?string{return 'Riwayat perubahan data siswa dan ujian. Bukan log aktivitas ujian siswa.';}
}
