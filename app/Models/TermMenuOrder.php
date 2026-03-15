<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TermMenuOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'term_id',
        'parent_id',
        'menu_order',
    ];

    public function term()
    {
        return $this->belongsTo(Term::class, 'term_id');
    }

    public function parent()
    {
        return $this->belongsTo(Term::class, 'parent_id');
    }
}
