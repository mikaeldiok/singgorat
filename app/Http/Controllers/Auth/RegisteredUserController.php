<?php

namespace App\Http\Controllers\Auth;

use App\Events\Frontend\UserRegistered;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Userprofile;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Flash;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param \App\Http\Requests\LoginRequest $request
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name'=> 'required|min:3|max:191',
            'last_name' => 'required|min:3|max:191',
            'email'     => 'required|email|regex:/(.+)@(.+)\.(.+)/i|max:191|unique:users',
            'password'  => 'required|confirmed|min:4',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'reporter_type' => $request->reporter_type,
            'name' => $request->first_name . " " . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $all_roles_in_database = Role::all()->pluck('name');

        // \Log::debug("startt");
        $user->assignRole(['user']);
        // \Log::debug($hasil);

        // username
        $username = config('app.initial_username')+$user->id;
        $user->username = $username;
        $user->save();

        \Log::debug(json_encode($user->roles()));
        $userprofile = new Userprofile();
        $userprofile->user_id = $user->id;
        $userprofile->name = $user->name;
        $userprofile->address = $user->address;
        $userprofile->first_name = $user->first_name;
        $userprofile->last_name = $user->last_name;
        $userprofile->username = $user->username;
        $userprofile->email = $user->email;
        $userprofile->mobile = $user->mobile;
        $userprofile->gender = $user->gender;
        $userprofile->date_of_birth = $user->date_of_birth;
        $userprofile->avatar = $user->avatar;
        $userprofile->status = ($user->status > 0) ? $user->status : 0;
        $userprofile->save();

        Auth::login($user);

        event(new UserRegistered($user));
        event(new Registered($user));
        
        Flash::success('<i class="fas fa-check"></i>  Selamat anda sudah terdaftar, Kami telah mengirimkan konfirmasi ke alamat email anda!')->important();

        return redirect('login');
    }
}
