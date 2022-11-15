<?php

namespace Inovector\Mixpost\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Inovector\Mixpost\Facades\SocialProviderManager;
use Inovector\Mixpost\Models\Account;

class CallbackSocialProviderController extends Controller
{
    public function __invoke(string $providerName): RedirectResponse
    {
        $provider = SocialProviderManager::connect($providerName);

        $credentials = $provider->getAccessToken();

        $provider->setCredentials($credentials);

        $account = $provider->getAccount();

        $user = auth()->user();
        Account::updateOrCreate(
            [
                'provider' => $providerName,
                'provider_id' => $account['id'],
                'user_id' => $user->id
            ],
            [
                'name' => $account['name'],
                'username' => $account['username'],
                'image' => $account['image'],
                'credentials' => $credentials,
                'user_id' => $user->id
            ]
        );

        return redirect()->route('mixpost.accounts.index');
    }
}
