<?php

namespace Mkamel\StarterCoreKit\database\seeders;

use Illuminate\Database\Seeder;
use Mkamel\StarterCoreKit\app\Models\ExceptionModel;
use Symfony\Component\HttpFoundation\Response;

class ExceptionRecordSeeder extends Seeder
{
    public function run()
    {
        $exceptions = [
            \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class => [
                'status' => Response::HTTP_NOT_FOUND,
                'message' => [
                    'ar' => 'هذا الرابط غير موجود',
                    'en' => 'This Route Not Found',
                ],
            ],
            \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class => [
                'status' => Response::HTTP_METHOD_NOT_ALLOWED,
                'message' => [
                    'ar' => 'الطريقة غير مسموح بها',
                    'en' => 'This Method Not Allowed',
                ],
            ],
            \Illuminate\Auth\Access\AuthorizationException::class => [
                'status' => Response::HTTP_FORBIDDEN,
                'message' => [
                    'ar' => 'غير مصرح به',
                    'en' => 'this user is not authorized to perform this action',
                ],
            ],
            \Illuminate\Auth\AuthenticationException::class => [
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => [
                    'ar' => 'مستخدم غير مُصادق عليه',
                    'en' => 'this is not authenticated to perform this action',
                ],
            ],
            \Illuminate\Http\Exceptions\ThrottleRequestsException::class => [
                'status' => Response::HTTP_TOO_MANY_REQUESTS,
                'message' => [
                    'ar' => '',
                    'en' => 'too_many_requests',
                ],
            ],
            \Illuminate\Database\Eloquent\RelationNotFoundException::class => [
                'status' => Response::HTTP_NOT_FOUND,
                'message' => [
                    'ar' => 'طلبات كثيرة جدًا على هذا الرابط',
                    'en' => 'relation_not_found',
                ],
            ],
            \TypeError::class => [
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => [
                    'ar' => '',
                    'en' => 'this type of data is invalid',
                ],
            ],
            \ValueError::class => [
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => [
                    'ar' => 'هذا النوع من البيانات غير صالح',
                    'en' => 'value_error',
                ],
            ],
            \Illuminate\Validation\ValidationException::class => [
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => [
                    'ar' => 'خطأ في التحقق من صحة البيانات',
                    'en' => 'Validation Error',
                ],
            ],
        ];

        $repo = new ExceptionModel();

        foreach ($exceptions as $exceptionClass => $exceptionData) {
            ExceptionModel::updateOrCreate(
                [
                    'exception_class' => $exceptionClass,
                ],
                [
                    'exception_class' => $exceptionClass,
                    'message' => $exceptionData['message'],
                ]
            );
        }
    }
}
