<?php

// Lokasi scraper Kiryuu (di luar repo, jangan di-commit isinya).
// Path bisa di-override via .env KIRYUU_DIR / KIRYUU_PYTHON.
return [
    'dir' => env('KIRYUU_DIR', 'C:/Users/CORE I7/Documents/kiryu'),
    'python' => env('KIRYUU_PYTHON', 'C:/Users/CORE I7/Documents/kiryu/kiryuu-venv/Scripts/python.exe'),
];
