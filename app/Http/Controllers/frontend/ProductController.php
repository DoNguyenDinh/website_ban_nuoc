<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Comments;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\Products;

class ProductController extends Controller
{
    public function index()
    {
        $product = Products::where('trangthai', 1)->get();
        $danhmuc = Categories::where('trangthai', 1)->get();

        $banner = Image::where('trangthai', 1)
            ->where('loai', 'bannerProduct')
            ->first();
        $viewData = [
            'product' => $product,
            'danhmuc' => $danhmuc,
            'banner' => $banner
        ];
        return view('templates.clients.product.index', $viewData);
    }
    public function detail($slug, Request $request)
    {
        if ($slug) {
            $product = Products::where('slug', $slug)->first();
            $comments = Comments::where('id_sanpham', $product->id)
                ->where('type', 'product')
                ->where('parent_id', 0)
                ->get();
            if ($product) {
                $related = Products::where('id_loaisanpham', $product->id_loaisanpham)->get();
            }
            $viewData = [
                'product' => $product,
                'related' => $related,
                'comments' => $comments,
                'url_current' => $request->url()
            ];
        }
        return view('templates.clients.product.detail', $viewData);
    }

    public function search(Request $request)
    {
        if ($request->keyword) {
            $product = Products::where('tensp', 'like', '%' . $request->keyword . '%')
                ->Where('trangthai', 1)->get()->sortBy('id_loaisanpham');
            return view('templates.clients.product.search', ['products' => $product]);
        }
    }
    
}
