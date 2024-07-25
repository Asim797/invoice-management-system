<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'number',
        'due_date',
        'date',
        'reference',
        'toc',
        'sub_total',
        'discount',
        'total',
    ];

    protected $appends = ['create_at'];

    public function getCreateAtAttribute()
    {
        return Carbon::parse($this->created_at)->format('Y-m-d h:i A');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function invoice_items(){
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }
}
