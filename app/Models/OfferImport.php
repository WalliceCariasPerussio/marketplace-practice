<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferImport extends Model
{
    use HasFactory;

    protected $table = 'offers_imports';

    protected $fillable = [
        'account_id',
        'status',
        'total_offers',
        'total_imported',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
}
