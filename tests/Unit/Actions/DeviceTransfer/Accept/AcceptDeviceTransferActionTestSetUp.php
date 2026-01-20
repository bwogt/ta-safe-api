<?php

namespace Tests\Unit\Actions\DeviceTransfer\Accept;

use App\Actions\DeviceTransfer\Accept\AcceptDeviceTransferAction;
use App\Models\DeviceTransfer;
use App\Models\User;
use Database\Factories\DeviceTransferFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class AcceptDeviceTransferActionTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $sourceUser;
    protected User $targetUser;
    protected DeviceTransfer $deviceTransfer;
    protected AcceptDeviceTransferAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userSetUp();
        $this->deviceTransferSetUp();
        $this->actionSetUp();
    }

    private function userSetUp(): void
    {
        $this->sourceUser = UserFactory::new()->create();
        $this->targetUser = UserFactory::new()->create();
    }

    private function deviceTransferSetUp(): void
    {
        $this->deviceTransfer = DeviceTransferFactory::new()->create([
            'source_user_id' => $this->sourceUser->id,
            'target_user_id' => $this->targetUser->id,
        ]);
    }

    private function actionSetUp(): void
    {
        $this->action = new AcceptDeviceTransferAction;
    }
}
