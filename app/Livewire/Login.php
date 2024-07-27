<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Auth;

#[Title('incu - login')]
class Login extends Component
{
    #[Validate('required|email')]
    public $email;

    #[Validate('required')]
    public $password;
    public function login()
    {
        $this->validate();

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials)) {
            $message = 'success';
            return Redirect()->to('dashboard')->with('success', $message);

            // session()->flash('message', 'success');
            // return $this->redirectRoute('dashboard', navigate: true);
        }

        session()->flash('error', 'Invalid credentials!');
    }
    public function render()
    {
        return view('livewire.login');
    }
}
