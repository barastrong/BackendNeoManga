<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan | NeoManga</title>
    <meta name="robots" content="noindex">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="/css/app.css">
    <style>
        body { background: #0b0f19; color: #e2e8f0; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Inter', system-ui, sans-serif; margin: 0; overflow: hidden; }
        .glow { position: fixed; border-radius: 50%; filter: blur(90px); opacity: .35; pointer-events: none; }
        .g1 { width: 420px; height: 420px; background: #ff2e4d; top: -120px; left: -80px; }
        .g2 { width: 380px; height: 380px; background: #7c3aed; bottom: -100px; right: -60px; }
        .card { position: relative; z-index: 1; text-align: center; padding: 48px 40px; max-width: 520px; }
        .code { font-size: 112px; font-weight: 800; line-height: 1; background: linear-gradient(135deg, #ff2e4d 20%, #ff7a8f 60%, #ffb3c0); -webkit-background-clip: text; -webkit-text-fill-color: transparent; letter-spacing: -4px; margin-bottom: 8px; }
        .title { font-size: 20px; font-weight: 700; margin: 0 0 10px; }
        .desc { color: #94a3b8; font-size: 14px; line-height: 1.7; margin: 0 0 28px; }
        .btn { display: inline-block; background: #ff2e4d; color: #fff; font-weight: 600; font-size: 14px; padding: 12px 28px; border-radius: 999px; text-decoration: none; transition: background .2s; }
        .btn:hover { background: #c81e3a; }
        .home-link { display: inline-block; margin-top: 14px; color: #64748b; font-size: 13px; text-decoration: none; }
        .home-link:hover { color: #94a3b8; }
    </style>
</head>
<body>
    <div class="glow g1"></div>
    <div class="glow g2"></div>

    <div class="card">
        <div class="code">404</div>
        <h1 class="title">Halaman Tidak Ditemukan</h1>
        <p class="desc">Halaman yang kamu cari mungkin sudah dipindah, dihapus, atau alamatnya salah.</p>
        <a class="btn" href="{{ url('/') }}">← Kembali ke Beranda</a>
        <br>
        <a class="home-link" href="{{ url('/mangas') }}">Jelajahi Semua Manga</a>
    </div>
</body>
</html>