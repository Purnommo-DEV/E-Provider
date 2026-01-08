<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterLeadRequest;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
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
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Buat pesan WA
            $message = "Pendaftaran baru dari website!\n" .
                       "Nama: {$lead->name}\n" .
                       "WA: {$phone_normalized}\n" .
                       ($lead->email ? "Email: {$lead->email}\n" : "") .
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