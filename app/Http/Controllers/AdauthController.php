<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\Laravel\Facades\Adldap;
use Auth;

class AdauthController extends Controller
{
    function test()
    {
        //dd('ok');

        if (auth()->check()) {
            dump('login');
        } else {
            dump('not login');
        }

        //$username="sfs.hrdbadmin";
        //$password="djEmals03!4";

        $username = "dongwook.koh";
        $password = "rhEhd03!4";

        // Finding a user.
        $user = Adldap::search()->users()->find('dongwook');
        $user = Adldap::search()->users()->find('Koh, Dongwook (IT)');
//dd($user);

        // Searching for a user.
        $search = Adldap::search()->where('cn', '=', 'Koh, Dongwook (IT)')->get();
//dd($search);

        // Authenticating against your LDAP server.
        if (Adldap::auth()->attempt($username, $password)) {
            dump('passed1');
        } else {
            dump('not passed1');
        }

        // Authenticating against your LDAP server.
        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            // Passed!
            $user = \Auth::user();

            Auth::loginUsingId($user->id);

            dump($user);

        } else {
            dump('not passed2');
        }

        // Running an operation under a different connection:
        //$users = Adldap::getProvider('other-connection')->search()->users()->get();

        // Creating a user.
        //$user = Adldap::make()->user([
        //    'cn' => 'John Doe',
        //]);
//        $user->save();
        //return redirect('/');
    }

    public function create(Request $request, $from = null, $returl = null)
    {
        if (!$returl) {
            $returl = $request->input('returl');
        }

        if (!$from) {
            $from = $request->input('from');
        }
        return view('adauth.adminlte_create')->with(
            compact('returl', 'from')
        );
    }

    public function store(Request $request)
    {
        //dd($request->all());

        $this->validate($request, [
            'loginid' => 'required',
            'loginpw' => 'required',
        ]);

        // Authenticating against your LDAP server.
        if (auth()->attempt(['username' => $request->input('loginid'), 'password' => $request->input('loginpw')])) {
//            $user = auth()->user();
//            \Log::alert($user);

            $from = $request->input('from');
            $returl = $request->input('returl');
            if ($from) {
                if ($from == 'hrdb') {
                    $returl = "https://hrdb.sfs.or.kr" . urldecode($returl);
                } elseif ($from == 'webservice') {
                    $returl = "https://webservice.sfs.or.kr" . urldecode($returl);
                } elseif ($from == 'students') {
                    $returl = "https://students.sfs.or.kr" . urldecode($returl);
                }
            } else {
                if ($returl) {
                    try {
                        $returl = decrypt($returl);
                    } catch (DecryptException $e) {
                        $returl = null;
                    }
                } else {
                    $returl = redirect()->intended('/', 301, [], true)->getTargetUrl();
                    $returl = str_replace('http://', 'https://', $returl);
                }
            }
            if (! $returl) $returl = "https://seoulforeignorg.finalsite.com/";

            dd($returl);

            return redirect()->secure($returl);
        } else {
            return back()->withInput()->withErrors(['message' => "Invalid ID or password. Please try again your SFS ID."]);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, $returl = null)
    {
//        dd($request->all());

        auth()->logout();
        setcookie('hrdbAuth', '', time() - 86400, "/", ".sfs.or.kr");
        setcookie('hrdbAN', '', time() - 86400, "/", ".sfs.or.kr");

        $from = $request->input('from');
        if ($from) {
            if ($from == 'hrdb') {
                $returl = "https://hrdb.sfs.or.kr/";
            } elseif ($from == 'webservice') {
                $returl = "https://webservice.sfs.or.kr/";
            } elseif ($from == 'students') {
                $returl = "https://students.sfs.or.kr/";
            } else {
                $returl = "https://seoulforeignorg.finalsite.com/";
            }
            return redirect()->secure($returl);
        }

        if ($returl) {
            try {
                $returl = decrypt($returl);
            } catch (DecryptException $e) {
                $returl = null;
            }
        } else {
            $returl = null;
        }

        if ($returl) { // 외부사이트에 넘어오는것을 대비해서
            return redirect($returl);
        } else {
            return redirect('/');
        }
    }
}
