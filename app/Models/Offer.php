<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_import_id',
        'external_id',
        'title',
        'description',
        'status',
        'stock',
        'price',
        'images',
        'status_queue'
    ];

    protected $casts = [
        'images' => 'array'
    ];

    public function offerImport()
    {
        return $this->belongsTo(OfferImport::class);
    }
}
