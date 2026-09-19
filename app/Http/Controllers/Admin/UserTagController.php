<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserTag;
use App\Services\TagService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserTagController extends Controller
{
    /** Daftar semua gelar (CMS). */
    public function index()
    {
        $tags = UserTag::orderBy('sort_order')->orderBy('id')->get();
        $types = TagService::types();

        return view('admin.usertag.index', compact('tags', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateTag($request);

        UserTag::create($validated + ['sort_order' => (int) ($request->sort_order ?? 0)]);

        return back()->with('success', "Gelar \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function update(Request $request, UserTag $tag)
    {
        $validated = $this->validateTag($request, $tag->id);

        $tag->update($validated + ['sort_order' => (int) ($request->sort_order ?? 0)]);

        return back()->with('success', "Gelar \"{$validated['name']}\" berhasil diubah.");
    }

    public function destroy(UserTag $tag)
    {
        $name = $tag->name;
        $tag->delete();

        return back()->with('success', "Gelar \"{$name}\" berhasil dihapus.");
    }

    /** Toggle aktif/nonaktif (aktif = muncul & dievaluasi). */
    public function toggle(UserTag $tag)
    {
        $tag->update(['is_active' => !$tag->is_active]);

        return back()->with('success', "Gelar \"{$tag->name}\" " . ($tag->is_active ? 'diaktifkan' : 'dinonaktifkan') . '.');
    }

    private function validateTag(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'              => ['required', 'string', 'max:60', Rule::unique('user_tags', 'name')->ignore($ignoreId)],
            'icon'              => 'required|string|max:60',
            'color'             => 'required|string|max:20',
            'requirement_type'  => ['required', Rule::in(array_keys(TagService::types()))],
            'requirement_value' => 'required|integer|min:0',
            'description'       => 'nullable|string|max:255',
        ]);
    }
}