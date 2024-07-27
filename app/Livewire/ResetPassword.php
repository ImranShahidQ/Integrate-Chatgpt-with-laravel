<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use DB;
use Mail;
use Hash;
use App\Models\User;
use App\Models\UserPasswordReset;

#[Title('incu - reset password')]
class ResetPassword extends Component
{
    #[Validate('required')]
    public $link;
    public $password;
    public $password_confirmation;

    public function mount($link)
    {
        $this->link = $link;

        $userLink = UserPasswordReset::where('link', $this->link)->first();
        if (!$userLink) {
            $message = "Invalid Link";
            session()->flash('error', $message);            
            return Redirect()->to('login')->with('error', $message);
        }
        $userLink = $userLink->toArray();

        $d1 = date("Y-m-d H:i:s");
        $d2 = date("Y-m-d H:i:s", strtotime($userLink['created_at']));
        $date1 = strtotime($d1);
        $date2 = strtotime($d2);
        $diff = ($date1 - $date2) / 60;
        if ($diff > 60) {
            $message = "Link expired";
            session()->flash('error', $message);
            return Redirect()->to('login')->with('error', $message);
        }
    }
    public function resetPassword()
    {
        $userLink = UserPasswordReset::where('link', $this->link)->first();
        if (!$userLink) {
            $message = "Invalid Link";
            session()->flash('error', $message);
            return Redirect()->to('login')->with('error', $message);
        }
        $userLink = $userLink->toArray();

        $d1 = date("Y-m-d H:i:s");
        $d2 = date("Y-m-d H:i:s", strtotime($userLink['created_at']));
        $date1 = strtotime($d1);
        $date2 = strtotime($d2);
        $diff = ($date1 - $date2) / 60;
        if ($diff > 60) {
            $message = "Link expired";
            session()->flash('error', $message);
            return Redirect()->to('login')->with('error', $message);
        }

        // $this->validate();
        $this->validate(
            [
                'link' => 'required',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|required_with:password',
            ],
            [
                'link.required' => 'Something went wrong'
            ]
        );

        DB::beginTransaction();
        try {
            $password = Hash::make($this->password);
            $user = User::find($userLink['user_id'])->update(['password' => $password]);
            $deleteLink = UserPasswordReset::where('user_id', $userLink['user_id'])->delete();

            DB::commit();
            
            $message = 'Password are reset';
            return Redirect()->to('login')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            $message = $e->getMessage();
            session()->flash('error', $message);
            return Redirect()->to('login')->with('error', $message);
        }

    }
    public function render()
    {
        return view('livewire.reset-password');
    }
}
