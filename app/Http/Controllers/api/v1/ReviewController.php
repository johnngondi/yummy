<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\api\v1\Helpers\ReviewHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\DataResource;
use App\Models\Review;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = ReviewHelper::formatReviews(DB::table('reviews')->latest()->paginate(10)->items());
        $nextPage = DB::table('reviews')->latest()->paginate(10)->nextPageUrl();

        return new DataResource([
            'reviews' => $reviews,
            'nextPage' => $nextPage
        ]);
    }

    public function store(Request $request)
    {
        try {
            $this->authorize('create', new Review());

            $newReview = json_decode($request->getContent());

            $review = Review::create([
                'user_id' => \auth()->user()->id,
                'review' => $newReview->review,
                'rating' => $newReview->rating,
                'order_id' => $newReview->order
            ]);

            return new DataResource([
                'message' => "Review Added successfully"
            ]);

        } catch (AuthorizationException $e) {
            return response([
                'message' => $e->getMessage()
            ], 401);
        }


    }
}
