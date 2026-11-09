<?php

namespace Tests\Unit\Models\DeviceTransfer\Scopes;

use App\Models\DeviceTransfer;
use Database\Factories\DeviceTransferFactory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

final class DeviceTransferAcceptedScopeTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_should_return_only_accepted_transfers(): void
    {
        $accepted = DeviceTransferFactory::new()
            ->count(2)
            ->accepted()
            ->create();

        DeviceTransferFactory::new()->create();
        DeviceTransferFactory::new()->canceled()->create();
        DeviceTransferFactory::new()->rejected()->create();

        $result = DeviceTransfer::query()->accepted()->get();

        $this->assertCount(2, $result);

        $this->assertEqualsCanonicalizing(
            $accepted->pluck('id')->all(),
            $result->pluck('id')->all()
        );
    }

    public function test_should_order_by_most_recent_update(): void
    {
        $this->travelTo(now()->subDay());
        $old = DeviceTransferFactory::new()->accepted()->create();

        $this->travelBack();
        $new = DeviceTransferFactory::new()->accepted()->create();

        $this->assertSame(
            [$new->id, $old->id],
            DeviceTransfer::query()->accepted()->pluck('id')->all()
        );
    }

    public function test_should_return_an_empty_list_when_there_are_no_accepted_transfers(): void
    {
        $result = DeviceTransfer::query()->accepted()->get();
        $this->assertEmpty($result);
    }
}
