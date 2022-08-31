<?php

namespace Inovector\Mixpost\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Inovector\Mixpost\Facades\SocialProviderManager;
use Inovector\Mixpost\Model\Account;
use Inovector\Mixpost\Resources\AccountResource;

class AccountsController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();

        $accounts = Account::where('user_id', $user->id)->get();

        return Inertia::render('Accounts', [
            'accounts' => AccountResource::collection($accounts)->resolve()
        ]);
    }

    public function update(Account $account): RedirectResponse
    {
        $result = SocialProviderManager::connect($account->provider)->credentials($account->credentials)->getAccount();

        $account->update([
            'name' => $result['name'],
            'username' => $result['username'],
            'image' => $result['image']
        ]);

        return redirect()->back();
    }

    public function delete(Account $account): RedirectResponse
    {
        $account->delete();

        return redirect()->back();
    }
}
