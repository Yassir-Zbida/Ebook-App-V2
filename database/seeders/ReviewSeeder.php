<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Ebook;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users and ebooks to create reviews for
        $users = User::where('role', 'customer')->take(5)->get();
        $ebooks = Ebook::take(3)->get();

        if ($users->isEmpty() || $ebooks->isEmpty()) {
            $this->command->info('No users or ebooks found. Skipping review seeding.');
            return;
        }

        $reviews = [
            [
                'user_id' => $users->first()->id,
                'ebook_id' => $ebooks->first()->id,
                'rating' => 5,
                'review' => 'Excellent ebook! Very informative and well-written. Highly recommended!',
                'is_approved' => true,
                'status' => 'approved',
            ],
            [
                'user_id' => $users->get(1)->id ?? $users->first()->id,
                'ebook_id' => $ebooks->first()->id,
                'rating' => 4,
                'review' => 'Great content, easy to follow. Would recommend to others.',
                'is_approved' => true,
                'status' => 'approved',
            ],
            [
                'user_id' => $users->get(2)->id ?? $users->first()->id,
                'ebook_id' => $ebooks->get(1)->id ?? $ebooks->first()->id,
                'rating' => 3,
                'review' => 'Good book, but could be more detailed in some areas.',
                'is_approved' => true,
                'status' => 'approved',
            ],
            [
                'user_id' => $users->get(3)->id ?? $users->first()->id,
                'ebook_id' => $ebooks->get(1)->id ?? $ebooks->first()->id,
                'rating' => 5,
                'review' => 'Amazing! This book changed my perspective completely.',
                'is_approved' => false,
                'status' => 'pending',
            ],
            [
                'user_id' => $users->get(4)->id ?? $users->first()->id,
                'ebook_id' => $ebooks->get(2)->id ?? $ebooks->first()->id,
                'rating' => 2,
                'review' => 'Not what I expected. Disappointing content.',
                'is_approved' => false,
                'status' => 'rejected',
            ],
        ];

        foreach ($reviews as $reviewData) {
            Review::create($reviewData);
        }

        $this->command->info('Sample reviews created successfully!');
    }
}
