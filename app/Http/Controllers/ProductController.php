<?php

namespace App\Http\Controllers;

use App\Models\Product;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    
    public function productsAll(Request $request){
       
     $product = Product::all();
        return response()->json([
            "status"=> "success",
            //"message"=>"Ürününüz Başarılı bir şekilde oluşturulmuştur!",
            "product"=> $product,
        ]); 
           
    }
    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function ProductDetail(Request $request, $id,Product $product): JsonResponse
{
    $product = Product::findOrFail($id);
    $token = session('token');
    return response()->json([
        'status'=> 'success',
        'data'=> $product, 
        'message'=> 'Ürürünüz Başarılı Bir Şekilde Görüntülenmiştir !'
    ],200);
}
 /**
     * @param Product $product
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
{
    try {
        $validatedData = $request->validate([
            'name' => 'required',
            'detail' => 'required',
            'price' => 'required|max:6',
        ], [
            'name.required' => 'Lütfen ürün ismini boş bırakmayınız!',
            'detail.required' => 'Lütfen ürün detayını boş bırakmayınız!',
            'price.required' => 'Lütfen ürün fiyatını boş bırakmayınız!',
            'price.max' => 'Lütfen ürün fiyatını en fazla 6 karakter giriniz!',
        ]);

        $product = Product::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Ürününüz başarılı bir şekilde oluşturulmuştur.',
           
        ], 201);

    } catch ( ValidationException $e) {
        return response()->json([
            'status' => 'error',
            'errors' => $e->errors() 
        ], 422);
    }
}

    public function createShow(){
        return response()->json([
            'status' => 'success',
        ]);
    }

    /** 
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Request $request, $id){// show 
        $product = Product::findOrFail($id);
        $token = session('token');

        return response()->json([
            'status'=> 'success',
           
            'data'=> $product
        ],200); 
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(Request $request, $id,Product $product): JsonResponse
{
    $validator = Validator::make($request->all(),[
        'name' => 'required',
        'detail' => 'required',
        'price' => 'required|max:6',
    ], [
        'name.required' => 'Lütfen ürün ismini boş bırakmayınız!',
        'detail.required' => 'Lütfen ürün detayını boş bırakmayınız!',
        'price.required' => 'Lütfen ürün fiyatını boş bırakmayınız!',
        'price.max' => 'Lütfen ürün fiyatını en fazla 6 karakter giriniz!',
    ]);
    if($validator->fails()) {
        return response()->json([
            'status'=> 'error',
            'errors'=> $validator->errors()
        ],422);
      }

    $product = Product::findOrFail($id);
    $product->name= $request->name;
    $product->detail= $request->detail;
    $product->price= $request->price;
    $product->save();
    $token = session('token');

    return response()->json([
        'status'=> 'success',
       
        'message'=> 'Ürününüz Başarılı Bir Şekilde güncellenmiştir !'
    ],200);
}

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product,$id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json("Böyle Bir ürün bulunamadı");
        }
        $product->delete();
        $token = session('token');
        return response()->json([
            'status' => 'success',
            'message' => 'Ürününüz başarılı bir şekilde silinmiştir.',
        ], 200);
    }
}