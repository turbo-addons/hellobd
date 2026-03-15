<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainMenuOrder extends Model
{
    protected $table = 'main_menu_orders';
    protected $fillable = ['term_id', 'menu_order'];

    public function term()
    {
        return $this->belongsTo(\App\Models\Term::class, 'term_id');
    }
}
