<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
