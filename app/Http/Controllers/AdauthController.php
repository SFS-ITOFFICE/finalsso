<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\Laravel\Facades\Adldap;
use Auth;
use Hash;
use App\Models\User;
use App\Models\Staff;
use App\Models\Student;

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
        $user = Adldap::search()->users()->find($username);
dd($user);

        // Searching for a user.
        $search = Adldap::search()->where('sAMAccountName', '=', $username)->get();
dd($search);

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
        //dd($request->input('loginid'));

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

        $loginid = $request->input('loginid');
        $loginpw = $request->input('loginpw');
        if (Adldap::auth()->attempt($loginid, $loginpw)) { // LDAP 에 아이디/암호로 확인한다.
            $password = Hash::make($loginpw);
            $staffid = 0;
            $name = 'Unknown';

            $_STAFF = Staff::where('accountname', $loginid)->first();
            if ($_STAFF) {
                $staffid = $_STAFF->staffid;
                $name = $_STAFF->fullname;
            } else {
                $_STUDENT = Student::where('st_accountname', $loginid)->first();
                if ($_STUDENT) {
                    $staffid = $_STUDENT->studentid;
                    $name = $_STUDENT->fullname;
                }
            }

            $_USER = User::where('username', $loginid)->first();
            if (! $_USER) {
                $_USER = new User();
                $_USER->username = $loginid;
            }
            $_USER->email = $loginid . "@seoulforeign.org";
            $_USER->name = ($_USER->name) ?: $name;
            $_USER->staffid = $staffid;
            $_USER->password = $password;
            $_USER->save();

            // Authenticating against your LDAP server.
            if (auth()->attempt(['username' => $loginid, 'password' => $loginpw])) {
                $returl = "https://www.seoulforeign.org/";
                return redirect()->secure($returl);
            } else {
                return back()->withInput()->withErrors(['message' => "Invalid ID or password. Please try again your SFS ID."]);
            }
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
                $returl = "https://www.seoulforeign.org/";
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
