<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$chDaily = App\Models\Chapter::where('created_at', '>=', now()->subDays(29)->startOfDay())
    ->selectRaw('DATE(created_at) d, COUNT(*) c')
    ->groupBy('d')->pluck('c', 'd');
echo "chDaily keys: ".json_encode($chDaily->keys()->all())."\n";
echo "chDaily vals: ".json_encode($chDaily->values()->all())."\n";
$series = collect(range(29,0))->map(fn($i) => ['date'=>now()->subDays($i)->toDateString(),'total'=>(int)($chDaily[now()->subDays($i)->toDateString()] ?? 0)]);
echo "last7: ".json_encode($series->slice(-7)->values()->all())."\n";
echo "sum last7: ".$series->slice(-7)->sum('total')."\n";
echo "chapterToday(ctrl): ".App\Models\Chapter::whereDate('created_at', now())->count()."\n";
