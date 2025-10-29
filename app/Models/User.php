<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'session_id',
        'last_login_ip',
        'last_login_at',
        'current_device',
        'last_activity'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'session_id'
    ];

    /**
     * Casting attributes untuk otomatis convert ke Carbon
     */
    protected $casts = [
        'last_login_at' => 'datetime',
        'last_activity' => 'datetime', // ✅ TAMBAH INI
    ];

    /**
     * Generate new session ID
     */
    public function generateSessionId()
    {
        $this->session_id = Str::uuid()->toString();
        $this->last_activity = now();
        $this->save();
    }

    /**
     * Clear session (logout from all devices)
     */
    public function clearSession()
    {
        $this->session_id = null;
        $this->current_device = null;
        $this->last_activity = null;
        $this->save();
    }

    /**
     * Check if session is valid
     */
    public function isValidSession($sessionId)
    {
        // ✅ CEK JIKA SESSION EXPIRED (lebih dari 12 jam)
        if ($this->last_activity && $this->getLastActivity()->diffInHours(now()) > 12) {
            $this->clearSession();
            return false;
        }

        return $this->session_id === $sessionId;
    }

    /**
     * Get last_activity as Carbon instance
     */
    public function getLastActivity()
    {
        // Jika sudah di-cast, langsung return
        if ($this->last_activity instanceof \Carbon\Carbon) {
            return $this->last_activity;
        }

        // Jika string, convert ke Carbon
        return $this->last_activity ? Carbon::parse($this->last_activity) : null;
    }

    /**
     * Update login info
     */
    public function updateLoginInfo($ip, $device)
    {
        $this->last_login_ip = $ip;
        $this->last_login_at = now();
        $this->last_activity = now();
        $this->current_device = $this->truncateDeviceInfo($device);
        $this->generateSessionId();
    }

    /**
     * Update last activity timestamp
     */
    public function updateLastActivity()
    {
        $this->last_activity = now();
        $this->save();
    }

    /**
     * Clean expired sessions - bisa dipanggil manual
     */
    public static function cleanExpiredSessions()
    {
        $expiredTime = now()->subHours(12); // Session expired setelah 12 jam
        $expiredUsers = self::whereNotNull('session_id')
            ->where('last_activity', '<', $expiredTime)
            ->get();

        foreach ($expiredUsers as $user) {
            $user->clearSession();
        }

        return $expiredUsers->count();
    }

    /**
     * Truncate device info to prevent too long string
     */
    private function truncateDeviceInfo($device)
    {
        if (strlen($device) > 255) {
            return substr($device, 0, 252) . '...';
        }
        return $device;
    }
}
