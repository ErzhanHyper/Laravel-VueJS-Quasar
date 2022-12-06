<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function application_create($email)
    {
        Mail::raw("У вас новая заявка! http://192.168.0.20/application", function ($message) use ($email) {
            $message->to($email, '')->subject('Новая заявка!');
        });
    }

    public function event_create($email, $title, $body)
    {
        try {
            Mail::raw($body, function ($message) use ($email, $title) {
                $message->to($email, '')->subject($title);
            });
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function test()
    {
        $email = 'e.shaldybaev@recycle.kz';
        Mail::raw("Test! http://192.168.0.20/application", function ($message) use ($email) {
            $message->to($email, '')->subject('test!');
        });
    }
}
