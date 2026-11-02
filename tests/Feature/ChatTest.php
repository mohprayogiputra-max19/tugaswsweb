<?php

namespace Tests\Feature;

use App\Events\MessageSent;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ChatTest extends TestCase
{
    public function test_only_admins_and_cashiers_can_open_chat(): void
    {
        $this->get(route('chat'))
            ->assertRedirect(route('login'));

        $this->actingAs(User::factory()->make(['role' => 'user']))
            ->get(route('chat'))
            ->assertForbidden();

        $this->actingAs(User::factory()->make(['role' => 'kasir']))
            ->get(route('chat'))
            ->assertOk();
    }

    public function test_admin_and_cashier_dashboards_link_to_chat(): void
    {
        $this->actingAs(User::factory()->make(['role' => 'kasir']))
            ->get(route('kasir.dashboard'))
            ->assertOk()
            ->assertSee(route('chat'), false);

        $this->actingAs(User::factory()->make(['role' => 'admin']))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee(route('chat'), false);
    }

    public function test_sending_a_chat_message_broadcasts_it(): void
    {
        Event::fake([MessageSent::class]);

        $this->actingAs(User::factory()->make([
            'name' => 'Kasir Satu',
            'role' => 'kasir',
        ]))
            ->postJson(route('chat.send'), ['message' => 'Tolong cek stok'])
            ->assertOk()
            ->assertJson(['status' => 'Pesan terkirim!']);

        Event::assertDispatched(MessageSent::class, function (MessageSent $event) {
            return $event->username === 'Kasir Satu'
                && $event->message === 'Tolong cek stok';
        });
    }

    public function test_sending_a_chat_message_reports_when_reverb_is_unavailable(): void
    {
        config([
            'broadcasting.default' => 'reverb',
            'broadcasting.connections.reverb.options.host' => '127.0.0.1',
            'broadcasting.connections.reverb.options.port' => 1,
            'broadcasting.connections.reverb.options.scheme' => 'http',
            'broadcasting.connections.reverb.client_options' => [
                'connect_timeout' => 1,
                'timeout' => 1,
            ],
        ]);

        $this->actingAs(User::factory()->make(['role' => 'kasir']))
            ->postJson(route('chat.send'), ['message' => 'Tes'])
            ->assertServiceUnavailable()
            ->assertJsonPath('message', 'Server Reverb tidak berjalan atau tidak dapat dijangkau. Jalankan php artisan reverb:start.');
    }

    public function test_chat_message_is_broadcast_on_a_private_chat_channel(): void
    {
        $event = new MessageSent('Kasir Satu', 'Tes');

        $this->assertSame('private-chat', $event->broadcastOn()[0]->name);
    }

    public function test_admin_and_cashier_can_subscribe_to_chat_channel(): void
    {
        $this->actingAs(User::factory()->make(['role' => 'kasir']))
            ->postJson('/broadcasting/auth', [
                'socket_id' => '123.456',
                'channel_name' => 'private-chat',
            ])
            ->assertOk();

        $this->actingAs(User::factory()->make(['role' => 'user']))
            ->postJson('/broadcasting/auth', [
                'socket_id' => '123.456',
                'channel_name' => 'private-chat',
            ])
            ->assertForbidden();
    }

    public function test_message_is_required_and_limited_to_255_characters(): void
    {
        $this->actingAs(User::factory()->make(['role' => 'kasir']))
            ->postJson(route('chat.send'), ['message' => str_repeat('a', 256)])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('message');
    }
}
