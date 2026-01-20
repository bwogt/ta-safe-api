<?php

namespace Tests\Unit\Actions\Device\Register;

use App\Actions\Device\Register\RegisterDeviceAction;
use App\Dto\Device\RegisterDeviceDTO;
use App\Models\User;
use App\Traits\RandomNumberGenerator;
use Database\Factories\DeviceModelFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class RegisterDeviceActionTestSetUp extends TestCase
{
    use RandomNumberGenerator;
    use RefreshDatabase;

    protected User $user;
    protected RegisterDeviceAction $action;
    protected RegisterDeviceDTO $data;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userSetUp();
        $this->actionSetUp();
        $this->deviceDataSetUp();
    }

    private function userSetUp(): void
    {
        $this->user = UserFactory::new()->create();
    }

    private function actionSetUp(): void
    {
        $this->action = new RegisterDeviceAction;
    }

    private function deviceDataSetUp(): void
    {
        $deviceModel = DeviceModelFactory::new()->create();

        $this->data = new RegisterDeviceDTO(
            deviceModelId: $deviceModel->id,
            accessKey: $this->generateRandomNumber(44),
            color: 'black',
            imei1: $this->generateRandomNumber(15),
            imei2: $this->generateRandomNumber(15),
        );
    }
}
