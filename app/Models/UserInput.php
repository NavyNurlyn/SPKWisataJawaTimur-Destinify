<?php
// app/Models/UserInput.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInput extends Model
{
    use HasFactory;

    protected $table = 'tb_user_input';
    protected $fillable = ['session_id', 'latitude', 'longitude', 'jenis_wisata'];
}
