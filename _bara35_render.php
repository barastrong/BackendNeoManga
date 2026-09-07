<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
try {
    $c = app()->make(App\Http\Controllers\Admin\AnalyticsController::class);
    $html = $c->index()->render();
    file_put_contents(__DIR__.'/_bara35_render.html', $html);
    echo "RENDER OK len=", strlen($html);
} catch (Throwable $e) {
    echo get_class($e), ": ", $e->getMessage(), "\n";
    echo $e->getFile(), ":", $e->getLine(), "\n";
    echo $e->getTraceAsString();
}
