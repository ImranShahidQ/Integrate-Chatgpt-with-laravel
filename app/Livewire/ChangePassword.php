<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use DB;
use Auth;
use Hash;

#[Title('incu - change password')]
class ChangePassword extends Component
{
    public $current_password;
    public $password;
    public $password_confirmation;

    public function updatePassword()
    {

        // $this->validate();
        $this->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|required_with:password',
        ]);

        $user = Auth::user();
        if (!Hash::check($this->current_password, $user->password)) {
            $message = 'Current password does not match';
            session()->flash('error', $message);
            return $this->redirectRoute('change-password', navigate: true);
        }

        DB::beginTransaction();
        try {
            $user->password = Hash::make($this->password);
            $user->save();

            DB::commit();

            session()->flash('success', 'success');

            return $this->redirectRoute('change-password', navigate: true);

        } catch (\Exception $e) {
            DB::rollBack();

            $message = $e->getMessage();
            session()->flash('error', $message);
            return $this->redirectRoute('change-password', navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.change-password');
    }
}
