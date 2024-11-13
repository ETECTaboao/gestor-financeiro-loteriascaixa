<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // Criação do usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Autentica o usuário automaticamente após o registro
        Auth::login($user);

        // Redireciona para as transações após o registro e login bem-sucedidos
        return redirect()->route('transactions.index');
    }


    public function login(LoginRequest $request)
    {
        // Tentativa de autenticação
        if (Auth::attempt($request->only('email', 'password'))) {
            // Redireciona para as transações após o login bem-sucedido
            return redirect()->route('transactions.index');
        }

        // Retorna à página de login com uma mensagem de erro se as credenciais forem inválidas
        return back()->withErrors(['email' => 'Credenciais inválidas'])->withInput($request->only('email'));
    }

    public function logout()
    {
        // Desloga o usuário e limpa a sessão
        Auth::logout();

        // Exibe uma mensagem de sucesso ao fazer logout
        return redirect()->route('login')->with('status', 'Você foi desconectado com sucesso!');
    }
}
