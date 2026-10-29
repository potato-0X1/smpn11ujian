<?php
namespace App\Console\Commands;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
class CreateTeacher extends Command {
 protected $signature='school:create-teacher';
 protected $description='Create an authorized teacher interactively, without putting passwords in command arguments';
 public function handle():int {
  $name=$this->ask('Nama guru');
  $email=$this->ask('Email guru');
  $password=$this->secret('Kata sandi guru (minimal 12 karakter, maksimal 72 byte)');
  $confirmation=$this->secret('Ulangi kata sandi');
  $validator=Validator::make(['name'=>$name,'email'=>$email,'password'=>$password], ['name'=>'required|string|max:255','email'=>'required|email|max:255|unique:users,email','password'=>'required|string|min:12|max:72']);
  if ($validator->fails() || $password !== $confirmation || strlen((string)$password)>72) {
   $this->error('Data tidak valid, email sudah dipakai, kata sandi terlalu pendek/panjang, atau konfirmasi tidak cocok.');
   return self::FAILURE;
  }
  $user=new User();$user->name=$name;$user->email=$email;$user->password=$password;$user->is_teacher=true;$user->save();
  $this->info('Akun guru dibuat. Saat masuk pertama kali, segera aktifkan MFA.');
  return self::SUCCESS;
 }
}
