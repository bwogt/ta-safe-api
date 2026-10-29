<?php

namespace Tests\Unit\Actions\User\Transfers;

use App\Models\DeviceTransfer;
use App\Models\User;
use Database\Factories\DeviceTransferFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTransfersActionTestSetUp extends TestCase
{
    protected User $sourceUser;
    protected User $targetUser;
    protected DeviceTransfer $pending;
    protected DeviceTransfer $accepted;
    protected DeviceTransfer $canceled;
    protected DeviceTransfer $rejected;

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userSetUp();
        $this->transferSetUp();
    }

    private function userSetUp(): void
    {
        $this->sourceUser = UserFactory::new()->create();
        $this->targetUser = UserFactory::new()->create();
    }

    private function transferSetUp(): void
    {
        $this->pending = DeviceTransferFactory::new()->create([
            'source_user_id' => $this->sourceUser->id,
            'target_user_id' => $this->targetUser->id,
        ]);

        $this->accepted = DeviceTransferFactory::new()
            ->accepted()
            ->create([
                'source_user_id' => $this->sourceUser->id,
                'target_user_id' => $this->targetUser->id,
            ]);

        $this->canceled = DeviceTransferFactory::new()
            ->canceled()
            ->create([
                'source_user_id' => $this->sourceUser->id,
                'target_user_id' => $this->targetUser->id,
            ]);

        $this->rejected = DeviceTransferFactory::new()
            ->rejected()
            ->create([
                'source_user_id' => $this->sourceUser->id,
                'target_user_id' => $this->targetUser->id,
            ]);
    }
}
