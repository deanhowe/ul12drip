<?php

namespace Tests\Feature;

use App\Mail\WelcomeMail;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderShipped;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that WelcomeMail can be sent.
     */
    public function test_welcome_mail_can_be_sent(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        Mail::to($user->email)->send(new WelcomeMail($user));

        Mail::assertSent(WelcomeMail::class, function ($mail) use ($user) {
            return $mail->user->id === $user->id;
        });
    }

    /**
     * Test that OrderShipped notification can be sent.
     */
    public function test_order_shipped_notification_can_be_sent(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        Notification::send($user, new OrderShipped($order));

        Notification::assertSentTo(
            $user,
            OrderShipped::class,
            function ($notification, $channels) use ($order) {
                return $notification->order->id === $order->id &&
                       in_array('mail', $channels) &&
                       in_array('database', $channels);
            }
        );
    }

    /**
     * Test that OrderShipped notification is stored in database.
     */
    public function test_order_shipped_notification_is_stored_in_database(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $user->notify(new OrderShipped($order));

        $this->assertCount(1, $user->notifications);
        $this->assertEquals(
            'Order #'.$order->order_number.' has been shipped.',
            $user->notifications->first()->data['message']
        );
    }
}
