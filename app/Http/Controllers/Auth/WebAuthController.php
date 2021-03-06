<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;


class WebAuthController extends BaseController
{

    /**
     * Redirect the user to the OAuth Provider.
     * @param $provider
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)
            ->scopes(['public_content'])->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     * @param $provider
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        $authUser = $this->findOrCreateUser($user, $provider);
//        Auth::login($authUser, true);

        return redirect()->away(env('FRONTEND_APP_URL') . '/callback?user_id=' . $authUser->getUuidKey());
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
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }

        $roles = \App\Models\Role::all();

        return User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'provider' => $provider,
            'provider_id' => $user->id,
            'avatar' => $user->avatar,
            'nickname' => $user->nickname,
            'access_token' => $user->token,
            'primary_role' => $roles->where('name', \App\Models\Role::ROLE_SOCIALITE)->first()->role_id,
        ]);
    }
}
