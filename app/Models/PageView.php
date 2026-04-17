<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model {
    protected $fillable = ['path','ip','user_id','viewed_date'];

    protected $casts = ['viewed_date' => 'date'];
}
