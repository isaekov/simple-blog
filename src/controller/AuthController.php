<?php


namespace src\controller;


use Delight\Auth\AttemptCancelledException;
use Delight\Auth\AuthError;
use Delight\Auth\EmailNotVerifiedException;
use Delight\Auth\InvalidEmailException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;
use System\controller\Controller;
use System\tools\Tools;

class AuthController extends Controller
{

    public function registration()
    {
        if (!empty($_POST["email"]) && !empty($_POST["password"])) {
            try {
                $userId = $this -> auth -> register($_POST['email'], $_POST['password']);
                $this -> auth -> login($_POST['email'], $_POST['password']);

                echo 'We have signed up a new user with the ID ' . $userId;
            } catch (\Delight\Auth\InvalidEmailException $e) {
                die('Invalid email address');
            } catch (\Delight\Auth\InvalidPasswordException $e) {
                die('Invalid password');
            } catch (\Delight\Auth\UserAlreadyExistsException $e) {
                die('User already exists');
            } catch (\Delight\Auth\TooManyRequestsException $e) {
                die('Too many requests');
            } catch (AuthError $e) {
            } catch (AttemptCancelledException $e) {
            } catch (EmailNotVerifiedException $e) {
            }
            Tools::redirect("/admin/create");
        }

       echo $this->getRender();
    }

    public function login()
    {

        if (!empty($_POST["email"]) && !empty($_POST["password"])) {
            if (isset($_POST['remember'])) {
                // keep logged in for one year
                $rememberDuration = (int)(60 * 60 * 24 * 365.25);
            } else {
                // do not keep logged in after session ends
                $rememberDuration = null;
            }

            try {
                $this -> auth -> login($_POST['email'], $_POST['password'], $rememberDuration);
            } catch (AttemptCancelledException $e) {
                echo "1";
            } catch (AuthError $e) {
                echo "2";
            } catch (EmailNotVerifiedException $e) {
                echo "3";
            } catch (InvalidEmailException $e) {
                echo "4";
            } catch (InvalidPasswordException $e) {
                echo "5";
            } catch (TooManyRequestsException $e) {
            }
        }

        if ($this->auth->check()) {
            Tools::redirect("/admin/creator");

        }
       echo $this->getRender();
    }

    public function logout()
    {
        $this->auth->logOut();
        if (!$this->auth->check()) {
            Tools::redirect("/");

        }

    }
}