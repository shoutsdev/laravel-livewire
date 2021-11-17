<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User as UserModel;

class User extends Component
{
    public $name, $email, $user_id;
    public $updateMode = false;

    public function render()
    {
        $users = UserModel::all();

        $data = [
              'users' => $users
        ];
        return view('livewire.user',$data);
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);
        UserModel::create([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        session()->flash('message', 'Users Created Successfully.');

        $this->resetInputFields();

        $this->emit('userStore'); // Close model to using to jquery
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $user = UserModel::find($id);
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);
        if ($this->user_id) {
            $user = UserModel::find($this->user_id);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);
            $this->updateMode = false;
            session()->flash('message', 'Users Updated Successfully.');
            $this->resetInputFields();
        }
    }

    public function delete($id)
    {
        if ($id) {
            UserModel::destroy($id);
            session()->flash('message', 'Users Deleted Successfully.');
        }
    }
}
