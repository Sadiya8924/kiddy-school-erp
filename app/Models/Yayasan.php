<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yayasan extends Model
{
    use HasFactory;

    protected $table = 'yayasans';

    protected $fillable = [
        'nama_yayasan',
        'kode_yayasan',
        'penanggung_jawab',
        'telepon',
        'alamat',
        'logo_yayasan',
        'landing_content',
        'status',
    ];

    protected $casts = [
        'landing_content' => 'array',
    ];


    public function schools()
    {
        return $this->hasMany(School::class, 'yayasan_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'yayasan_id');
    }

    /**
     * Generate URL-safe deterministic token for the yayasan code.
     */
    public function getEncryptedTokenAttribute(): string
    {
        $code = strtoupper(trim($this->kode_yayasan ?? (string)$this->id));
        $key = config('app.key', 'kiddy-school-erp-secret');
        $hash = substr(hash_hmac('sha256', $code, $key), 0, 16);
        $payload = json_encode(['c' => $code, 'h' => $hash]);
        return rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');
    }

    /**
     * Decrypt URL-safe token back to original yayasan code.
     */
    public static function decryptToken(string $token): ?string
    {
        if (empty($token)) return null;

        try {
            $base64 = strtr($token, '-_', '+/');
            $padding = strlen($base64) % 4;
            if ($padding) {
                $base64 .= str_repeat('=', 4 - $padding);
            }
            $json = base64_decode($base64);
            $data = json_decode($json, true);
            if (is_array($data) && isset($data['c'], $data['h'])) {
                $key = config('app.key', 'kiddy-school-erp-secret');
                $expectedHash = substr(hash_hmac('sha256', $data['c'], $key), 0, 16);
                if (hash_equals($expectedHash, $data['h'])) {
                    return $data['c'];
                }
            }
        } catch (\Throwable $e) {}

        // Legacy Crypt decrypt fallback
        try {
            $base64 = strtr($token, '-_', '+/');
            $padding = strlen($base64) % 4;
            if ($padding) {
                $base64 .= str_repeat('=', 4 - $padding);
            }
            $rawEncrypted = base64_decode($base64);
            return \Illuminate\Support\Facades\Crypt::decryptString($rawEncrypted);
        } catch (\Throwable $e) {
            try {
                return \Illuminate\Support\Facades\Crypt::decryptString($token);
            } catch (\Throwable $e2) {
                return null;
            }
        }
    }

    /**
     * Get full encrypted login URL for this yayasan.
     */
    public function getEncryptedLoginUrlAttribute(): string
    {
        return route('yayasan.login', $this->encrypted_token);
    }

    /**
     * Customize the route key for implicit binding & URL generation.
     */
    public function getRouteKey()
    {
        return $this->encrypted_token;
    }
}
