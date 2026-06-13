<?php

namespace App\Listeners;

use App\Events\ReviewSubmitted;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LogReviewForModeration
{
    public function handle(ReviewSubmitted $event): void
    {
        $review = $event->review->loadMissing('user');
        $product = $event->product;

        $payload = [
            'event' => 'review.submitted',
            'review_id' => $review->id,
            'product_id' => $product->id,
            'product_slug' => $product->slug,
            'product_name' => $product->name,
            'user_id' => $review->user_id,
            'user_name' => $review->user?->name,
            'rating' => $review->rating,
            'title' => $review->title,
            'content' => $review->content,
            'submitted_at' => $review->updated_at?->toIso8601String(),
        ];

        Log::info('Review submitted — pending moderation', $payload);

        $webhook = config('services.review_webhook.url');
        if ($webhook) {
            Http::timeout(5)->post($webhook, $payload);
        }
    }
}
