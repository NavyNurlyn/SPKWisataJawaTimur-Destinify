<?php
// app/Models/HasilPerhitungan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilPerhitungan extends Model
{
    use HasFactory;

    protected $table = 'tb_hasil_perhitungan';
    protected $fillable = [
        'session_id',
        'alternatif_id',
        'jarak_km',
        'nilai_preferensi',
        'ranking'
    ];

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_id');
    }
}
