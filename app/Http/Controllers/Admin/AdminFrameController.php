<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CardFrame;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminFrameController extends Controller
{
    public function index(): View
    {
        $frames = CardFrame::withCount('cards')->orderBy('id')->get();
        return view('admin.frames.index', compact('frames'));
    }

    public function edit(CardFrame $frame): View
    {
        return view('admin.frames.edit', compact('frame'));
    }

    public function update(Request $request, CardFrame $frame): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:250'],
            'is_active' => ['nullable', 'boolean'],
            'border_primary' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'border_inner' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'accent_color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'outer_bg' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'border_width' => ['required', 'integer', 'min:16', 'max:64'],
            'inner_padding' => ['required', 'integer', 'min:4', 'max:32'],
            'label' => ['required', 'string', 'max:40'],
        ]);

        $config = $frame->config ?? [];
        $config['border_primary'] = $validated['border_primary'];
        $config['border_inner'] = $validated['border_inner'];
        $config['accent_color'] = $validated['accent_color'];
        $config['outer_bg'] = $validated['outer_bg'];
        $config['border_width'] = (int) $validated['border_width'];
        $config['inner_padding'] = (int) $validated['inner_padding'];
        $config['label'] = $validated['label'];

        $frame->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'is_active' => $request->has('is_active'),
            'config' => $config,
        ]);

        return redirect()->route('admin.frames.index')->with('success', "Frame '{$frame->name}' updated successfully.");
    }

    public function toggle(CardFrame $frame): RedirectResponse
    {
        $frame->is_active = !$frame->is_active;
        $frame->save();

        $status = $frame->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.frames.index')->with('success', "Frame '{$frame->name}' {$status}.");
    }
}
