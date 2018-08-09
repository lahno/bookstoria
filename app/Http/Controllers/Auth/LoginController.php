<?php

namespace App\Http\Controllers\Auth;

use App\Category;
use App\Collection;
use App\FooterMenu;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Socialite;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/mybooks';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($request->ajax()){
            return response()->json([
                'auth' => auth()->check(),
                'user' => $user,
//                'intended' => $this->redirectPath(),
            ]);
        }
    }

    public function showLoginForm()
    {
        $cats = Category::all();
        $cols = Collection::all();
        $foot = FooterMenu::all();
        return view('auth.login',[
            'cats'=>$cats,
            'cols'=>$cols,
            'foot'=>$foot
        ]);
    }
    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect($this->redirectTo);
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        if($provider != 'vkontakte'){
            $authUser = User::where('email', $user->email)->first();
            if ($authUser) {
                return $authUser;
            }

            $users = User::create([
                'name'     => $user->name,
                'email'    => $user->email,
                'password' => Hash::make(str_random(8)),
                'provider' => $provider,
                'provider_id' => $user->id
            ]);
            $users->roles()->attach(3);
            return $users;
        }elseif($provider == 'vkontakte')
        {
            $authUser = User::where('provider_id', $user->id)->first();
            if ($authUser) {
                return $authUser;
            }
            if(isset($user) && !empty($user)){
                $users = new User();
                if(isset($user->name) && !empty($user->name))
                {
                    $users->name = $user->name;
                }
                if(isset($user->email) && !empty($user->email))
                {
                    $users->email = $user->email;
                }else{
                    $users->email = $user->name;
                }
                $users->password = Hash::make(str_random(8));
                $users->provider = $provider;
                $users->provider_id = $user->id;
                $users->save();

                $users->roles()->attach(3);
                return $users;
            }

        }

    }
}
