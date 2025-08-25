<?php
namespace App\Http\Controllers;

use App\Models\ServiceProvider;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceProviderController extends Controller
{
    use ApiResponseTrait;

    /**
     * عرض قائمة جميع مقدمي الخدمات
     */
    public function index()
    {
        $providers = ServiceProvider::with('user')->get()->map(function ($provider) {
            return [
                'id' => $provider->id,
                'user_id' => $provider->user_id,
                'user_name' => $provider->user ? $provider->user->name : null,
                'service_id' => $provider->service_id,
                'specialization' => $provider->specialization,
                'professional_desc' => $provider->professional_desc,
                'years_of_experience' => $provider->years_of_experience,
                'min_price' => $provider->min_price,
                'phone_number' => $provider->phone_number,
                'profile_img' => $provider->profile_img,
                'identity_img' => $provider->identity_img,
                'address' => $provider->address,
                'cover_img' => $provider->cover_img,
                'created_at' => $provider->created_at,
                'updated_at' => $provider->updated_at,
            ];
        });
        return $this->successResponse($providers, "تم جلب جميع مقدمي الخدمات بنجاح!");
    }

    /**
     * عرض مقدم خدمة معين
     */
    public function show($id)
    {
        $provider = ServiceProvider::with('user')->find($id);

        if (!$provider) {
            return $this->errorResponse("مقدم الخدمة غير موجود!", 404);
        }

        return $this->successResponse([
            'id' => $provider->id,
            'user_id' => $provider->user_id,
            'user_name' => $provider->user ? $provider->user->name : null,
            'service_id' => $provider->service_id,
            'specialization' => $provider->specialization,
            'professional_desc' => $provider->professional_desc,
            'years_of_experience' => $provider->years_of_experience,
            'min_price' => $provider->min_price,
            'phone_number' => $provider->phone_number,
            'profile_img' => $provider->profile_img,
            'identity_img' => $provider->identity_img,
            'address' => $provider->address,
            'cover_img' => $provider->cover_img,
            'created_at' => $provider->created_at,
            'updated_at' => $provider->updated_at,
        ], "تم جلب بيانات مقدم الخدمة بنجاح!");
    }

    /**
     * إنشاء مقدم خدمة جديد
     */
    public function store(Request $request)
    {
        $user = $request->user(); // Get user from token

        $validatedData = $request->validate([
            'service_id' => 'required|exists:services,id',
            'specialization' => 'required|string',
            'professional_desc' => 'nullable|string',
            'years_of_experience' => 'nullable|integer',
            'min_price' => 'nullable|numeric',
            'phone_number' => 'nullable|string',
            'profile_img' => 'nullable|string',
            'identity_img' => 'nullable|string',
            'address' => 'nullable|string',
            'cover_img' => 'nullable|string',
        ]);

        $provider = ServiceProvider::create(array_merge(
            $validatedData,
            ['user_id' => $user->id] // Use authenticated user's ID
        ));

        return $this->successResponse([
            'id' => $provider->id,
            'user_id' => $provider->user_id,
            'user_name' => $user->name,
            'service_id' => $provider->service_id,
            'specialization' => $provider->specialization,
            'professional_desc' => $provider->professional_desc,
            'years_of_experience' => $provider->years_of_experience,
            'min_price' => $provider->min_price,
            'phone_number' => $provider->phone_number,
            'profile_img' => $provider->profile_img,
            'identity_img' => $provider->identity_img,
            'address' => $provider->address,
            'cover_img' => $provider->cover_img,
            'created_at' => $provider->created_at,
            'updated_at' => $provider->updated_at,
        ], "تم إنشاء مقدم الخدمة بنجاح!", 201);
    }

    /**
     * تحديث بيانات مقدم خدمة
     */
    public function update(Request $request, $id)
    {
        $provider = ServiceProvider::find($id);

        if (!$provider) {
            return $this->errorResponse("مقدم الخدمة غير موجود!", 404);
        }

        $provider->update($request->only([
            'specialization', 'professional_desc', 'years_of_experience', 'min_price',
            'phone_number', 'profile_img', 'identity_img', 'address', 'cover_img'
        ]));

        $provider->load('user');

        return $this->successResponse([
            'id' => $provider->id,
            'user_id' => $provider->user_id,
            'user_name' => $provider->user ? $provider->user->name : null,
            'service_id' => $provider->service_id,
            'specialization' => $provider->specialization,
            'professional_desc' => $provider->professional_desc,
            'years_of_experience' => $provider->years_of_experience,
            'min_price' => $provider->min_price,
            'phone_number' => $provider->phone_number,
            'profile_img' => $provider->profile_img,
            'identity_img' => $provider->identity_img,
            'address' => $provider->address,
            'cover_img' => $provider->cover_img,
            'created_at' => $provider->created_at,
            'updated_at' => $provider->updated_at,
        ], "تم تحديث بيانات مقدم الخدمة بنجاح!");
    }

    /**
     * حذف مقدم خدمة
     */
    public function destroy($id)
    {
        $provider = ServiceProvider::find($id);

        if (!$provider) {
            return $this->errorResponse("مقدم الخدمة غير موجود!", 404);
        }

        $provider->delete();

        return $this->successResponse(null, "تم حذف مقدم الخدمة بنجاح!");
    }

    /**
     * الحصول على مقدمي الخدمة حسب الخدمة
     */
    public function getByService($service_id)
    {
        $providers = ServiceProvider::with('user')->where('service_id', $service_id)->get()->map(function ($provider) {
            return [
                'id' => $provider->id,
                'user_id' => $provider->user_id,
                'user_name' => $provider->user ? $provider->user->name : null,
                'service_id' => $provider->service_id,
                'specialization' => $provider->specialization,
                'professional_desc' => $provider->professional_desc,
                'years_of_experience' => $provider->years_of_experience,
                'min_price' => $provider->min_price,
                'phone_number' => $provider->phone_number,
                'profile_img' => $provider->profile_img,
                'identity_img' => $provider->identity_img,
                'address' => $provider->address,
                'cover_img' => $provider->cover_img,
                'created_at' => $provider->created_at,
                'updated_at' => $provider->updated_at,
            ];
        });
        return $this->successResponse($providers, "تم جلب مقدمي الخدمة حسب الخدمة بنجاح!");
    }
}
