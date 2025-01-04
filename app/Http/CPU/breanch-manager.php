<?php

namespace App\CPU;

use App\Branch;
use Illuminate\Support\Facades\DB;
class BranchManager
{
   

  
   
   

    public static function search_branches($name, $limit = 10, $offset = 1)
    {
        $key = explode(' ', $name);
        $paginator = Branch::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $paginator->total(),
            'limit' => (integer)$limit,
            'offset' => (integer)$offset,
            'products' => $paginator->items()
        ];
    }

    public static function product_image_path($image_type)
    {
        $path = '';
        if ($image_type == 'thumbnail') {
            $path = asset('storage/app/public/product/thumbnail');
        } elseif ($image_type == 'product') {
            $path = asset('storage/app/public/product');
        }
        return $path;
    }

   
   

   

  
   
}
