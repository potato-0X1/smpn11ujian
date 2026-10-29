<?php
namespace Tests\Feature;
use App\Filament\Resources\Exams\Pages\CreateExam;
use App\Filament\Resources\Students\Pages\CreateStudent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;
class SchoolDashboardTest extends TestCase {
 use RefreshDatabase;
 public function test_guest_is_redirected_from_admin():void {$this->get('/admin')->assertRedirect('/admin/login');}
 public function test_teacher_can_view_panel_and_resources():void {$this->actingAs(User::factory()->create(['is_teacher'=>true,'app_authentication_secret'=>'JBSWY3DPEHPK3PXP']));foreach(['/admin','/admin/exams','/admin/students','/admin/exam-codes','/admin/audit-logs','/admin/exams/create','/admin/students/create'] as $p)$this->get($p)->assertOk();}
 public function test_creating_exam_creates_class_codes():void {$this->actingAs(User::factory()->create(['is_teacher'=>true,'app_authentication_secret'=>'JBSWY3DPEHPK3PXP']));Livewire::test(CreateExam::class)->fillForm(['title'=>'Ujian IPA','subject'=>'IPA','form_url'=>'https://docs.google.com/forms/d/example/viewform','exam_date'=>'2026-11-01','status'=>'draft','classes'=>['7A','7B','9C']])->call('create')->assertHasNoFormErrors();$this->assertDatabaseCount('exams',1);$this->assertDatabaseCount('exam_codes',3);$this->assertCount(3,DB::table('exam_codes')->pluck('code')->unique());$this->assertStringStartsWith('7A-',DB::table('exam_codes')->where('class_name','7A')->value('code'));}
 public function test_untrusted_url_is_rejected():void {$this->actingAs(User::factory()->create(['is_teacher'=>true,'app_authentication_secret'=>'JBSWY3DPEHPK3PXP']));Livewire::test(CreateExam::class)->fillForm(['title'=>'IPA','subject'=>'IPA','form_url'=>'https://docs.google.com.attacker.test/form','exam_date'=>'2026-11-01','status'=>'draft','classes'=>['7A']])->call('create')->assertHasFormErrors(['form_url']);$this->assertDatabaseCount('exams',0);}
 public function test_student_password_is_hashed():void {$this->actingAs(User::factory()->create(['is_teacher'=>true,'app_authentication_secret'=>'JBSWY3DPEHPK3PXP']));Livewire::test(CreateStudent::class)->fillForm(['name'=>'Siswa Demo','nis'=>'12345','class_name'=>'7A','password'=>'kata-sandi-aman'])->call('create')->assertHasNoFormErrors();$hash=DB::table('students')->where('nis','12345')->value('password');$this->assertNotEquals('kata-sandi-aman',$hash);$this->assertTrue(Hash::check('kata-sandi-aman',$hash));}
 public function test_non_teacher_cannot_access_panel():void { $this->actingAs(User::factory()->create(['is_teacher'=>false])); $this->get('/admin')->assertForbidden(); }
 public function test_teacher_without_mfa_must_enroll():void { $this->actingAs(User::factory()->create(['is_teacher'=>true])); $this->get('/admin')->assertRedirectContains('multi-factor-authentication'); }
 public function test_mfa_secret_is_encrypted_and_hidden():void { $u=User::factory()->create(['is_teacher'=>true,'app_authentication_secret'=>'JBSWY3DPEHPK3PXP']); $stored=DB::table('users')->where('id',$u->id)->value('app_authentication_secret'); $this->assertNotEquals('JBSWY3DPEHPK3PXP',$stored); $this->assertArrayNotHasKey('app_authentication_secret',$u->toArray()); }
 public function test_student_changes_write_audit_without_passwords():void {
  $teacher=User::factory()->create(['is_teacher'=>true,'app_authentication_secret'=>'JBSWY3DPEHPK3PXP']);
  $this->actingAs($teacher);
  Livewire::test(CreateStudent::class)->fillForm(['name'=>'Siswa Demo','nis'=>'54321','class_name'=>'7A','password'=>'secret-pass-2026'])->call('create')->assertHasNoFormErrors();
  $student=\App\Models\Student::where('nis','54321')->firstOrFail();
  $this->assertDatabaseHas('audit_logs',['actor_id'=>$teacher->id,'action'=>'created','subject_type'=>'student','subject_id'=>$student->id]);
  $student->update(['class_name'=>'7B']);$student->delete();
  $this->assertDatabaseHas('audit_logs',['actor_id'=>$teacher->id,'action'=>'updated','subject_type'=>'student','subject_id'=>$student->id]);
  $this->assertDatabaseHas('audit_logs',['actor_id'=>$teacher->id,'action'=>'deleted','subject_type'=>'student','subject_id'=>$student->id]);
  $audit=DB::table('audit_logs')->where('subject_type','student')->get()->toJson();
  $this->assertStringNotContainsString('secret-pass-2026',$audit);
  $this->assertStringNotContainsString('Siswa Demo',$audit);
 }
}
