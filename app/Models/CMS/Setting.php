<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use \Illuminate\Database\Eloquent\Concerns\HasUuids;

    protected $guarded = [];

    protected static function booted()
    {
        static::saving(function (Setting $setting) {
            $sensitiveKeys = ['stripe_secret_key', 'stripe_webhook_secret'];
            if (in_array($setting->key, $sensitiveKeys) && $setting->isDirty('value') && !empty($setting->value)) {
                // Check if it's already encrypted (starts with base64 payload pattern or fallback logic)
                try {
                    \Illuminate\Support\Facades\Crypt::decryptString($setting->value);
                } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    // Not encrypted, encrypt it
                    $setting->value = \Illuminate\Support\Facades\Crypt::encryptString($setting->value);
                }
            }
        });
    }

    public static function getDecryptedValue(string $key)
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) return null;

        $sensitiveKeys = ['stripe_secret_key', 'stripe_webhook_secret'];
        if (in_array($key, $sensitiveKeys) && !empty($setting->value)) {
            try {
                return \Illuminate\Support\Facades\Crypt::decryptString($setting->value);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                return $setting->value;
            }
        }
        return $setting->value;
    }
}
