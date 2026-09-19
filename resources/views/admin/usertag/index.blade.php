@extends('layouts.admin')

@section('title', 'Gelar User — Admin NeoManga')
@section('page-title', 'Gelar User')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/usertag/index.css') }}">

<div>
    {{-- HEADER --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="ut-eyebrow"><i class="fa-solid fa-id-badge mr-1.5"></i>Gamifikasi</span>
                <span class="text-slate-600 text-xs">•</span>
                <span class="text-[11px] font-semibold tracking-wide text-brand/90 uppercase">Achivement Tags</span>
            </div>
            <h1 class="font-display text-[26px] lg:text-3xl font-bold text-white tracking-tight mt-1.5">Gelar User</h1>
            <p class="text-sm text-slate-400 mt-1">Tags achievement yang terbuka otomatis sesuai syarat user.</p>
        </div>
        <button class="ut-btn ut-btn-primary" onclick="utOpen('add')">
            <i class="fa-solid fa-plus text-xs"></i>Tambah Gelar
        </button>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="mt-5 flex items-center gap-3 px-5 py-4 rounded-2xl border text-sm" style="background:rgba(52,211,153,.08);border-color:rgba(52,211,153,.25);color:#6ee7b7">
            <i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- STATS --}}
    <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-3.5">
        <div class="ut-stat">
            <span class="ic" style="background:rgba(255,46,77,.13);color:#ff2e4d"><i class="fa-solid fa-id-badge"></i></span>
            <div><p class="lbl">Total Gelar</p><p class="val">{{ $tags->count() }}</p></div>
        </div>
        <div class="ut-stat">
            <span class="ic" style="background:rgba(52,211,153,.13);color:#34d399"><i class="fa-solid fa-circle-check"></i></span>
            <div><p class="lbl">Aktif</p><p class="val">{{ $tags->where('is_active', true)->count() }}</p></div>
        </div>
        <div class="ut-stat">
            <span class="ic" style="background:rgba(100,116,139,.13);color:#94a3b8"><i class="fa-solid fa-circle-minus"></i></span>
            <div><p class="lbl">Nonaktif</p><p class="val">{{ $tags->where('is_active', false)->count() }}</p></div>
        </div>
        <div class="ut-stat">
            <span class="ic" style="background:rgba(167,139,250,.13);color:#a78bfa"><i class="fa-solid fa-star"></i></span>
            <div><p class="lbl">Jenis Syarat</p><p class="val">{{ count($types) }}</p></div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="ut-card mt-6">
        <table class="ut-table">
            <thead>
                <tr>
                    <th>Gelar</th>
                    <th>Syarat</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tags as $tag)
                    <tr class="{{ $tag->is_active ? '' : 'ut-row-off' }}">
                        <td>
                            <div class="ut-tag">
                                <span class="ut-tag-ic" style="color:{{ $tag->color }};background:{{ $tag->color }}22"><i class="fa-solid {{ $tag->icon }}"></i></span>
                                <div>
                                    <b>{{ $tag->name }}</b>
                                    <p>{{ $tag->description }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="ut-req">{{ $types[$tag->requirement_type] ?? $tag->requirement_type }}</span>
                            <span class="ut-val">{{ $tag->requirement_value }}</span>
                        </td>
                        <td>
                            @if ($tag->is_active)
                                <span class="ut-badge ut-badge-on"><i class="fa-solid fa-circle"></i>Aktif</span>
                            @else
                                <span class="ut-badge ut-badge-off"><i class="fa-solid fa-circle"></i>Off</span>
                            @endif
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="ut-actions">
                                <button class="ut-act ut-act-edit" title="Edit" onclick='utOpen("edit", {{ json_encode($tag, JSON_HEX_APOS | JSON_HEX_QUOT) }})'>
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.usertag.toggle', $tag) }}" class="inline">
                                    @csrf
                                    <button class="ut-act {{ $tag->is_active ? 'ut-act-off' : 'ut-act-on' }}" title="{{ $tag->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="fa-solid {{ $tag->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.usertag.destroy', $tag) }}" class="inline" onsubmit="return confirm('Hapus gelar &quot;{{ $tag->name }}&quot;?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="ut-act ut-act-del" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-8 text-slate-500">Belum ada gelar. Klik "Tambah Gelar".</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL ADD/EDIT --}}
<div id="utModal" class="ut-modal" style="display:none">
    <div class="ut-modal-box">
        <div class="ut-modal-head">
            <h3 id="utModalTitle">Tambah Gelar</h3>
            <button type="button" class="ut-modal-x" onclick="utClose()">&times;</button>
        </div>
        <form method="POST" id="utForm" action="{{ route('admin.usertag.store') }}">
            @csrf
            <input type="hidden" id="utMethod" name="_method" value="POST">
            <input type="hidden" id="utId" name="id" value="">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="ut-label">Nama Gelar</label>
                    <input type="text" name="name" id="utName" class="ut-input" maxlength="60" required placeholder="cth: Kutu Buku">
                </div>
                <div>
                    <label class="ut-label">Ikon FontAwesome</label>
                    <input type="text" name="icon" id="utIcon" class="ut-input" maxlength="60" required placeholder="cth: fa-book">
                </div>
                <div>
                    <label class="ut-label">Warna (hex)</label>
                    <div class="ut-color-row">
                        <input type="color" name="color" id="utColor" class="ut-color" value="#38bdf8">
                        <input type="text" name="color_text" id="utColorText" class="ut-input" maxlength="20" placeholder="#38bdf8">
                    </div>
                </div>
                <div>
                    <label class="ut-label">Urutan Tampil</label>
                    <input type="number" name="sort_order" id="utSort" class="ut-input" min="0" value="0">
                </div>
            </div>

            <div class="mt-4">
                <label class="ut-label">Jenis Syarat</label>
                <select name="requirement_type" id="utType" class="ut-input">
                    @foreach ($types as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4">
                <label class="ut-label">Nilai Minimal Syarat</label>
                <input type="number" name="requirement_value" id="utValue" class="ut-input" min="0" value="0">
                <p class="ut-hint">Untuk "Admin" dan "Semua user" nilai diabaikan.</p>
            </div>

            <div class="mt-4">
                <label class="ut-label">Deskripsi</label>
                <input type="text" name="description" id="utDesc" class="ut-input" maxlength="255" placeholder="cth: Baca 100 chapter">
            </div>

            <div class="ut-modal-foot">
                <button type="button" class="ut-btn ut-btn-ghost" onclick="utClose()">Batal</button>
                <button type="submit" class="ut-btn ut-btn-primary">Simpan Gelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function utOpen(mode, tag) {
        var m = document.getElementById('utModal');
        document.getElementById('utModalTitle').textContent = mode === 'edit' ? 'Edit Gelar' : 'Tambah Gelar';
        var form = document.getElementById('utForm');
        if (mode === 'edit') {
            form.action = "{{ route('admin.usertag.update', 0) }}".replace('/0', '/' + tag.id);
            document.getElementById('utMethod').value = 'PUT';
            document.getElementById('utId').value = tag.id;
            document.getElementById('utName').value = tag.name;
            document.getElementById('utIcon').value = tag.icon;
            document.getElementById('utColor').value = tag.color;
            document.getElementById('utColorText').value = tag.color;
            document.getElementById('utSort').value = tag.sort_order;
            document.getElementById('utType').value = tag.requirement_type;
            document.getElementById('utValue').value = tag.requirement_value;
            document.getElementById('utDesc').value = tag.description || '';
        } else {
            form.action = "{{ route('admin.usertag.store') }}";
            document.getElementById('utMethod').value = 'POST';
            document.getElementById('utId').value = '';
            document.getElementById('utName').value = '';
            document.getElementById('utIcon').value = 'fa-tag';
            document.getElementById('utColor').value = '#38bdf8';
            document.getElementById('utColorText').value = '#38bdf8';
            document.getElementById('utSort').value = '0';
            document.getElementById('utType').value = 'reads';
            document.getElementById('utValue').value = '0';
            document.getElementById('utDesc').value = '';
        }
        m.style.display = 'flex';
    }
    function utClose() {
        document.getElementById('utModal').style.display = 'none';
    }
    document.querySelector('#utColor').addEventListener('input', function () {
        document.getElementById('utColorText').value = this.value;
    });
    document.getElementById('utModal').addEventListener('click', function (e) {
        if (e.target === this) utClose();
    });
</script>
@endsection