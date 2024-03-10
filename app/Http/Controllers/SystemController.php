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

    public function medications(Request $request): JsonResponse
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
    
    public function customers(Request $request): JsonResponse
    {
        if ($this->isAdmin($request->user()) || $this->isManager($request->user()) || $this->isCashier($request->user())) {
            $post = DB::table('customers')->get();
            return $this->onSuccess($post, 'Customers Retrieved');
        }
        return $this->onError(401, 'Unauthorized Access');
    }

    public function singleCustomer(Request $request, $id): JsonResponse
    {
        if ($this->isAdmin($request->user()) || $this->isManager($request->user()) || $this->isCashier($request->user())) {
            $post = DB::table('customers')->where('id', $id)->first();
            if (!empty($post)) {
                return $this->onSuccess($post, 'Customer Retrieved');
            }
            return $this->onError(404, 'Customer Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }

    public function createCustomer(Request $request): JsonResponse
    {
        if ($this->isAdmin($request->user())) {
            $validator = Validator::make($request->all(), $this->customerValidatedRules());
            if ($validator->passes()) {
                $user = Customer::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                ]);
                return $this->onSuccess($user, 'Customer Created');
            }
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized Access');

    }

    public function updateCustomer(Request $request, $id): JsonResponse
    {
        if ($this->isAdmin($request->user()) || $this->isManager($request->user()) || $this->isCashier($request->user())) {
            $validator = Validator::make($request->all(), $this->customerValidatedRules());
            if ($validator->passes()) {
                $post = Customer::find($id);
                $post->name = $request->input('name');
                $post->email = $request->input('email');
                $post->save();
                return $this->onSuccess($post, 'Customer Updated');
            }
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized Access');
    }

    public function softStatusCustomer(Request $request, $id): JsonResponse
    {
        if ($this->isAdmin($request->user()) || $this->isManager($request->user())) {
            $validator = Validator::make($request->all(), $this->softStatusValidationRules());
            if ($validator->passes()) {
                $post = Customer::find($id);
                $post->status = $request->input('status');
                $post->save();
                return $this->onSuccess($post, 'Customer Updated');
            }
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized Access');
    }

    public function deleteCustomer(Request $request, $id): JsonResponse
    {
        if ($this->isAdmin($request->user())) {
            $user = Customer::find($id);
            $user->delete();
            if (!empty($user)) {
                return $this->onSuccess('', 'Customer Deleted');
            }
            return $this->onError(404, 'Customer Not Found');
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
    
    public function logout(Request $request): JsonResponse
    {
        if ($this->isAdmin($request->user()) || $this->isManager($request->user()) || $this->isCashier($request->user())) {
            if($request->user()->tokens()->delete()){
                return $this->onSuccess('', 'User Logout');
            }
            return $this->onError(400, 'Logout faill');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
}
