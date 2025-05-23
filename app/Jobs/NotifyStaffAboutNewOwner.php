<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\NewOwnerRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyStaffAboutNewOwner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $newOwner;

    public function __construct(User $newOwner)
    {
        $this->newOwner = $newOwner;
    }

    public function handle(): void
    {
        Log::info('NotifyStaffAboutNewOwner job started', ['owner_id' => $this->newOwner->user_id]);
        
        $staffMembers = User::whereIn('role', [0, 2, 3])
            ->where('status', 1)
            ->get();

        Log::info('Found staff members', ['count' => $staffMembers->count()]);

        foreach ($staffMembers as $staff) {
            try {
                Log::info('Attempting to notify staff member', ['staff_id' => $staff->user_id]);
                $staff->notify(new NewOwnerRegistration($this->newOwner));
                Log::info('Successfully notified staff member', ['staff_id' => $staff->user_id]);
            } catch (\Exception $e) {
                Log::error('Failed to notify staff member', [
                    'staff_id' => $staff->user_id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info('NotifyStaffAboutNewOwner job completed');
    }
}