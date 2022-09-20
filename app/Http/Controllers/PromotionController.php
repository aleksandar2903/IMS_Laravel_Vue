<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;


class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promotions = Promotion::latest('updated_at')->get();

        return view('products.promotions.index', compact('promotions'));
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
        $request->validate([
            'title' => 'required|min:3',
            'description' => 'required|min:3',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $data = $request->all();

        $img = Image::make($request->image->path());
        $imageName = time() . $request->image->extension();
        $img->resize(1280, 720, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('/storage/images/' . $imageName));
        $data['image'] = $imageName;

        Promotion::create($data);
        return redirect('/products/promotions')
            ->withStatus(__('Promotion successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function show(Promotion $promotion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function edit(Promotion $promotion)
    {
        return view('products.promotions.edit', compact('promotion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'title' => 'required|min:3',
            'description' => 'required|min:3',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $data = $request->all();
        if ($request->image) {
            File::delete(public_path('/storage/images/' . $promotion->image));
            $img = Image::make($request->image->path());
            $imageName = time() . $request->image->extension();
            $img->resize(1280, 720, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/storage/images/' . $imageName));
            $data['image'] = $imageName;
        }

        $promotion->update($data);

        return redirect('/products/promotions')
            ->withStatus(__('Promotion successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promotion $promotion)
    {
        //
    }
}
