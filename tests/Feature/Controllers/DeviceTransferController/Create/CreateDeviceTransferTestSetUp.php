<?php

namespace Tests\Feature\Controllers\DeviceTransferController\Create;

use App\Enums\FlashMessage\FlashMessageType;
use App\Models\Device;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Asserts\AccessAsserts;
use Tests\TestCase;

class CreateDeviceTransferTestSetUp extends TestCase
{
    use AccessAsserts;
    use RefreshDatabase;

    protected User $sourceUser;
    protected User $targetUser;
    protected Device $device;

    protected function setUp(): void
    {
        parent::SetUp();

        $this->userSetUp();
        $this->deviceSetUp();
    }

    private function userSetUp(): void
    {
        $this->sourceUser = UserFactory::new()->create();
        $this->targetUser = UserFactory::new()->create();
    }

    private function deviceSetUp(): void
    {
        $this->device = Device::factory()
            ->for($this->sourceUser)
            ->validated()
            ->create();
    }

    protected function route(): string
    {
        return route('api.device.transfer.create', $this->device);
    }

    protected function flashMessage(): array
    {
        return [
            'type' => FlashMessageType::SUCCESS,
            'msg' => trans('actions.device_transfer.success.create'),
        ];
    }
}
