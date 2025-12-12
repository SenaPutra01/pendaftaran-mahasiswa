<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Pembayaran extends Model
{
    use HasFactory;


    const STATUS_PENDING = 'pending';
    const STATUS_SETTLEMENT = 'settlement';
    const STATUS_CAPTURE = 'capture';
    const STATUS_DENY = 'deny';
    const STATUS_CANCEL = 'cancel';
    const STATUS_EXPIRE = 'expire';
    const STATUS_REFUND = 'refund';


    const STATUS_CHALLENGE = 'challenge';
    const STATUS_PENDING_VERIFICATION = 'pending_verification';

    protected $table = 'pembayaran';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'calon_mahasiswa_id',
        'order_id',
        'jumlah',
        'status',
        'payment_type',
        'snap_token',
        'waktu_kadaluarsa',
        'metadata'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'waktu_kadaluarsa' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get all valid statuses based on database constraint
     */
    public static function getValidStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_SETTLEMENT,
            self::STATUS_CAPTURE,
            self::STATUS_DENY,
            self::STATUS_CANCEL,
            self::STATUS_EXPIRE,
            self::STATUS_REFUND,
        ];
    }

    /**
     * Get successful statuses (payment completed)
     */
    public static function getSuccessfulStatuses(): array
    {
        return [
            self::STATUS_SETTLEMENT,
            self::STATUS_CAPTURE,
        ];
    }

    /**
     * Get failed statuses
     */
    public static function getFailedStatuses(): array
    {
        return [
            self::STATUS_DENY,
            self::STATUS_CANCEL,
            self::STATUS_EXPIRE,
        ];
    }

    /**
     * Get pending statuses
     */
    public static function getPendingStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_CHALLENGE,
        ];
    }

    /**
     * Validate if a status is valid according to database constraint
     */
    public static function isValidStatus(string $status): bool
    {
        return in_array($status, self::getValidStatuses());
    }

    /**
     * Scope query untuk pembayaran yang berhasil
     */
    public function scopeSuccessful($query)
    {
        return $query->whereIn('status', self::getSuccessfulStatuses());
    }

    /**
     * Scope query untuk pembayaran pending
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope query untuk pembayaran expired
     */
    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRE)
            ->orWhere(function ($q) {
                $q->where('status', self::STATUS_PENDING)
                    ->where('waktu_kadaluarsa', '<', Carbon::now());
            });
    }

    /**
     * Scope query untuk pembayaran yang gagal
     */
    public function scopeFailed($query)
    {
        return $query->whereIn('status', self::getFailedStatuses());
    }

    /**
     * Relasi ke calon mahasiswa
     */
    public function calonMahasiswa()
    {
        return $this->belongsTo(CalonMahasiswa::class, 'calon_mahasiswa_id');
    }

    /**
     * Cek apakah pembayaran sudah kadaluarsa
     */
    public function getIsExpiredAttribute(): bool
    {
        if ($this->waktu_kadaluarsa && $this->status === self::STATUS_PENDING) {
            return Carbon::now()->greaterThan($this->waktu_kadaluarsa);
        }


        return $this->status === self::STATUS_EXPIRE;
    }

    /**
     * Cek apakah pembayaran berhasil (settlement atau capture)
     */
    public function getIsPaidAttribute(): bool
    {
        return in_array($this->status, self::getSuccessfulStatuses());
    }

    /**
     * Cek apakah pembayaran pending
     */
    public function getIsPendingAttribute(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Cek apakah pembayaran gagal
     */
    public function getIsFailedAttribute(): bool
    {
        return in_array($this->status, self::getFailedStatuses());
    }

    /**
     * Cek apakah pembayaran direfund
     */
    public function getIsRefundedAttribute(): bool
    {
        return $this->status === self::STATUS_REFUND;
    }

    /**
     * Cek apakah pembayaran menunggu verifikasi manual
     * (jika menggunakan sistem manual transfer)
     */
    public function getIsPendingVerificationAttribute(): bool
    {
        return $this->status === self::STATUS_PENDING_VERIFICATION;
    }

    /**
     * Cek apakah pembayaran masih aktif (belum expired dan belum selesai)
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->is_pending && !$this->is_expired;
    }

    /**
     * Get status label untuk tampilan
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_SETTLEMENT => 'Berhasil',
            self::STATUS_CAPTURE => 'Berhasil',
            self::STATUS_DENY => 'Ditolak',
            self::STATUS_CANCEL => 'Dibatalkan',
            self::STATUS_EXPIRE => 'Kadaluarsa',
            self::STATUS_REFUND => 'Dikembalikan',
            self::STATUS_CHALLENGE => 'Dalam Peninjauan',
            self::STATUS_PENDING_VERIFICATION => 'Menunggu Verifikasi',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get status badge color untuk tampilan
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            self::STATUS_PENDING => 'warning',
            self::STATUS_SETTLEMENT => 'success',
            self::STATUS_CAPTURE => 'success',
            self::STATUS_DENY => 'danger',
            self::STATUS_CANCEL => 'danger',
            self::STATUS_EXPIRE => 'secondary',
            self::STATUS_REFUND => 'info',
            self::STATUS_CHALLENGE => 'warning',
            self::STATUS_PENDING_VERIFICATION => 'info',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get payment type label
     */
    public function getPaymentTypeLabelAttribute(): string
    {
        $labels = [
            'bank_transfer' => 'Transfer Bank',
            'credit_card' => 'Kartu Kredit',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'qris' => 'QRIS',
            'cstore' => 'Convenience Store',
            'manual_transfer' => 'Transfer Manual',
            null => 'Tidak Diketahui',
        ];

        return $labels[$this->payment_type] ?? $this->payment_type;
    }

    /**
     * Format jumlah untuk tampilan
     */
    public function getJumlahFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    /**
     * Cek apakah bisa dibuat pembayaran baru
     * (tidak ada pembayaran aktif yang sedang berjalan)
     */
    public function canCreateNewPayment(): bool
    {

        $activePayment = self::where('calon_mahasiswa_id', $this->calon_mahasiswa_id)
            ->where('status', self::STATUS_PENDING)
            ->where('waktu_kadaluarsa', '>', Carbon::now())
            ->exists();

        return !$activePayment;
    }

    /**
     * Update status dengan validasi
     */
    public function updateStatus(string $newStatus, array $additionalData = []): bool
    {

        if (!self::isValidStatus($newStatus)) {
            throw new \InvalidArgumentException("Status '$newStatus' tidak valid. Status yang valid: " . implode(', ', self::getValidStatuses()));
        }

        $updateData = ['status' => $newStatus];


        if (!empty($additionalData)) {
            $updateData = array_merge($updateData, $additionalData);
        }

        return $this->update($updateData);
    }

    /**
     * Mark payment as expired jika melewati waktu kadaluarsa
     */
    public function markAsExpired(): bool
    {
        if ($this->status === self::STATUS_PENDING && $this->is_expired) {
            return $this->updateStatus(self::STATUS_EXPIRE, [
                'metadata' => array_merge(
                    $this->metadata ?? [],
                    ['expired_at' => Carbon::now()->toDateTimeString()]
                )
            ]);
        }

        return false;
    }

    /**
     * Boot method untuk event listeners
     */
    protected static function boot()
    {
        parent::boot();


        static::saving(function ($model) {
            if (!self::isValidStatus($model->status)) {
                throw new \InvalidArgumentException(
                    "Status '{$model->status}' tidak valid. " .
                        "Status yang diijinkan: " . implode(', ', self::getValidStatuses())
                );
            }
        });


        static::retrieved(function ($model) {
            if ($model->status === self::STATUS_PENDING && $model->is_expired) {
            }
        });
    }
}
