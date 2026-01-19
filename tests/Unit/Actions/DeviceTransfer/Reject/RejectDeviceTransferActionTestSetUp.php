<?php

namespace Tests\Unit\Actions\DeviceTransfer\Reject;

use App\Actions\DeviceTransfer\Reject\RejectDeviceTransferAction;
use App\Models\DeviceTransfer;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class RejectDeviceTransferActionTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $sourceUser;
    protected User $targetUser;
    protected DeviceTransfer $transfer;
    protected RejectDeviceTransferAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionSetUp();
        $this->userSetUp();
        $this->deviceTransferSetUp();
    }

    private function actionSetUp(): void
    {
        $this->action = new RejectDeviceTransferAction;
    }

    private function userSetUp(): void
    {
        $this->sourceUser = UserFactory::new()->create();
        $this->targetUser = UserFactory::new()->create();
    }

    private function deviceTransferSetUp(): void
    {
        $this->transfer = DeviceTransfer::factory()->create([
            'source_user_id' => $this->sourceUser->id,
            'target_user_id' => $this->targetUser->id,
        ]);
    }
}
