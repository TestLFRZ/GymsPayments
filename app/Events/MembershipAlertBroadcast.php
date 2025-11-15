<?php

namespace App\Events;

use App\Models\Alert;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MembershipAlertBroadcast implements ShouldBroadcast
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Alert $alert)
    {
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel("tenant.{$this->alert->tenant_id}");
    }

    public function broadcastWith(): array
    {
        return $this->alert->toArray();
    }
}
