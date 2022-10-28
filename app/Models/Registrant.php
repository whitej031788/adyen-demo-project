<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registrant extends Model
{
    public function shopperReference()
    {
        return sprintf('%03d', $this->id);
    }

    public function lineItems() {
        return $this->hasMany(LineItem::class);
    }

    public function lineItemsUnpaid() {
        return $this->hasMany(LineItem::class)->where('line_items.is_paid', '=', 0);;
    }

    public function lineItemsPaid() {
        return $this->hasMany(LineItem::class)->where('line_items.is_paid', '=', 1);;
    }
}
