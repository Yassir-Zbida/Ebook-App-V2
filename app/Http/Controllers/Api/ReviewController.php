<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Get reviews for an ebook
     */
    public function index(Request $request, $ebookId)
    {
        $validator = Validator::make(['ebook_id' => $ebookId], [
            'ebook_id' => 'required|exists:ebooks,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ebook not found',
            ], 404);
        }

        $reviews = Review::where('ebook_id', $ebookId)
            ->approved()
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate average rating
        $averageRating = Review::where('ebook_id', $ebookId)
            ->approved()
            ->avg('rating');

        $totalReviews = Review::where('ebook_id', $ebookId)
            ->approved()
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'reviews' => $reviews,
                'summary' => [
                    'average_rating' => round($averageRating, 1),
                    'total_reviews' => $totalReviews,
                ]
            ]
        ]);
    }

    /**
     * Submit a review for an ebook
     */
    public function store(Request $request, $ebookId)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Check if ebook exists
        $ebook = Ebook::find($ebookId);
        if (!$ebook) {
            return response()->json([
                'success' => false,
                'message' => 'Ebook not found'
            ], 404);
        }

        // Check if user has purchased this ebook
        if (!$user->hasPurchased($ebookId)) {
            return response()->json([
                'success' => false,
                'message' => 'You can only review ebooks you have purchased'
            ], 403);
        }

        // Check if user already reviewed this ebook
        $existingReview = Review::where('user_id', $user->id)
            ->where('ebook_id', $ebookId)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this ebook'
            ], 400);
        }

        try {
            $review = Review::create([
                'user_id' => $user->id,
                'ebook_id' => $ebookId,
                'rating' => $request->rating,
                'review' => $request->review,
                'is_approved' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully',
                'data' => [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'review' => $review->review,
                    'created_at' => $review->created_at,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit review'
            ], 500);
        }
    }

    /**
     * Update user's review
     */
    public function update(Request $request, $reviewId)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        $review = Review::where('id', $reviewId)
            ->where('user_id', $user->id)
            ->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

        try {
            $review->update([
                'rating' => $request->rating,
                'review' => $request->review,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully',
                'data' => [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'review' => $review->review,
                    'updated_at' => $review->updated_at,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update review'
            ], 500);
        }
    }

    /**
     * Delete user's review
     */
    public function destroy(Request $request, $reviewId)
    {
        $user = $request->user();

        $review = Review::where('id', $reviewId)
            ->where('user_id', $user->id)
            ->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

        try {
            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete review'
            ], 500);
        }
    }

    /**
     * Get user's reviews
     */
    public function userReviews(Request $request)
    {
        $user = $request->user();

        $reviews = Review::where('user_id', $user->id)
            ->with('ebook:id,title,cover_image')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }
}
