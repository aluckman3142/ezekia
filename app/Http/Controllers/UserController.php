<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Models\User;

class UserController extends Controller
{
    public function create()
    {
        return View::make('user.create');
    }

    public function store(StoreUserRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'hourly_rate' => $request->hourly_rate,
            'default_currency' => $request->default_currency,
        ]);

        return Redirect::to('/user/create')->with('message', 'Successfully created user!');
    }

    public function show(User $user)
    {

        $hourly_rate = $user->hourly_rate;

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET','http://api.exchangeratesapi.io/v1/latest?access_key=b74f97ad1516c6d7c387cdd59cb652d4&format=1');
        $data = json_decode($res->getBody()->getContents(), true);


        if ($user->default_currency == 'EUR' && $res){
            $user->euro_rate = $hourly_rate * $data['rates']['EUR'];
            $user->dollar_rate = $hourly_rate * $data['rates']['USD'];
            $user->pound_rate = $hourly_rate * $data['rates']['GBP'];
        } else {
            switch ($user->default_currency) {
                case 'EUR':
                    $user->euro_rate = $hourly_rate;
                    $user->pound_rate = $hourly_rate * 0.9;
                    $user->dollar_rate = $hourly_rate * 1.2;
                    break;
                case "GBP":
                    $user->euro_rate = $hourly_rate * 1.1;
                    $user->pound_rate = $hourly_rate;
                    $user->dollar_rate = $hourly_rate * 1.3;
                    break;
                case 'USD':
                    $user->euro_rate = $hourly_rate * 0.8;
                    $user->pound_rate = $hourly_rate * 0.7;
                    $user->dollar_rate = $hourly_rate;
                    break;
            }
        }

        return View::make('user.show')->with(compact('user'));
    }
}
