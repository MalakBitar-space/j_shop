<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    use ApiResponseTrait;

    /**
     * عرض جميع التقييمات
     */
    public function index()
    {
        $evaluations = Evaluation::all();
        return $this->successResponse($evaluations, "تم جلب جميع التقييمات بنجاح!");
    }

    /**
     * عرض تقييم معين
     */
    public function show($id)
    {
        $evaluation = Evaluation::find($id);

        if (!$evaluation) {
            return $this->errorResponse("التقييم غير موجود!", 404);
        }

        return $this->successResponse($evaluation, "تم جلب التقييم بنجاح!");
    }

    /**
     * إنشاء تقييم جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $evaluation = Evaluation::create($request->only(['name', 'rating', 'comment']));

        return $this->successResponse($evaluation, "تم إنشاء التقييم بنجاح!", 201);
    }

    /**
     * تعديل تقييم موجود
     */
    public function update(Request $request, $id)
    {
        $evaluation = Evaluation::find($id);

        if (!$evaluation) {
            return $this->errorResponse("التقييم غير موجود!", 404);
        }

        $evaluation->update($request->only(['name', 'rating', 'comment']));

        return $this->successResponse($evaluation, "تم تحديث التقييم بنجاح!");
    }

    /**
     * حذف تقييم
     */
    public function destroy($id)
    {
        $evaluation = Evaluation::find($id);

        if (!$evaluation) {
            return $this->errorResponse("التقييم غير موجود!", 404);
        }

        $evaluation->delete();

        return $this->successResponse(null, "تم حذف التقييم بنجاح!");
    }
}
