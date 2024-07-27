<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use DB;
use Auth;

#[Title('incu - profile')]
class Profile extends Component
{
    // #[Validate('required')]
    public $name;

    // #[Validate('required|email|unique:users,email')]
    public $email;


    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile()
    {

        // $this->validate();
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $user->name = $this->name;
            $user->email = $this->email;
            $user->save();

            DB::commit();

            session()->flash('success', 'success');

            return $this->redirectRoute('profile', navigate: true);

        } catch (\Exception $e) {
            DB::rollBack();

            $message = $e->getMessage();
            session()->flash('error', $message);
            return $this->redirectRoute('profile', navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
