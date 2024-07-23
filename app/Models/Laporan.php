<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'laporan';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_laporan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'desa',
        'kecamatan',
        'foto',
        'keterangan',
        'latitude',
        'longitude',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'desa' => 'integer',     
        'kecamatan' => 'integer',
    ];

    /**
     * Get the user who created the report.
     */
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the report.
     */
    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Relasi ke Village (desa)
    public function village()
    {
        return $this->belongsTo(Village::class, 'desa', 'id');
    }

    // Relasi ke District (kecamatan)
    public function district()
    {
        return $this->belongsTo(District::class, 'kecamatan', 'id');
    }
}
