<?php

use App\Models\Cart;

function getCart()
{
    if (auth()->check()) {
        return Cart::firstOrCreate([
            'user_id' => auth()->id()
        ]);
    }

    return Cart::firstOrCreate([
        'session_id' => session()->getId()
    ]);
}
