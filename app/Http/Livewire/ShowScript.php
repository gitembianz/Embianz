<?php

namespace App\Http\Livewire;

use App\Models\CustomScript;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;


class ShowScript extends Component
{
    public $itemId;
    public $record = [];
    public $edititem = null;

    public function render()
    {
        return view('livewire.show-script', [
            'script' => $this->script
        ]);
    }
    public function mount($scriptId)
    {
        $this->itemId = $scriptId;
    }
    public function confirmItemRemoval()
    {
        $this->dispatchBrowserEvent('show-delete-modal');
    }
    public function getScriptProperty()
    {
        return CustomScript::find($this->itemId);
    }
    public function deleteSingleRecord()
    {
        $this->script->delete();

        return redirect()->route('customscripts')->with('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function edititem()
    {
        $this->record = [
            'name' => $this->script->name,
            'type' => $this->script->type,
            'content' => $this->script->content,
            'active' => $this->script->active == 1 ? true : false,
        ];
        $this->edititem = true;
    }
    public function cancelitem()
    {
        $this->edititem = null;
        $this->record = [];
    }
    public function saveitem()
    {
        $rec = $this->record ?? NULL;
        if (!is_null($rec)) {
            $new = CustomScript::find($this->itemId);
            if (array_key_exists('type', $rec)) {
                if (!empty($rec['type'])) {
                    $new->type = $rec['type'];
                } else {
                    session()->flash('notification', [
                        'message' => 'Please provide a value!',
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
            if (array_key_exists('name', $rec)) {
                if (!empty($rec['name'])) {
                    $new->name = $rec['name'];
                } else {
                    session()->flash('notification', [
                        'message' => 'Please provide a name!',
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
            if (array_key_exists('active', $rec)) {
                $new->active = $rec['active'];
            }
            if (array_key_exists('content', $rec)) {
                if (!empty($rec['content'])) {
                    $new->content = $rec['content'];
                } else {
                    session()->flash('notification', [
                        'message' => 'Please provide a value!',
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
            $new->updated_at = now();
            $new->save();
            Cache::forget('global_scripts');

            session()->flash('notification', [
                'message' => 'Record edited successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);
        }
        $this->record = [];
        $this->edititem = null;
    }
}
