<?php

// app/Models/Transaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Permite a atribuição em massa
    protected $fillable = [
        'type', 'category', 'amount', 'description', 'user_id'
    ];
}
