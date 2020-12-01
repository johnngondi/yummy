<?php


namespace App\Http\Controllers\api\v1\Helpers;


use App\Models\Review;
use Illuminate\Support\Collection;

class ReviewHelper
{
    public static function formatReviews($reviews)
    {
        $formattedReviews = [];

        foreach ($reviews as $review){
            $review = Review::find($review->id);
            $formattedReview = [
                'id' => $review->id,
                'rating' => $review->rating,
                'review' => $review->review,
                'date' => $review->created_at->diffForHumans(),
                'author' => [
                    'name' => $review->author->first_name . ' ' . $review->author->last_name,
                    'photo' => $review->author->profile_photo_path
                ]
            ];

            array_push($formattedReviews, $formattedReview);
        }

        return $formattedReviews;
    }
}
