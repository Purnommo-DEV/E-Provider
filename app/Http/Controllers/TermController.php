<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TermController extends Controller
{
    /**
     * Tampilkan halaman edit (single record)
     */
    public function edit()
    {
        $term = Term::first() ?? new Term([
            'content'  => '',
            'is_active' => true,   // default tampilkan
        ]);

        return view('page.terms.index', compact('term'));
    }

    /**
     * Update via AJAX
     */
    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'content'   => 'required|string|max:30000',
                'is_active' => 'boolean',
            ]);

            $term = Term::first();

            if (!$term) {
                $term = Term::create([
                    'content'   => $validated['content'],
                    'is_active' => $validated['is_active'] ?? false,
                ]);
            } else {
                $term->update([
                    'content'   => $validated['content'],
                    'is_active' => $validated['is_active'] ?? $term->is_active,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Syarat dan Ketentuan berhasil diperbarui!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Validasi gagal: ' . implode(', ', array_flatten($e->errors()))
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Term update failed', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'input'   => $request->except(['_token', 'content']), // content terlalu besar untuk log
            ]);

            return response()->json([
                'success' => false,
                'error'   => 'Terjadi kesalahan server. Silakan coba lagi. (lihat log untuk detail)'
            ], 500);
        }
    }
}