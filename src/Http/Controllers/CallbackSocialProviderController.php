<?php

namespace Inovector\Mixpost\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inovector\Mixpost\Actions\UpdateOrCreateAccount;
use Inovector\Mixpost\Facades\SocialProviderManager;

class CallbackSocialProviderController extends Controller
{
    public function __invoke(Request $request, UpdateOrCreateAccount $updateOrCreateAccount, string $providerName): RedirectResponse
    {
        $provider = SocialProviderManager::connect($providerName);

        if (!$provider->isOnlyUserAccount()) {
            return redirect()->route('mixpost.accounts.entities.index', ['provider' => $providerName])
                ->with('mixpost_callback_response', $provider->getCallbackResponse());
        }

        $accessToken = $provider->requestAccessToken();

        $provider->setAccessToken($accessToken);

        $account = $provider->getAccount();

        $updateOrCreateAccount($providerName, $account, $accessToken);

        return redirect()->route('mixpost.accounts.index');
    }
}
