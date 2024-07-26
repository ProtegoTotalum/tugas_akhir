<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Http\Request;
use App\Models\User;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {
        $user = User::find($request->input('id_dokter'));
        if ($user && $user->fcm_token) {
            $deviceToken = $user->fcm_token;
    
            $messaging = app('firebase.messaging');
            $message = CloudMessage::withTarget('token', $deviceToken)
                ->withNotification(Notification::create('Diagnosa Baru', 'Diagnosa baru memerlukan analisa dan verifikasi anda'))
                ->withData(['id_diagnosa' => $request->input('id_diagnosa')]);
    
            $messaging->send($message);
    
            return response()->json(['success' => true, 'message' => 'Notification sent successfully']);
        }
        return response()->json(['success' => false, 'message' => 'User or token not found'], 404);
    }
}