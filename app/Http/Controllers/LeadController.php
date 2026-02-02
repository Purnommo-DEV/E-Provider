<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterLeadRequest;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class LeadController extends Controller
{
    public function index()
    {
        return view('page.data-registrasi.index');
    }

    public function data()
    {
        return DataTables::of(Lead::latest())
            ->addColumn('created_at', function ($lead) {
                return $lead->created_at->format('d/m/Y H:i');
            })
            ->make(true);
    }

    public function destroy($id)
    {
        Lead::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function store(RegisterLeadRequest $request)
    {
        // try {
            $token = $request->input('g-recaptcha-response');

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token captcha hilang. Silakan refresh halaman.'
                ], 422);
            }

            // Verifikasi reCAPTCHA v3 manual
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => env('RECAPTCHA_SECRET_KEY'),
                'response' => $token,
                'remoteip' => $request->ip(),
            ]);

            $result = $response->json();

            if (!$result['success'] || ($result['score'] ?? 0) < (float) env('RECAPTCHA_MIN_SCORE', 0.4)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Verifikasi captcha gagal (skor terlalu rendah). Anda terdeteksi sebagai bot.'
                ], 422);
            }

            // Normalisasi nomor HP ke format +62...
            $phone = $request->phone;
            $phone_normalized = preg_replace('/^0/', '+62', $phone);
            $phone_normalized = preg_replace('/[^0-9+]/', '', $phone_normalized);

            // Simpan ke database
            $lead = Lead::create([
                'name'       => $request->name,
                'email'      => $request->email ?? null,
                'phone'      => $phone_normalized,
                'address'    => $request->address ?? null,
                'kelurahan'  => $request->kelurahan ?? null,
                'rt'         => $request->rt ?? null,
                'blok'       => $request->blok ?? null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Buat pesan WA
            $message = "Pendaftaran baru!\n" .
                       "Nama: {$lead->name}\n" .
                       "HP: {$phone_normalized}\n" .
                       ($lead->address ? "Alamat: {$lead->address}\n" : "") .
                       ($lead->kelurahan ? "Kelurahan: {$lead->kelurahan}\n" : "") .
                       ($lead->rt ? "RT: {$lead->rt}\n" : "") .
                       ($lead->blok ? "Blok/RW: {$lead->blok}\n" : "") .
                       "Waktu: " . $lead->created_at->format('d/m/Y H:i');

            $adminPhone = '6281234567890'; // GANTI DENGAN NOMOR ADMIN KAMU (format +62 tanpa spasi)
            $waLink = "https://wa.me/{$adminPhone}?text=" . urlencode($message);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil! Anda akan diarahkan ke WhatsApp...',
                'wa_link' => $waLink
            ]);

        // } catch (\Exception $e) {
        //     Log::error('Error saving lead: ' . $e->getMessage());
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Terjadi kesalahan server. Silakan coba lagi nanti.'
        //     ], 500);
        // }
    }
}