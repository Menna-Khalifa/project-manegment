<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, \Throwable $exception)
    {
        // إذا كان الخطأ هو 404 (Not Found)
        if ($exception instanceof NotFoundHttpException) {
            return response()->view('dashboard.404', [], 404); // توجيه إلى صفحة مخصصة
        }

        // إذا كان الخطأ هو 403 (Forbidden)
        if ($exception instanceof HttpException && $exception->getStatusCode() == 403) {
            return response()->view('dashboard.403', [], 403); // توجيه إلى صفحة مخصصة
        }

        // إذا كان APP_DEBUG=false وأي خطأ داخلي يحدث
        if (!config('app.debug')) {
            // إعادة توجيه إلى صفحة الخطأ المخصصة
            return response()->view('dashboard.500', [], 500);
        }

        return parent::render($request, $exception);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // إضافة معالج للأخطاء
        // $this->renderable(function (Throwable $e, $request) {
        //     // تحقق إذا كان الـ debug معطل
        //     if (!config('app.debug')) {
        //         // توجيه جميع الأخطاء إلى مسار معين
        //         return view('dashboard.500');
        //     }

        //     // إذا كان الـ debug مفعل، اترك Laravel يعرض الخطأ بشكل طبيعي
        //     return null;
        // });
    }
}
