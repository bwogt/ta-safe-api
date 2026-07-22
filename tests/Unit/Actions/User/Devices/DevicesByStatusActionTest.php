<?php

namespace Tests\Unit\Actions\User\Devices;

use App\Actions\User\Devices\DevicesByStatusAction;
use App\Enums\Device\DeviceValidationStatus;
use App\Models\Device;
use App\Models\User;
use Database\Factories\DeviceFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DevicesByStatusActionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Device $pendingDevice;
    private Device $inAnalysisDevice;
    private Device $validatedDevice;
    private Device $rejectedDevice;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userSetUp();
        $this->deviceSetUp();
    }

    private function userSetUp(): void
    {
        $this->user = UserFactory::new()->create();
    }

    private function deviceSetUp(): void
    {
        $this->pendingDevice = DeviceFactory::new()->for($this->user)->create();
        $this->validatedDevice = DeviceFactory::new()->for($this->user)->validated()->create();
        $this->inAnalysisDevice = DeviceFactory::new()->for($this->user)->inAnalysis()->create();
        $this->rejectedDevice = DeviceFactory::new()->for($this->user)->rejected()->create();
    }

    public function test_should_return_pending_devices(): void
    {
        $status = DeviceValidationStatus::PENDING;
        $paginatedDevices = (new DevicesByStatusAction)($this->user, $status);

        $this->assertCount(1, $paginatedDevices->items());
        $this->assertEquals($this->pendingDevice->id, $paginatedDevices->items()[0]->id);
    }

    public function test_should_return_validated_devices(): void
    {
        $status = DeviceValidationStatus::VALIDATED;
        $paginatedDevices = (new DevicesByStatusAction)($this->user, $status);

        $this->assertCount(1, $paginatedDevices->items());
        $this->assertEquals($this->validatedDevice->id, $paginatedDevices->items()[0]->id);
    }

    public function test_should_return_devices_in_analysis(): void
    {
        $status = DeviceValidationStatus::IN_ANALYSIS;
        $paginatedDevices = (new DevicesByStatusAction)($this->user, $status);

        $this->assertCount(1, $paginatedDevices->items());
        $this->assertEquals($this->inAnalysisDevice->id, $paginatedDevices->items()[0]->id);
    }

    public function test_should_return_rejected_devices(): void
    {
        $status = DeviceValidationStatus::REJECTED;
        $paginatedDevices = (new DevicesByStatusAction)($this->user, $status);

        $this->assertCount(1, $paginatedDevices->items());
        $this->assertEquals($this->rejectedDevice->id, $paginatedDevices->items()[0]->id);
    }

    public function test_should_return_four_devices_per_page(): void
    {
        $status = DeviceValidationStatus::PENDING;
        $paginatedDevices = (new DevicesByStatusAction)($this->user, $status);

        $this->assertEquals(4, $paginatedDevices->perPage());
    }
}
