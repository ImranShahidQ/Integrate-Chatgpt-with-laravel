<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use DB;
use App\Models\User;


#[Title('incu - email verification')]
class EmailVerification extends Component
{
    #[Validate('required')]
    public $token;

    public function mount($token)
    {
        $this->token = $token;

        $user = User::where('email_verification_token', $this->token)->first();
        if (!$user) {
            $message = "Invalid Link";
            session()->flash('error', $message);
            return Redirect()->to('login')->with('error', $message);
        }
        $user = $user->toArray();

        $this->validate(
            [
                'token' => 'required',
            ],
            [
                'token.required' => 'Something went wrong'
            ]
        );

        DB::beginTransaction();
        try {
            $user = User::find($user['id'])->update(['email_verified' => COMMON_STATUS_YES, 'email_verification_token' => null]);

            DB::commit();

            $message = 'Email address are verfied successfully';
            return Redirect()->to('login')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            $message = $e->getMessage();
            return Redirect()->to('login')->with('error', $message);
        }
    }
    public function render()
    {
        return view('livewire.email-verification');
    }
}
