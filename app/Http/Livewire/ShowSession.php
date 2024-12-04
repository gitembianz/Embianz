<?php

namespace App\Http\Livewire;

use App\Models\UserSessions;
use Livewire\Component;

class ShowSession extends Component
{
    public $delete = false;
    public $sessionId;

    public function render()
    {
        return view('livewire.show-session', [
            'session' => $this->session
        ]);
    }
    public function updated()
    {
        $this->dispatchBrowserEvent('tabNavigation');
    }
    public function getSessionProperty()
    {
        return UserSessions::find($this->sessionId);
    }
    public function mount($sessionId)
    {
        $this->sessionId = $sessionId;
    }
    public function confirmItemRemoval()
    {
        $this->delete = true;
    }
    public function deleteRecord()
    {
        UserSessions::find($this->sessionId)->delete();
        $this->delete = false;
        return redirect()->route('sessions')->with('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
}
