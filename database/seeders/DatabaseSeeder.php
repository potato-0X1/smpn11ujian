<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder {
 public function run(): void {
  if (app()->environment('production')) throw new \RuntimeException('Demo seeder tidak boleh dijalankan di production.');
  DB::table('users')->updateOrInsert(['email'=>'guru@demo.test'],['is_teacher'=>true,'name'=>'Guru Demo','password'=>Hash::make('DemoGuru2026!'),'created_at'=>now(),'updated_at'=>now()]);
  if(DB::table('exams')->count()===0){foreach([['Penilaian Harian Matematika','Matematika','2026-11-03','active'],['Asesmen Bahasa Indonesia','Bahasa Indonesia','2026-11-06','draft'],['Penilaian Harian IPA','Ilmu Pengetahuan Alam','2026-11-10','draft']] as $e){$id=DB::table('exams')->insertGetId(['title'=>$e[0],'subject'=>$e[1],'exam_date'=>$e[2],'status'=>$e[3],'form_url'=>'https://docs.google.com/forms/','created_at'=>now(),'updated_at'=>now()]);foreach(['7A','7B','7C'] as $class)DB::table('exam_codes')->insert(['exam_id'=>$id,'class_name'=>$class,'code'=>$class.'-'.strtoupper(bin2hex(random_bytes(4)))]);}}
  if(DB::table('students')->count()===0){foreach([['10001','Andi Pratama','7A'],['10002','Dinda Sari','7A'],['10003','Rafi Kurnia','7B'],['10004','Nadia Putri','8A']] as $s)DB::table('students')->insert(['nis'=>$s[0],'name'=>$s[1],'class_name'=>$s[2],'password'=>Hash::make('DemoSiswa2026!'),'created_at'=>now(),'updated_at'=>now()]);}
 }
}
