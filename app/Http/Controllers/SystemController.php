<?php
namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\Medication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class SystemController extends Controller
{
    use ApiHelpers;

    public function medication(Request $request): JsonResponse
    {

        if ($this->isAdmin($request->user()) || $this->isManager($request->user()) || $this->isCashier($request->user())) {
            $post = DB::table('medications')->get();
            return $this->onSuccess($post, 'Medication Retrieved');
        }

        return $this->onError(401, 'Unauthorized Access');
    }

    public function singleMedication(Request $request, $id): JsonResponse
    {
        if ($this->isAdmin($request->user()) || $this->isManager($request->user()) || $this->isCashier($request->user())) {
            $post = DB::table('medications')->where('id', $id)->first();
            if (!empty($post)) {
                return $this->onSuccess($post, 'Medication Retrieved');
            }
            return $this->onError(404, 'Medication Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }

    public function createMedication(Request $request): JsonResponse
    {

        if ($this->isAdmin($request->user())) {
            $validator = Validator::make($request->all(), $this->medicationValidationRules());
            if ($validator->passes()) {
                $post = new Medication();
                $post->name = $request->input('name');
                $post->description = $request->input('description');
                $post->stock = $request->input('stock');
                $post->munit = $request->input('munit');
                $post->save();

                return $this->onSuccess($post, 'Medication Created');
            }
            return $this->onError(400, $validator->errors());
        }

        return $this->onError(401, 'Unauthorized Access');

    }

    public function updateMedication(Request $request, $id): JsonResponse
    {
        if ($this->isAdmin($request->user()) || $this->isManager($request->user()) || $this->isCashier($request->user())) {
            $validator = Validator::make($request->all(), $this->medicationValidationRules());
            if ($validator->passes()) {
                $post = Medication::find($id);
                $post->name = $request->input('name');
                $post->description = $request->input('description');
                $post->stock = $request->input('stock');
                $post->munit = $request->input('munit');
                $post->save();

                return $this->onSuccess($post, 'Medication Updated');
            }
            return $this->onError(400, $validator->errors());
        }

        return $this->onError(401, 'Unauthorized Access');
    }

    public function softStatusMedication(Request $request, $id): JsonResponse
    {
        if ($this->isAdmin($request->user()) || $this->isManager($request->user())) {
            $validator = Validator::make($request->all(), $this->softStatusValidationRules());
            if ($validator->passes()) {
                $post = Medication::find($id);
                $post->status = $request->input('status');
                $post->save();

                return $this->onSuccess($post, 'Medication Updated');
            }
            return $this->onError(400, $validator->errors());
        }

        return $this->onError(401, 'Unauthorized Access');
    }

    public function deleteMedication(Request $request, $id): JsonResponse
    {
        if ($this->isAdmin($request->user())) {
            $post = Medication::find($id);
            $post->delete();
            if (!empty($post)) {
                return $this->onSuccess($post, 'Medication Deleted');
            }
            return $this->onError(404, 'Medication Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }

    public function users(Request $request): JsonResponse
    {
        if ($this->isAdmin($request->user()) || $this->isManager($request->user()) || $this->isCashier($request->user())) {
            $post = DB::table('users')->get();
            return $this->onSuccess($post, 'Users Retrieved');
        }

        return $this->onError(401, 'Unauthorized Access');
    }

    public function singleUser(Request $request, $id): JsonResponse
    {
        if ($this->isAdmin($request->user()) || $this->isManager($request->user()) || $this->isCashier($request->user())) {
            $post = DB::table('users')->where('id', $id)->first();
            if (!empty($post)) {
                return $this->onSuccess($post, 'User Retrieved');
            }
            return $this->onError(404, 'User Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }

    public function createUser(Request $request): JsonResponse
    {
        if ($this->isAdmin($request->user())) {
            $validator = Validator::make($request->all(), $this->userValidatedRules());
            if ($validator->passes()) {
                $user = User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'role' => $request->input('role'),
                    'username' => $request->input('username'),
                    'password' => Hash::make($request->input('password')),
                ]);
                return $this->onSuccess($user, 'User Created');
            }
            return $this->onError(400, $validator->errors());
        }

        return $this->onError(401, 'Unauthorized Access');

    }

    public function updateUser(Request $request, $id): JsonResponse
    {
        if ($this->isAdmin($request->user()) || $this->isManager($request->user()) || $this->isCashier($request->user())) {
            $validator = Validator::make($request->all(), $this->userValidatedRules());
            if ($validator->passes()) {
                $post = User::find($id);
                $post->name = $request->input('name');
                $post->email = $request->input('email');
                $post->role = $request->input('role');
                $post->username = $request->input('username');
                $post->password = Hash::make($request->input('password'));
                $post->save();

                return $this->onSuccess($post, 'User Updated');
            }
            return $this->onError(400, $validator->errors());
        }

        return $this->onError(401, 'Unauthorized Access');
    }

    public function softStatusUser(Request $request, $id): JsonResponse
    {
        if ($this->isAdmin($request->user()) || $this->isManager($request->user())) {
            $validator = Validator::make($request->all(), $this->softStatusValidationRules());
            if ($validator->passes()) {
                $post = User::find($id);
                $post->status = $request->input('status');
                $post->save();

                return $this->onSuccess($post, 'User Updated');
            }
            return $this->onError(400, $validator->errors());
        }

        return $this->onError(401, 'Unauthorized Access');
    }

    public function deleteUser(Request $request, $id): JsonResponse
    {
        if ($this->isAdmin($request->user())) {
            $user = User::find($id);
            if ($user->role !== 1) {
                $user->delete();
                if (!empty($user)) {
                    return $this->onSuccess('', 'User Deleted');
                }
                return $this->onError(404, 'User Not Found');
            }
        }
        return $this->onError(401, 'Unauthorized Access');
    }
    
}
