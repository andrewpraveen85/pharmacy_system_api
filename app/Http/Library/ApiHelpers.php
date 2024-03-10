<?php
namespace App\Http\Library;

use Illuminate\Http\JsonResponse;

trait ApiHelpers
{
    protected function isAdmin($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan('admin');
        }

        return false;
    }

    protected function isManager($user): bool
    {

        if (!empty($user)) {
            return $user->tokenCan('manager');
        }

        return false;
    }

    protected function isCashier($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan('cashier');
        }

        return false;
    }

    protected function onSuccess($data, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function onError(int $code, string $message = ''): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
        ], $code);
    }

    protected function medicationValidationRules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
            'stock' => 'required|integer',
        ];
    }

    protected function userValidatedRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'role' => 'required|integer',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    protected function softStatusValidationRules(): array
    {
        return [
            'status' => 'required|string',
        ];
    }
}
