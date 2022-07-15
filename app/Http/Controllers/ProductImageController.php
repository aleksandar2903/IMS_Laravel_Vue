<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Intervention\Image\Facades\Image;

use App\Models\ProductImage;

use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $images = $request->file('images');
        $increment = 0;
        foreach($images as $image){
            $img = Image::make($image->path());
            $imageName = time() .$increment. '.' . $image->extension();
            $img->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/storage/images/w500_'.$imageName));

            $image->move(public_path('/storage/images/'), 'original_'.$imageName);

            ProductImage::create([
                'original' => 'original_'.$imageName,
                'w500' => 'w500_'.$imageName,
                'product_id' => $request->input('product_id'),
            ]);

            $increment++;
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductImage $image)
    {
        if ($image != null) {
            File::delete(public_path('/storage/images/' . $image->original));
            File::delete(public_path('/storage/images/' . $image->w500));
            $image->delete();
        }

        return back();
    }
}
