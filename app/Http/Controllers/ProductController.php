<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $products = [
        [
            'name' => 'cuxin',
            'desc' => 'cantik banget',
            'price' => 'Rp 50.000',
            'image' => 'images/gambar1.jpg',
            'images' => [
                'images/gambar1.jpg',
                'images/gambar2.jpg'
            ],
            'detail' => 'Deskripsi lengkap produk cuxin'
        ],
        [
            'name' => 'ying',
            'desc' => 'cadia riverland',
            'price' => 'Rp 70.000',
            'image' => 'images/gambar2.jpg',
            'images' => [
                'images/gambar2.jpg',
                'images/gambar3.jpg'
            ],
            'detail' => 'Deskripsi lengkap produk ying'
        ],
        [
            'name' => 'yue',
            'desc' => 'cantik banget',
            'price' => 'Rp 60.000',
            'image' => 'images/gambar3.jpg',
            'images' => [
                'images/gambar3.jpg',
                'images/gambar1.jpg'
            ],
            'detail' => 'Deskripsi lengkap produk yue'
        ],
                [
            'name' => 'ying',
            'desc' => 'cadia riverland',
            'price' => 'Rp 70.000',
            'image' => 'images/gambar2.jpg',
            'images' => [
                'images/gambar2.jpg',
                'images/gambar3.jpg'
            ],
            'detail' => 'Deskripsi lengkap produk ying'
        ],
        [
            'name' => 'yue',
            'desc' => 'cantik banget',
            'price' => 'Rp 60.000',
            'image' => 'images/gambar3.jpg',
            'images' => [
                'images/gambar3.jpg',
                'images/gambar1.jpg'
            ],
            'detail' => 'Deskripsi lengkap produk yue'
        ],
    ];

    public function index()
    {
        return view('pages.home', [
            'products' => $this->products
        ]);
    }

    public function show($id)
    {
        $product = $this->products[$id] ?? null;

        if (!$product) {
            abort(404);
        }

        return view('pages.product-detail', [
            'product' => $product,
            'products' => $this->products
        ]);
    }
}
