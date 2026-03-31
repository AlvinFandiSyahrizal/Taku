<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $products = [
        [
            'name'   => 'Cuxin',
            'desc'   => [
                'id' => 'Cantik banget',
                'en' => 'Beautifully crafted',
            ],
            'price'  => 'Rp 50.000',
            'image'  => 'images/gambar1.jpg',
            'images' => [
                'images/gambar1.jpg',
                'images/gambar2.jpg',
            ],
            'detail' => [
                'id' => 'Deskripsi lengkap produk Cuxin dalam bahasa Indonesia. Produk ini dibuat dengan bahan berkualitas tinggi.',
                'en' => 'Full description of Cuxin product in English. This product is made with high quality materials.',
            ],
        ],
        [
            'name'   => 'Ying',
            'desc'   => [
                'id' => 'Cadia riverland',
                'en' => 'Cadia riverland',
            ],
            'price'  => 'Rp 70.000',
            'image'  => 'images/gambar2.jpg',
            'images' => [
                'images/gambar2.jpg',
                'images/gambar3.jpg',
            ],
            'detail' => [
                'id' => 'Deskripsi lengkap produk Ying dalam bahasa Indonesia. Dibuat dengan desain elegan dan modern.',
                'en' => 'Full description of Ying product in English. Made with elegant and modern design.',
            ],
        ],
        [
            'name'   => 'Yue',
            'desc'   => [
                'id' => 'Cantik banget',
                'en' => 'Beautifully crafted',
            ],
            'price'  => 'Rp 60.000',
            'image'  => 'images/gambar3.jpg',
            'images' => [
                'images/gambar3.jpg',
                'images/gambar1.jpg',
            ],
            'detail' => [
                'id' => 'Deskripsi lengkap produk Yue dalam bahasa Indonesia. Kualitas terjamin dengan bahan pilihan.',
                'en' => 'Full description of Yue product in English. Guaranteed quality with selected materials.',
            ],
        ],
    ];

    public function index(Request $request)
    {
        $products = $this->products;

        if ($request->has('q') && $request->q != '') {
            $q = strtolower($request->q);
            $products = array_filter($products, function ($item) use ($q) {
                return str_contains(strtolower($item['name']), $q);
            });
        }

        return view('pages.home', [
            'products' => $products,
        ]);
    }

    public function show($id)
    {
        $product = $this->products[$id] ?? null;

        if (!$product) {
            abort(404);
        }

        return view('pages.product-detail', [
            'product'  => $product,
            'products' => $this->products,
            'id'       => $id,
        ]);
    }
}
