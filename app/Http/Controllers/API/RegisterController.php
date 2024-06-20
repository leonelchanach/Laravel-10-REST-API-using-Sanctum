<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\User\RegisterRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\TryCatch;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

     protected $userService;

     public function __construct(UserService $userService)
     {
         $this->userService = $userService;
     }


    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->userService->createUser($request->validated());
            return response()->json(['success'=>true,'data' => ['usuarioNuevo' => $user],'mensage'=>"Registro exitoso"], 201);
        } catch (\Throwable $th) {
           return response()->json(['success' => false,'mensaje'=>'Servidor no disponible'], 500);
        }

    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $loginData = $request->only(['email', 'password']);
        $loginResult = $this->userService->loginUser($loginData);

        if (!$loginResult) {
            return response()->json([
                'success' => false,
                'data' => null,
                'mensaje' => 'Credenciales incorrectas.'
            ], 200); // Código de estado HTTP para "No Autorizado"
        }

        return response()->json([
            'success' => true,
            'data' => [
                'usuario' => $loginResult['user'],
                'token' => $loginResult['token']
            ],
            'mensaje' => 'Inicio de sesión exitoso.'
        ], 200);


    }

    public function datos()
    {
        $usuarios = User::all();

        return $this->sendResponse($usuarios, 'Usuarios retrieved successfully.');
    }
}
