<?php
// app/Models/RelKriteria.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelKriteria extends Model
{
    use HasFactory;

    protected $table = 'tb_rel_kriteria';
    protected $fillable = ['kode_kriteria1', 'kode_kriteria2', 'nilai'];
}
