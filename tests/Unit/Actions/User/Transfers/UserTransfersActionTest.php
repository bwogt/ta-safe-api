<?php

namespace Tests\Unit\Actions\User\Transfers;

use App\Actions\User\Transfers\UserTransfersAction;
use App\Enums\Device\DeviceTransferStatus;
use Database\Factories\DeviceTransferFactory;
use Database\Factories\UserFactory;

final class UserTransfersActionTest extends UserTransfersActionTestSetUp
{
    public function test_should_return_an_empty_collection_when_the_user_has_no_transfer_requests(): void
    {
        $user = UserFactory::new()->create();
        $transfers = (new UserTransfersAction)($user);

        $this->assertEmpty($transfers);
    }

    public function test_should_return_transfers_when_user_is_the_source(): void
    {
        $anotherTransfer = DeviceTransferFactory::new()->create([
            'source_user_id' => $this->sourceUser->id,
            'target_user_id' => UserFactory::new()->create() 
        ]);

        $transfers = (new UserTransfersAction)($this->sourceUser, null, 5);

        $this->assertEqualsCanonicalizing(
            [
                $this->pending->id,
                $this->accepted->id,
                $this->canceled->id,
                $this->rejected->id,
                $anotherTransfer->id,
            ],
            $transfers->pluck('id')->all(),
        );
    }

    public function test_should_return_transfers_when_user_is_the_target(): void
    {
        $anotherTransfer = DeviceTransferFactory::new()->create([
            'source_user_id' => UserFactory::new()->create(),
            'target_user_id' =>  $this->targetUser->id
        ]);

        $transfers = (new UserTransfersAction)($this->targetUser, null, 5);

        $this->assertEqualsCanonicalizing(
            [
                $this->pending->id,
                $this->accepted->id,
                $this->canceled->id,
                $this->rejected->id,
                $anotherTransfer->id,
            ],
            $transfers->pluck('id')->all(),
        );
    }

    public function test_should_return_all_transfers_of_the_user_when_the_status_param_is_null(): void
    {
        $transfers = (new UserTransfersAction)($this->sourceUser);

        $this->assertEqualsCanonicalizing(
            [
                $this->pending->id,
                $this->accepted->id,
                $this->canceled->id,
                $this->rejected->id,
            ],
            $transfers->pluck('id')->all(),
        );
    }

    public function test_should_return_only_pending_transfers_when_that_is_the_status(): void
    {
        $status = DeviceTransferStatus::PENDING;
        $transfers = (new UserTransfersAction)($this->sourceUser, $status);

        $this->assertCount(1, $transfers);
        $this->assertEquals($this->pending->id, $transfers->first()->id);
    }

    public function test_should_return_only_accepted_transfers_when_that_is_the_status(): void
    {
        $status = DeviceTransferStatus::ACCEPTED;
        $transfers = (new UserTransfersAction)($this->sourceUser, $status);

        $this->assertCount(1, $transfers);
        $this->assertEquals($this->accepted->id, $transfers->first()->id);
    }

    public function test_should_return_only_canceled_transfers_when_that_is_the_status(): void
    {
        $status = DeviceTransferStatus::CANCELED;
        $transfers = (new UserTransfersAction)($this->sourceUser, $status);

        $this->assertCount(1, $transfers);
        $this->assertEquals($this->canceled->id, $transfers->first()->id);
    }

    public function test_should_return_only_rejected_transfers_when_that_is_the_status(): void
    {
        $status = DeviceTransferStatus::REJECTED;
        $transfers = (new UserTransfersAction)($this->sourceUser, $status);

        $this->assertCount(1, $transfers);
        $this->assertEquals($this->rejected->id, $transfers->first()->id);
    }

    public function test_should_respect_transfers_per_page_limit(): void
    {
        DeviceTransferFactory::new()->create([
            'source_user_id' => $this->sourceUser->id,
            'target_user_id' => UserFactory::new()->create(),
        ]);

        $transfers = (new UserTransfersAction)($this->sourceUser);
        $this->assertCount(4, $transfers);

        $transfers = (new UserTransfersAction)($this->sourceUser, null, 5);
        $this->assertCount(5, $transfers);
    }
}
