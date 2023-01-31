<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\ApiResponseCode;
use App\Http\Requests\Api\v1\Auth\AuthLoginRequest;
use App\Http\Requests\Api\v1\Auth\AuthSignupRequest;
use App\Http\Resources\Api\v1\User\UserResource;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseController
{
    /**
     * @OA\SecurityScheme(
     *     securityScheme="passport",
     *     type="http",
     *     scheme="bearer",
     * )
     */
    public function __construct(protected AuthService $service, protected UserService $userService)
    {
        parent::__construct();
    }

    /**
     * signup.
     *
     * @OA\Post(
     *     path="/api/v1/signup",
     *     summary="Signup",
     *     description="Signup",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *                 required={"name", "email", "password", "password_confirmation"},
     *
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     format="string",
     *                     description="name",
     *                     example="test001",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="string",
     *                     description="email",
     *                     example="test001@test.com",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     format="password",
     *                     description="password",
     *                     example="123456",
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string",
     *                     format="password",
     *                     description="password confirmation",
     *                     example="123456",
     *                 ),
     *             ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Successfully.",
     *         content={
     *
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *
     *                 @OA\Schema(
     *
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         format="string",
     *                         description="message",
     *                         example="Successfully created user!",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     *
     *     @OA\Response(
     *         response="400",
     *         description="Failed.",
     *         content={
     *
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *
     *                 @OA\Schema(
     *
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         format="string",
     *                         description="message",
     *                         example="Failed to create user!",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     *
     *     @OA\Response(
     *         response="422",
     *         description="Validation error.",
     *         content={
     *
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *
     *                 @OA\Schema(
     *
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         format="string",
     *                         description="message",
     *                         example="The email field is required.",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * )
     *
     * @param AuthSignupRequest $request
     */
    public function signup(AuthSignupRequest $request)
    {
        $user = $this->userService->create($request->validated());
        if (empty($user)) {
            return $this->responseFail(message: 'Failed to create user!');
        }

        return $this->responseSuccess(message: 'User created successfully!');
    }

    /**
     * login.
     *
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Login",
     *     description="Login",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *                 required={"email", "password"},
     *
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="string",
     *                     description="email",
     *                     example="test001@test.com",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     format="password",
     *                     description="password",
     *                     example="123456",
     *                 ),
     *             ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Successfully.",
     *         content={
     *
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *
     *                 @OA\Schema(
     *
     *                     @OA\Property(property="id", type="integer" ,format="int64", example=1),
     *                     @OA\Property(property="name", type="string", format="string", example="test001"),
     *                     @OA\Property(property="email", type="string", format="string", example="test001@test.com"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2020-07-31 23:54:28"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2020-07-31 23:54:28"),
     *                     @OA\Property(property="token", type="string", format="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJ..."),
     *                 ),
     *             ),
     *         },
     *     ),
     *
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized.",
     *         content={
     *
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *
     *                 @OA\Schema(
     *
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         format="string",
     *                         description="message",
     *                         example="Unauthorized",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     *
     *     @OA\Response(
     *         response="422",
     *         description="Validation error.",
     *         content={
     *
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *
     *                 @OA\Schema(
     *
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         format="string",
     *                         description="message",
     *                         example="The email field is required.",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * )
     *
     * @param AuthLoginRequest $request
     */
    public function login(AuthLoginRequest $request)
    {
        $user = $this->service->attempt($request->validated());
        if (empty($user)) {
            return $this->responseFail(
                code: ApiResponseCode::ERROR_UNAUTHORIZED->value,
                message: 'Unauthorized',
                httpStatusCode: Response::HTTP_UNAUTHORIZED
            );
        }

        return $this->responseSuccess(array_merge([
            'user' => UserResource::make($user),
        ], [
            'token' => $user->token(),
        ]));
    }

    /**
     * Logout user (Revoke the token).
     *
     * @OA\Get(
     *     path="/api/v1/logout",
     *     summary="Logout",
     *     description="Logout",
     *     tags={"Auth"},
     *     security={
     *         {
     *             "passport": {},
     *         },
     *     },
     *
     *     @OA\Response(
     *         response="200",
     *         description="Successfully.",
     *         content={
     *
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *
     *                 @OA\Schema(
     *
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         format="string",
     *                         description="message",
     *                         example="Successfully logged out",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     *
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized.",
     *         content={
     *
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *
     *                 @OA\Schema(
     *
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         format="string",
     *                         description="message",
     *                         example="Unauthorized",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * )
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $request->user()->token()->delete();

        return $this->responseSuccess(message: 'Successfully logged out');
    }
}
