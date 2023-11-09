<?php

namespace App\Http\Controllers;

use Inertia\Response;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): Response
    {
        return inertia(
            'Notification/Index',
            [
                'notifications' => $request->user()     // passing all notifications data
                    ->notifications()->paginate(10)
            ]
        );
    }
}
