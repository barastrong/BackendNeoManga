<?php
// BARA-34 temp preview: render admin user index as admin (DELETE AFTER USE)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Admin\UserController;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($req = Request::capture());

$admin = User::where('role', 'admin')->orderBy('id')->first();
if (!$admin) { echo "NO ADMIN"; exit; }
Auth::guard('web')->login($admin);

$ctrl = app()->make(UserController::class);
$html = $ctrl->index()->render();
echo $html;
