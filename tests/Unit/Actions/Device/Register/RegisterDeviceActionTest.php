<?php

namespace Tests\Unit\Actions\Device\Register;

use App\Exceptions\Application\Device\RegisterDeviceFailedException;
use App\Models\Device;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RegisterDeviceActionTest extends RegisterDeviceActionTestSetUp
{
    public function test_should_return_an_instance_of_the_device_when_the_action_is_successful(): void
    {
        $this->assertInstanceOf(Device::class, ($this->action)($this->user, $this->data));
    }

    public function test_should_increase_the_number_of_devices_linked_to_the_user(): void
    {
        $this->assertCount(0, $this->user->devices);

        ($this->action)($this->user, $this->data);
        $this->user->refresh();

        $this->assertCount(1, $this->user->devices);
    }

    public function test_should_register_the_device_correctly_based_on_the_data_provided(): void
    {
        $device = ($this->action)($this->user, $this->data);

        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'user_id' => $this->user->id,
            'device_model_id' => $this->data->deviceModelId,
            'color' => $this->data->color,
            'imei_1' => $this->data->imei1,
            'imei_2' => $this->data->imei2,
        ]);
    }

    public function test_should_register_an_invoice_for_the_device(): void
    {
        $device = ($this->action)($this->user, $this->data);

        $this->assertDatabaseHas('invoices', [
            'device_id' => $device->id,
            'access_key' => $this->data->accessKey,
        ]);
    }

    public function test_the_validation_attributes_of_an_invoice_should_be_set_to_null(): void
    {
        $device = ($this->action)($this->user, $this->data);

        $this->assertNull($device->invoice->consumer_cpf);
        $this->assertNull($device->invoice->consumer_name);
        $this->assertNull($device->invoice->consumer_description);
    }

    public function test_should_throw_register_device_failed_exception_on_failure(): void
    {
        $this->expectException(RegisterDeviceFailedException::class);

        DB::shouldReceive('transaction')->once()
            ->andThrow(new Exception('Simulates a transaction error',
                Response::HTTP_INTERNAL_SERVER_ERROR
            ));

        ($this->action)($this->user, $this->data);
    }
}
