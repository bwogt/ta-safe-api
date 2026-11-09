<?php

namespace Tests\Unit\Models\Device\Transfers;

use App\Models\Device;
use Database\Factories\DeviceFactory;
use Database\Factories\DeviceTransferFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

final class DeviceAcceptedTransfersTest extends TestCase
{
    use LazilyRefreshDatabase;

    private Device $device;

    protected function setUp(): void
    {
        parent::setUp();
        $this->deviceTransferSetUp();
    }

    private function deviceTransferSetUp(): void
    {
        $this->device = DeviceFactory::new()
            ->for(UserFactory::new()->create())
            ->create();

        // Same Device
        DeviceTransferFactory::new()->for($this->device)->create();
        DeviceTransferFactory::new()->canceled()->for($this->device)->create();
        DeviceTransferFactory::new()->rejected()->for($this->device)->create();

        // Another Device
        DeviceTransferFactory::new()->accepted()->create();
    }

    public function test_should_return_only_accepted_transfers_belonging_to_the_device(): void
    {
        $accepted = DeviceTransferFactory::new()
            ->accepted()
            ->for($this->device)
            ->count(2)
            ->create();

        $this->assertEqualsCanonicalizing(
            $accepted->pluck('id')->all(),
            $this->device->acceptedTransfers->pluck('id')->all()
        );
    }

    public function test_should_return_empty_collection_when_device_has_no_accepted_transfers(): void
    {
        $this->assertEmpty($this->device->acceptedTransfers);
    }
}
