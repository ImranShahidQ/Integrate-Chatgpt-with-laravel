<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use DB;
use Mail;
use App\Models\User;

#[Title('incu - register')]
class Register extends Component
{
    #[Validate('required')]
    public $name;

    #[Validate('required|email|unique:users,email')]
    public $email;

    #[Validate('required|string|min:8|confirmed')]
    public $password;

    public $password_confirmation;

    public function register()
    {
        $this->validate();

        DB::beginTransaction();

        try{
            $email_verification_token = generate_random_token();
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'email_verification_token' => $email_verification_token,
            ]);

            $user->link = url('/verify-email/' . $email_verification_token);
            $user = $user->toArray();

            Mail::send('emails.email-verify', $user, function ($message) use ($user) {
                $message->to(trime_fn($user['email']))
                    ->subject(env('APP_NAME') . ' | verify account');
            });

            DB::commit();
           
            $message = "Successfully registered. Please check your email to verify your email address.";
            //session()->flash('success', $message);
            return Redirect()->to('login')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            $message = $e->getMessage();
            // session()->flash('error', $message);
            // return;
            return Redirect()->to('login')->with('error', $message);
        }        
    }

    public function render()
    {
        return view('livewire.register');
    }
}
