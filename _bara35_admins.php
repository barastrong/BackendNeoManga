<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$u = App\Models\User::where('role','admin')->orderBy('id')->get(['id','name','email','email_verified','otp_code','otp_expires_at']);
foreach ($u as $x) { echo json_encode($x->toArray()), "\n"; }
