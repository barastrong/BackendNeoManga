<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;
echo "manga_views total: ", DB::table('manga_views')->count(), "\n";
echo "min date: ", DB::table('manga_views')->min('view_date'), "  max date: ", DB::table('manga_views')->max('view_date'), "\n";
echo "distinct dates: ", DB::table('manga_views')->distinct()->count('view_date'), "\n";
$rows = DB::table('manga_views')
    ->selectRaw('view_date, COUNT(*) c, COUNT(DISTINCT user_id) u')
    ->where('view_date', '>=', now()->subDays(35)->toDateString())
    ->groupBy('view_date')->orderBy('view_date')->get();
foreach ($rows as $r) echo $r->view_date, " total=", $r->c, " readers=", $r->u, "\n";
