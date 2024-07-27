<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\User;
use App\Models\UserPasswordReset;
use DB;
use Mail;

#[Title('incu - forgot password')]
class ForgotPassword extends Component
{
    #[Validate('required|email')]
    public $email;

    public function forgotPassword()
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();
        if (!$user) {
            $message = "Email address not exist";
            session()->flash('error', $message);
            return $this->redirectRoute('forgot-password', navigate: true);
        }

        DB::beginTransaction();
        try {
            $link = generate_random_token();
            $inArr = array();
            $inArr['user_id'] = $user->id;
            $inArr['link'] = $link;
            $inslink = UserPasswordReset::create($inArr);

            $user->link = url('/reset-password/' . $link);
            $user = $user->toArray();

            Mail::send('emails.forgot-password', $user, function ($message) use ($user) {
                $message->to(trime_fn($user['email']))
                    ->subject(env('APP_NAME') . ' | reset password');
            });
            DB::commit();

            $message = 'Email sent. Please reset your password from there';
            return Redirect()->to('login')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            $message = $e->getMessage();
            session()->flash('error', $message);
            return $this->redirectRoute('forgot-password', navigate: true);
        }

    }
    public function render()
    {
        return view('livewire.forgot-password');
    }
}
