<?php
$cols = Illuminate\Support\Facades\Schema::getColumnListing('chapters');
echo "COLUMNS: " . implode(', ', $cols) . "\n";
$manga = App\Models\Manga::where('slug', 'martial-peak')->first();
$rows = Illuminate\Support\Facades\DB::table('chapters')->where('manga_id', $manga->id)->get();
echo "COUNT: " . count($rows) . "\n";
foreach (array_slice($rows->toArray(), 0, 12) as $r) {
    echo json_encode($r) . "\n";
}
if (count($rows) > 12) {
    echo "...\n";
    foreach (array_slice($rows->toArray(), -3) as $r) {
        echo json_encode($r) . "\n";
    }
}
