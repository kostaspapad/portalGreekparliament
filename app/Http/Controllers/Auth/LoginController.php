<?php
namespace App\Http\Controllers\Auth;
 
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Contracts\Auth\Guard;
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
     * Auth guard
     *
     * @var
     */
    protected $auth;
     
     /**
     * lockoutTime
     *
     * @var
     */
    protected $lockoutTime;
     
    /**
     * maxLoginAttempts
     *
     * @var
     */
    protected $maxLoginAttempts;
 
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
 
    /**
     * Create a new controller instance.
     *
     * LoginController constructor.
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->auth = $auth;
        $this->lockoutTime  = 1;    //lockout for 1 minute (value is in minutes)
        $this->maxLoginAttempts = 4;    //lockout after 5 attempts
    }
     
    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), $this->maxLoginAttempts, $this->lockoutTime
        );
    }
 
    public function login(Request $request)
    {
        // 1) we validate the request
        $this->validateLogin($request);
         
        // 2) Check if the user has surpassed their alloed maximum of login attempts
        // We'll key this by the email and the IP address of the client making 
        // these requests into this application. 
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
 
            return $this->sendLockoutResponse($request);
        }
         
        // 3) now we can attempte the login
        $email      = $request->get('email');
        $password   = $request->get('password');
        $remember   = $request->get('remember');
 
        if ($this->auth->attempt([
            'email'     => $email,
            'password'  => $password,
            // 'activated'  => 1,
        ], $remember == 1 ? true : false)) {
            // SUCCESS: If the login attempt was successful we redirect to the dashboard. but first, we 
            // clear the login attempts session
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
 
            return redirect()->route('home');
 
        }
        else {
 
            // FAIL: If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            $this->incrementLoginAttempts($request);
             
            return redirect()->back()
                ->with('message','Incorrect email or password')
                ->with('status', 'danger')
                ->withInput();
        }
 
    }
     
    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
 
        $request->session()->flush();
 
        $request->session()->regenerate();
 
        return redirect('/')
            ->with('message','You are now signed out')
            ->with('status', 'success')
            ->withInput();
    }
     
    /**
     * Get the login email to be used by the controller.
     *
     * @return string
     */
    public function email()
    {
        return 'email';
    }
     
    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->email() => 'required', 'password' => 'required',
        ]);
    }
}