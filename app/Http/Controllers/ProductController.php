<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public $products = [
        [
            'name'     => 'Cuxin',
            'desc'     => ['id' => 'Cantik banget', 'en' => 'Beautifully crafted'],
            'price'    => 'Rp 50.000',
            'price_num'=> 50000,
            'image'    => 'images/gambar1.jpg',
            'images'   => ['images/gambar1.jpg', 'images/gambar2.jpg'],
            'detail'   => [
                'id' => 'Deskripsi lengkap produk Cuxin dalam bahasa Indonesia.',
                'en' => 'Full description of Cuxin product in English.',
            ],
        ],
        [
            'name'     => 'Ying',
            'desc'     => ['id' => 'Cadia riverland', 'en' => 'Cadia riverland'],
            'price'    => 'Rp 70.000',
            'price_num'=> 70000,
            'image'    => 'images/gambar2.jpg',
            'images'   => ['images/gambar2.jpg', 'images/gambar3.jpg'],
            'detail'   => [
                'id' => 'Deskripsi lengkap produk Ying dalam bahasa Indonesia.',
                'en' => 'Full description of Ying product in English.',
            ],
        ],
        [
            'name'     => 'Yue',
            'desc'     => ['id' => 'Cantik banget', 'en' => 'Beautifully crafted'],
            'price'    => 'Rp 60.000',
            'price_num'=> 60000,
            'image'    => 'images/gambar3.jpg',
            'images'   => ['images/gambar3.jpg', 'images/gambar1.jpg'],
            'detail'   => [
                'id' => 'Deskripsi lengkap produk Yue dalam bahasa Indonesia.',
                'en' => 'Full description of Yue product in English.',
            ],
        ],
    ];

    public function index(Request $request)
    {
        $products = $this->products;

        if ($request->has('q') && $request->q != '') {
            $q = strtolower($request->q);
            $products = array_filter($products, fn($item) =>
                str_contains(strtolower($item['name']), $q)
            );
        }

        return view('pages.home', ['products' => array_values($products)]);
    }

    public function shop(Request $request)
    {
        $products = $this->products;

        // Search
        if ($request->filled('q')) {
            $q = strtolower($request->q);
            $products = array_filter($products, fn($item) =>
                str_contains(strtolower($item['name']), $q)
            );
        }

        // Filter harga
        if ($request->filled('min_price')) {
            $products = array_filter($products, fn($item) =>
                $item['price_num'] >= (int) $request->min_price
            );
        }
        if ($request->filled('max_price')) {
            $products = array_filter($products, fn($item) =>
                $item['price_num'] <= (int) $request->max_price
            );
        }

        // Sort
        $products = array_values($products);
        $sort = $request->get('sort', 'default');

        if ($sort === 'name_az') {
            usort($products, fn($a, $b) => strcmp($a['name'], $b['name']));
        } elseif ($sort === 'name_za') {
            usort($products, fn($a, $b) => strcmp($b['name'], $a['name']));
        } elseif ($sort === 'price_lo') {
            usort($products, fn($a, $b) => $a['price_num'] - $b['price_num']);
        } elseif ($sort === 'price_hi') {
            usort($products, fn($a, $b) => $b['price_num'] - $a['price_num']);
        }

        $allPrices    = array_column($this->products, 'price_num');
        $minPossible  = min($allPrices);
        $maxPossible  = max($allPrices);

        return view('pages.Product', [
            'products'    => $products,
            'total'       => count($products),
            'sort'        => $sort,
            'minPossible' => $minPossible,
            'maxPossible' => $maxPossible,
            'minPrice'    => $request->get('min_price', $minPossible),
            'maxPrice'    => $request->get('max_price', $maxPossible),
            'q'           => $request->get('q', ''),
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
