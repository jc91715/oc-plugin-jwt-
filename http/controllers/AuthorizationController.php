<?php namespace Jcc\Jwt\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use October\Rain\Exception\ValidationException;
use Illuminate\Support\Facades\Validator;
use Jcc\Jwt\Services\Enums\ResponseEnumsService;
use RLuders\JWTAuth\Classes\JWTAuth;

class AuthorizationController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login']]);
    }

    public function logout()
    {
        auth('api')->logout();
        return $this->response->noContent();
    }

    public function login(Request $request)
    {
//        dd(auth('api'));

        $this->validate($request, [
            'email'    => 'filled|email',
            'username' => ['required_without:email'],
            'password' => 'required',
        ]);
        $credentials = request(['username', 'email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            $this->response->fail('', ResponseEnumsService::CLIENT_VALIDATION_ERROR);
        }

        return $this->respondWithToken($token);
    }

    public function show()
    {
        $user = auth('api')->user();
        return $this->response->success($user);
    }

    protected function respondWithToken($token)
    {
        return $this->response->success(
            [
                'access_token' => $token,
                'token_type'   => 'bearer',
                'expires_in'   => auth('api')->factory()->getTTL() * 60,
            ],
            '',
            ResponseEnumsService::SERVICE_LOGIN_SUCCESS
        );
    }


}
