<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CurrencySnapshot extends Model { protected $guarded = []; protected $casts = ['observed_at'=>'datetime']; }
