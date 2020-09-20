<?php

namespace Jcc\Jwt\Exceptions;

use jcc\Jwt\Support\Token;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Illuminate\Auth\Access\AuthorizationException;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Throwable;

use Jcc\Jwt\Support\Traits\ResponseTrait;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;



class LaravelHandler extends ExceptionHandler
{
    use ResponseTrait;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
//        AuthenticationException::class => ['未授权',401],
//        ModelNotFoundException::class => ['该模型未找到',404],
//        AuthorizationException::class => ['没有此权限',403],
//        ValidationException::class => [],
//        TokenInvalidException::class=>['token不正确',400],
//        NotFoundHttpException::class=>['没有找到该页面',404],
//        MethodNotAllowedHttpException::class=>['访问方式不正确',405],
//        QueryException::class=>['参数错误',401],
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }



    /**
     * 定制 ValidationException 响应
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse|void
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        $this->response->fail($exception->getMessage(), 422, $exception->errors());
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? $this->response->fail($exception->getMessage(),401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    protected function prepareJsonResponse($request, Exception $e)
    {
        $headers = [];
        if(Token::getToken()){//添加刷新的 token 到header中
            $headers = ['Authorization' => Token::getToken()];
        }
        // 要求请求头 header 中包含 /json 或 +json，如：Accept:application/json
        // 或者是 ajax 请求，header 中包含 X-Requested-With：XMLHttpRequest;
        $this->response->fail(
            $e->getMessage(),
            $this->isHttpException($e) ? $e->getStatusCode() : 500,
            $this->convertExceptionToArray($e),
            $this->isHttpException($e) ? array_merge($e->getHeaders(),$headers) : [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }
}
