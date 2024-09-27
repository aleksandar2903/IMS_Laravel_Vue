<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Review::where('product_id', $request->product_id)->with('product')->orderBy("created_at", "desc")->take(15)->get();
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
            'product_id' => 'required|integer',
            'rating' => 'required|integer|between:1,5',
            'text' => 'required|string',
            'title' => 'nullable|string',
        ]);

        Auth::user()->reviews()->create([
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'text' => $request->text,
            'title' => $request->title
        ]);

        return response()->json(['message' => 'Review created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        if ($review->user_id != auth()->user()->id) {
            return response()->json(['message' => 'You are not authorized to edit this review'], 403);
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'text' => 'required|string',
            'title' => 'nullable|string',
        ]);

        $review->update([
            'rating' => $request->rating,
            'text' => $request->text,
            'title' => $request->title,
        ]);

        return response()->json(['message' => 'Review updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully']);
    }
}
