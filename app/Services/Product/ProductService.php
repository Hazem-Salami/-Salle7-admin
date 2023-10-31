<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductService extends BaseService
{
    public function getProducts(Request $request): Response
    {
        $storehouse = $request->get('storehouse');

        $products = Product::where('user_id', $storehouse->user_id)->paginate(\request('size'));

        return $this->customResponse(true, 'get Products Success', $products);
    }
}
