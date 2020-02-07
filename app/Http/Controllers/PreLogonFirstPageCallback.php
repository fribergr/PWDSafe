<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PreLogonFirstPageCallback extends Controller
{
    public function index() {
        return redirect()->to('/groups/' . Auth::user()->primarygroup);
    }

    /**
     * @brief Authenticate user by email and password
     */
    public function post()
    {
        if (!isset($_POST['inputEmail'])) {
            return;
        }

        $graphics = new Graphics();
        $session = new Session();

        $res = $session->authenticate(DB::getInstance(), $_POST['inputEmail'], $_POST['inputPassword']);
        if ($res) {
            $_SESSION['id'] = $res['id'];
            $_SESSION['user'] = $res['email'];
            $_SESSION['pass'] = $res['password'];
            $_SESSION['pubkey'] = $res['pubkey'];
            $_SESSION['privkey'] = $res['privkey'];
            $_SESSION['primarygroup'] = $res['primarygroup'];
            $_SESSION['ldap'] = $res['ldap'];

            $ref = isset($_GET['ref']) ? urldecode($_GET['ref']) : "/";
            header("Location: $ref");
        } else {
            $graphics->showLogin(true);
        }
    }
}
