# Email-Password
Email activation and reset Password

To activate the email :::

1 - You need to set the email and password in .env file to ur mailtrap account if u use it.

2 - 

        php artisan make:auth

3 - add 2 columns in user table 

        $table->string('token')->nullable();
        $table->boolean('active')->default(false);

4 - don't forget to add active and token to ur user moel

5 - 
        php artisan migrate

6 - add this method in Controller\Auth\RegisterController.php

        protected function registered(Request $request, $user)
        {
            $this -> guard() -> logout();
            return redirect('login') -> with('success', 'You registered successfully, Please Check your Email to activate you account.');
        }
    
7 - add this to auth\loginblade.php to mange the massege 

        @if ($errors->has('email'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif

8 - add in create method at RegisterController.php

        'token'->str_random(100)

9 - then go to Controller\Auth\LoginController.php and add this method

        protected function validateLogin(Request $request)
        {
            $this->validate($request, [
                $this->username() =>
                    Rule::exists('users')->where(function ($query){
                        $query->where('active', true);
                    })
            ], [
                'Invaled E-mail or Password  or You need to activate your email.'
            ]);
        }
    
10 - 
        php artisan make:event EventMailActivate 

11 - open it and add 

          public $user;
          public function __construct($user)
          {
              $this->user = $user;
          }
          
12 - add this at RegisterController.php at registered method 
      
        event(new EventMailActivate($user));

13 -  
        php artisan make:listener ListenerMailActivate --event=EventMailActivate

14 - then replace the EventServiceProvider.php protected $listen with

        protected $listen = [
            'App\Events\EventMailActivate' => [
            'App\Listeners\ListenerMailActivate',
            ],
        ];
        
15 - 
        php artisan make:mail SendMail --markdown=mails.mail

16 - add in SendMail.php 

        public $user;
        public function __construct($user)
        {
            $this->user = $user;
        }
        
17 - add this method at ListenerMailActivate.php

        public function handle(EventActivateEmail $event)
        {
            if ($event->user->active){
                return;
            }

            Mail::to($event->user->email) -> send(new SendMail($event->user));
        }
        
18 - now u can check ur mailtrap after registeration and u must find the massege.

19 - to active the button in the massege do that 
  
        php artisan make:controller Auth\ActiveController

20 - and add this method to ActiveController

        public function activate(Request $req){
            $user = User::where('token', $req->token) -> where('email', $req->email) -> firstOrFail();

            $user -> update([
                'active' => true,
                'token' => null
            ]);

            Auth::loginUsingId($user->id);

            return redirect('home') -> with('success', 'Your account acivated successfully, Welcome '.$user->name);
        }
        
21 -  open route.php or web.php and make ur url like that 

        Route::get('activate', 'Auth\ActivateController@activate')->name('activate');

22 - now go to resources\views\mails\mail.blade.php and set ur route like that 

        ['url' => route('activate', [
            'token' => $user -> token,
            'email' => $user -> email
           ])
           ]
           
23 -  when u go to ur mailtrap and click on the button it must open new tap to ur page home 
      but if u have error take the url and change the localhost to ur project url
      
----------------------------------------------------

To reset password::

actually, when u make auth and set ur email at .env it work without any errors 
