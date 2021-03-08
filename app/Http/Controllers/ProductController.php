<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = ProductModel::orderBy('waktu', 'DESC')->get();
        $response = [
            'message' => 'List Product',
            'response' => $products,
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => ['required'],
            'nama' => ['required'],
            'harga' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $products = ProductModel::create($request->all());
            $response = [
                'message' => 'Product Berhasil Ditambahkan',
                'data' => $products,
            ];
            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Product Gagal Ditambahkan ' . $e->errorInfo,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $products = ProductModel::findOrFail($id);
        $response = [
            'message' => 'Detail Products',
            'data' => $products,
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $products = ProductModel::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'code' => ['required'],
            'nama' => ['required'],
            'harga' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $products->update($request->all());
            $response = [
                'message' => 'Product Berhasil Diupdate',
                'data' => $products,
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Product Gagal Diupdate ' . $e->errorInfo,
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $products = ProductModel::findOrFail($id);

        try {
            $products->delete($products);
            $response = [
                'message' => 'Product Berhasil Dihapus',
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Product Gagal Dihapus ' . $e->errorInfo,
            ]);
        }

    }
}
