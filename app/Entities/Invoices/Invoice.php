<?php

namespace App\Entities\Invoices;

use App\Entities\Projects\Project;
use App\Entities\Users\User;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = ['items' => 'array'];

    public function getRouteKeyName()
    {
        return 'number';
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function generateNewNumber()
    {
        $prefix = date('ym');

        $lastInvoice = $this->orderBy('number', 'desc')->first();

        if ( ! is_null($lastInvoice)) {
            $lastInvoiceNo = $lastInvoice->number;
            if (substr($lastInvoiceNo, 0, 4) == $prefix) {
                return ++$lastInvoiceNo;
            }
        }
        return $prefix.'001';
    }

    public function getItemsCountAttribute($value)
    {
        $pcsCount = 0;

        return count($this->items);
    }
}
