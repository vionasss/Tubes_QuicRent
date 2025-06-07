<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;


class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|max:1000',
        ]);

        Review::create([
            'rating' => $request->rating,
            'message' => $request->message,
        ]);

        
        return redirect()->back()->with('success', 'Ulasan berhasil dikirim!');

    }
}

