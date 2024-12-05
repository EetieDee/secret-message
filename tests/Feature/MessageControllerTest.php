<?php

namespace Tests\Feature;

use App\Helpers\EncryptionHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Message;
use Illuminate\Support\Facades\Artisan;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    public function testCreateMessage()
    {
        $response = $this->postJson('/api/messages', [
            'text' => 'This is a secret message',
            'recipient' => 'recipient@example.com',
            'expiry' => '60'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'key']);
    }

    public function testReadMessage()
    {
        $dateNow = now();
        $seed = env('KEY_SEED');
        $recipient = 'recipient@example.com';

        $message = Message::create([
            'text' => 'encrypted-text',
            'recipient' => $recipient,
            'expiry' => '1111',
            'created_at' => $dateNow
        ]);

        $key = EncryptionHelper::encrypt($recipient . $dateNow, $seed);

        $response = $this->getJson('/api/messages/' . $message->id . '?key=' . $key);
        $response->assertStatus(200)
            ->assertJsonStructure(['text']);
    }

    public function testReadMessageWithInvalidKey()
    {
        $message = Message::create([
            'text' => 'encrypted-text',
            'recipient' => 'recipient@example.com',
            'expiry' => 'read_once',
            'created_at' => now()
        ]);

        $response = $this->getJson('/api/messages/' . $message->id, [
            'key' => 'invalid-key'
        ]);

        $response->assertStatus(403)
            ->assertJson(['error' => 'No Access']);
    }
}
