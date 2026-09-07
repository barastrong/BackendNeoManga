/**
 * Bulk actions — vanilla JS untuk tabel & grid admin NeoManga.
 *
 * Kontrak HTML:
 *  - Container: <table data-bulk-form="URL" data-bulk-actions='[{"value":"delete","label":"Hapus"}]'>
 *    atau <div class="mg-grid" data-bulk-form data-bulk-actions> (grid mode).
 *  - Tabel: baris <tr data-id="{{ $row->id }}"> (data-id wajib).
 *  - Grid:  tiap kartu <div data-bulk-item data-id="{{ $row->id }}">.
 *  - CSRF: <meta name="csrf-token" content="{{ csrf_token() }}"> di layout.
 *
 * Action bar singleton di bawah layar; host = container terakhir yang
 * dipakai user. Aman untuk banyak tabel sekaligus (mis. halaman moderasi).
 */
(function () {
    'use strict';

    var bar = null;
    var host = null;
    var hostActions = [];

    function getCsrf() {
        var m = document.querySelector('meta[name="csrf-token"]');
        return m ? m.getAttribute('content') : null;
    }

    function actionsOf(el) {
        try { return JSON.parse(el.getAttribute('data-bulk-actions') || '[]'); }
        catch (e) { return []; }
    }

    function field(name, value) {
        var i = document.createElement('input');
        i.type = 'hidden'; i.name = name; i.value = value;
        return i;
    }

    function ensureBar() {
        if (bar) return bar;
        bar = document.createElement('div');
        bar.id = 'bulk-bar';
        bar.style.cssText = [
            'position:fixed;left:50%;bottom:22px;transform:translateX(-50%);z-index:9999;',
            'display:none;align-items:center;gap:10px;background:#131a2c;',
            'border:1px solid rgba(255,46,77,.5);box-shadow:0 14px 44px -10px rgba(0,0,0,.8);',
            'border-radius:14px;padding:10px 16px;font-size:13.5px;color:#e2e8f0;',
            'white-space:nowrap;font-family:Inter,system-ui,sans-serif'
        ].join('');
        bar.innerHTML =
            '<span style="font-weight:800;color:#ff8a9c" class="bk-n">0</span>' +
            '<span>dipilih</span>' +
            '<select class="bk-act" style="background:#0d1220;color:#e2e8f0;border:1px solid rgba(255,255,255,.16);border-radius:8px;padding:6px 9px;font-size:13px;outline:none"></select>' +
            '<button type="button" class="bk-go" style="background:#ff2e4d;color:#fff;border:none;border-radius:8px;padding:7px 16px;font-size:13px;font-weight:700;cursor:pointer">Terapkan</button>' +
            '<button type="button" class="bk-x" title="Bersihkan" style="background:transparent;color:#94a3b8;border:none;font-size:17px;cursor:pointer;padding:0 4px">&times;</button>';
        document.body.appendChild(bar);

        var sel = bar.querySelector('.bk-act');
        bar.querySelector('.bk-go').addEventListener('click', function () {
            applyAction(sel.value);
        });
        bar.querySelector('.bk-x').addEventListener('click', function () {
            if (host) clearChecks(host);
        });
        return bar;
    }

    function applyAction(action) {
        if (!host) return;
        var ids = selectedIds(host);
        if (!action || !ids.length) return;
        var label = action;
        hostActions.forEach(function (a) { if (a.value === action) label = a.label; });

        var endpoint = host.getAttribute('data-bulk-form');
        if (!confirm('Terapkan "' + label + '" ke ' + ids.length + ' item?')) return;

        var csrf = getCsrf();
        if (!csrf) { alert('CSRF token tidak ditemukan — muat ulang halaman.'); return; }

        var form = document.createElement('form');
        form.method = 'POST';
        form.action = endpoint;
        form.appendChild(field('_token', csrf));
        form.appendChild(field('action', action));
        ids.forEach(function (id) { form.appendChild(field('ids[]', id)); });
        document.body.appendChild(form);
        form.submit();
    }

    function clearChecks(container) {
        container.querySelectorAll('.bulk-check').forEach(function (c) { c.checked = false; });
        refresh();
    }

    function selectedIds(container) {
        var ids = [];
        container.querySelectorAll('.bulk-check:checked').forEach(function (c) {
            var id = c.getAttribute('data-id');
            if (id) ids.push(id);
        });
        return ids;
    }

    function refresh() {
        if (!host) return;
        var n = selectedIds(host).length;
        var b = ensureBar();
        b.querySelector('.bk-n').textContent = n;
        b.style.display = n ? 'flex' : 'none';

        host.querySelectorAll('.has-bulk').forEach(function (row) {
            var c = row.querySelector('.bulk-check');
            row.classList.toggle('bulk-selected', !!(c && c.checked));
        });
        var all = host.querySelector('.bulk-all');
        if (all) {
            var checks = host.querySelectorAll('.bulk-check');
            all.checked = n > 0 && n === checks.length;
        }
    }

    function setHost(container) {
        if (host === container) return;
        host = container;
        hostActions = actionsOf(container);
        var b = ensureBar();
        var sel = b.querySelector('.bk-act');
        sel.innerHTML = '';
        hostActions.forEach(function (a) {
            var o = document.createElement('option');
            o.value = a.value; o.textContent = a.label;
            sel.appendChild(o);
        });
        refresh();
    }

    function enable(container) {
        var endpoint = container.getAttribute('data-bulk-form');
        var actions = actionsOf(container);
        if (!endpoint || !actions.length) return;

        var isTable = container.tagName === 'TABLE';
        var tbody = isTable ? container.querySelector('tbody') : null;

        if (isTable) {
            if (!tbody) return;
            var headTr = container.querySelector('thead tr');
            if (!headTr) return;

            var th = document.createElement('th');
            th.className = 'bulk-th';
            th.innerHTML = '<input type="checkbox" class="bulk-all" title="Pilih semua di halaman ini">';
            headTr.insertBefore(th, headTr.firstChild);

            Array.prototype.forEach.call(tbody.querySelectorAll('tr'), function (tr) {
                if (!tr.querySelector('td')) return;
                if (tr.querySelector('td[colspan]')) return; // empty-state
                var td = document.createElement('td');
                td.className = 'bulk-col';
                td.innerHTML = '<input type="checkbox" class="bulk-check">';
                tr.insertBefore(td, tr.firstChild);
                tr.classList.add('has-bulk');
                var cb = tr.querySelector('.bulk-check');
                cb.setAttribute('data-id', tr.getAttribute('data-id') || '');
            });

            container.querySelector('.bulk-all').addEventListener('change', function (e) {
                Array.prototype.forEach.call(tbody.querySelectorAll('.bulk-check'), function (c) {
                    c.checked = e.target.checked;
                });
                setHost(container);
                refresh();
            });
        } else {
            Array.prototype.forEach.call(container.querySelectorAll('[data-bulk-item]'), function (item) {
                var cb = document.createElement('input');
                cb.type = 'checkbox';
                cb.className = 'bulk-check';
                cb.setAttribute('data-id', item.getAttribute('data-id') || '');
                cb.style.cssText = 'position:absolute;top:8px;right:32px;z-index:7;width:17px;height:17px;accent-color:#ff2e4d;cursor:pointer;box-shadow:0 0 0 3px rgba(0,0,0,.4);border-radius:4px';
                var wrap = item.querySelector('.mg-coverwrap') || item;
                if (getComputedStyle(wrap).position === 'static') wrap.style.position = 'relative';
                wrap.appendChild(cb);
                item.classList.add('has-bulk');
            });
        }

        // Delegasi change
        container.addEventListener('change', function (e) {
            if (e.target.classList && e.target.classList.contains('bulk-check')) {
                setHost(container);
                refresh();
            }
        });

        ensureBar(); // pre-build (agar dropdown terisi saat pertama centang)
        setHost(container);
    }

    function init() {
        var containers = document.querySelectorAll('[data-bulk-form]');
        Array.prototype.forEach.call(containers, enable);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
