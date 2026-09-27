<?php
try { App\Models\User::where('username','devtest')->delete(); } catch(\Throwable $e) {}
$u = App\Models\User::create(['name'=>'Dev Test','username'=>'devtest','email'=>'devtest@example.com','password'=>'secret12345','is_active'=>true,'is_admin'=>false]);
echo 'created='.$u->id.' profile='.$u->getOrCreateProfile()->id;
