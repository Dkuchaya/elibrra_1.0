<?php

namespace App\Services;

use App\Models\Book;
use App\Models\User;

class BookAccessService
{
    public function canRead(Book $book, ?User $user): bool
    {
        if (! $book->is_active) {
            return false;
        }

        if (! $book->subscription_required) {
            return true;
        }

        if (! $user) {
            return false;
        }

        return $this->hasUserSubscription($user)
            || $this->hasSchoolSubscription($user);
    }

    private function hasUserSubscription(User $user): bool
    {
        return $user->userSubscriptions()
            ->where('status', 'active')
            ->whereDate('expires_at', '>=', now())
            ->exists();
    }

    private function hasSchoolSubscription(User $user): bool
    {
        if (! $user->school_id) {
            return false;
        }

        return $user->school->schoolSubscriptions()
            ->where('status', 'active')
            ->whereDate('expires_at', '>=', now())
            ->exists();
    }

    public function recordView(Book $book): void
    {
        $book->increment('views');
    }
}