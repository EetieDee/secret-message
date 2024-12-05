<?php

namespace App\Console\Commands;

use App\Helpers\EncryptionHelper;
use Illuminate\Console\Command;

class DeleteExpiredMessages extends Command
{
    protected $signature = 'messages:delete-expired';
    protected $description = 'Delete messages that have expired';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $messages = Message::where('expiry', '!=', 'read_once')
            ->get();

        foreach ($messages as $message) {
            $expiryPeriod = (int) $message->expiry;
            if ($message->created_at->addMinutes($expiryPeriod)->isPast()) {
                $message->delete();
            }
        }
        $this->info('Expired messages deleted successfully.');
    }
}
