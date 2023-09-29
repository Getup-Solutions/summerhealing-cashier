<?php

namespace App\Http\Controllers;
use Darryldecode\Cart\CartCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CartController extends Controller
{
    //

    // public function __construct()
    // {
    //     $cartItems = \Cart::getContent();
    //     dd($cartItems)
    //     // if(Auth::check()) {
    //     //     \Cart::session(Auth::user()->id);
    //     //     foreach($cartItems as $row) {
    //     //         dd($row);
    //     //         \Cart::add($row);
    //     //     }
    //     // }
    // }

    public function index()
    {
        dd('dd');
        return Inertia::render('Cart/Cart');
    }

    public function add()
    {
        $product = request()->product;
        \Cart::add(
            array(
                'id' => $product['id'],
                'name' => $product['title'],
                'price' => $product['price'],
                'quantity' => request()->quantity,
                'attributes' => [
                    'image' => $product['thumbnail_url'],
                    'price_with_currency'=>$product['price'].' AUD',
                    'type' => request()->type,
                ],
                'associatedModel' => $product
            )
        );
        return redirect()->back()->with('success', 'Item is Added to Cart Successfully !');
    }

    public function update(Request $request)
    {

        \Cart::update(
            $request->id,
            [
                'quantity' => [
                    'relative' => false,
                    'value' => $request->quantity
                ],
            ]
        );

        return redirect()->back();
    }

    public function remove(Request $request)
    {
        \Cart::remove($request->id);
        
        return redirect()->back();
    }
}
