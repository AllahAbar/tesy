<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class table_atg_members extends Model 
{
    use HasFactory;
    protected $table = 'table_atg_members';
    protected $fillable = ['username', 'email', 'password']; //chỉ ~ giá trị đc thêm vào table
    public $timestamps = false; //tắt chế độ created_at & updated_at
}
