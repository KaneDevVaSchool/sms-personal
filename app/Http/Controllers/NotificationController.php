<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user === null) {
            return $this->failure('unauthenticated', 'Authentication required.', false, 401);
        }

        return $this->success(
            $user->notifications()
                ->orderByDesc('created_at')
                ->paginate(30)
        );
    }

    public function markRead(Request $request, string $notification): JsonResponse
    {
        $user = $request->user();
        if ($user === null) {
            return $this->failure('unauthenticated', 'Authentication required.', false, 401);
        }

        $dbNotification = $user->notifications()->whereKey($notification)->firstOrFail();
        $dbNotification->markAsRead();

        return $this->success(['read' => true]);
    }
}
