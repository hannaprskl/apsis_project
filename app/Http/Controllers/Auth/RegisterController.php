<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Models\Klien;
use App\Models\Admin;
use Illuminate\Foundation\Auth\RegistersUsers;
use Brian2694\Toastr\Facades\Toastr;
use Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    // use RegisterUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('guest:admin');
    }

    protected function create(array $data)
    {
        return Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admin'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function showAdminRegisterForm()
    {
        return view('auth.register', ['route' => route('admin.register-view'), 'title' => 'Admin']);
    }

    protected function createAdmin(Request $request)
    {
        $this->validator($request->all())->validate();
        $admin = Admin::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        return redirect()->intended('admin');
    }

    public function register()
    {
        return view('auth.register');
    }
    public function storeUser(Request $request)
    {

        $validator = $request->validate([
            'nama'      => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:klien',
            'email'     => 'required|string|email|max:255|unique:klien',
            'password'  => [
                            'required',
                            'string',
                            'min:8',
                            'confirmed',
                            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                            'regex:/^[^\W_]+$/',
                        ],
            'password_confirmation' => 'required',
        ], [
            'nama.required' => 'Nama harus diisi.',
            'nama.max' => 'Panjang nama tidak boleh melebihi :max karakter.',
            'username.required' => 'Username harus diisi.',
            'username.max' => 'Panjang username tidak boleh melebih :max karakter.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Panjang email tidak boleh melebihi :max karakter.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Format password tidak valid.',
            'password.min' => 'Panjang password minimal :min karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.regex' => 'Password harus mengandung setidaknya satu huruf kecil, satu huruf besar, dan satu angka.',
                                'Password tidak boleh mengandung simbol.',
            'password_confirmation.required' => 'Kolom konfirmasi password wajib diisi.',
        ]);



        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'role_name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users',
        //     'password' => ['required', 'confirmed', Password::min(8)
        //             ->mixedCase()
        //             ->letters()
        //             ->numbers()
        //             ->symbols()
        //             ->uncompromised(),
        //     'password_confirmation' => 'required',
        //     ],
        // ]);

        Klien::create([
            'nama'      => $request->nama,
            'username'  => $request->username,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);
        Toastr::success('Akun Berhasil Dibuat :)', 'Sukses!');
        return redirect('login');
    }
}
