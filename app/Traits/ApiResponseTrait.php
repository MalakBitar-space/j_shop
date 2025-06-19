<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * استجابة ناجحة
     * 
     * @param mixed $data البيانات المرجعة
     * @param string $message رسالة الاستجابة
     * @param int $statusCode كود الحالة HTTP
     * @return JsonResponse
     */
    public function successResponse($data = null, $message = "success", $statusCode = 200): JsonResponse
    {
       $response = [
    'status' => 'success',
    'message' => $message,
      ];

    if (!is_null($data)){
    $response['data'] = $data;
}

return response()->json($response, $statusCode);

    }

    /**
     * استجابة فشل
     * 
     * @param string $message رسالة الخطأ
     * @param int $statusCode كود الحالة HTTP
     * @param mixed|null $errors تفاصيل الخطأ إن وجدت
     * @return JsonResponse
     */
    public function errorResponse($message, $statusCode = 400, $errors = null): JsonResponse
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}
