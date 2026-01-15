<?php

namespace App\Actions\User\Update;

use App\Dto\User\UpdateUserDTO;
use App\Exceptions\Application\User\UpdateUserFailedException;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateUserAction
{
    public function __invoke(User $user, UpdateUserDTO $data): User
    {
        try {
            return DB::transaction(function () use ($user, $data) {
                $this->updateUserData($user, $data);
                $this->logSuccess($user);

                return $user;
            });
        } catch (Throwable $e) {
            $this->handleFailure($e, $user);
        }
    }

    private function updateUserData(User $user, UpdateUserDTO $data): void
    {
        $user->update([
            'name' => $data->name,
            'email' => $data->email,
            'phone' => $data->phone,
        ]);
    }

    private function logSuccess(User $user): void
    {
        Log::info('User profile updated successfully.', ['user_id' => $user->id]);
    }

    private function handleFailure(Throwable $e, User $user): never
    {
        throw new UpdateUserFailedException(
            previous: $e,
            context: ['user_id' => $user->id]
        );
    }
}
