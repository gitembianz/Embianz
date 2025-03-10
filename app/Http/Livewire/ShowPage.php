<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Static_Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;


class ShowPage extends Component
{
    public $itemId;
    public $edititem = null;
    public $delete = false;
    public $record;
    public function render()
    {
        return view('livewire.show-page', [
            'page' => $this->page
        ]);
    }
    public function mount($pageId)
    {
        $this->itemId = $pageId;
    }
    public function confirmItemRemoval()
    {
        $this->delete = true;
    }
    public function cancelItemRemoval()
    {
        $this->delete = false;
    }
    public function getPageQueryProperty()
    {
        return Static_Page::find($this->itemId);
    }
    public function getPageProperty()
    {
        return $this->pageQuery;
    }
    public function edititem()
    {
        $this->record = [
            'name' => $this->page->name,
            'route' => $this->page->route,
            'content' => $this->page->content,
            'sequence' => $this->page->sequence,
            'description' => $this->page->description,
            'display_in_footer' => $this->page->display_in_footer == 1 ? true : false,
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
        $rec = $this->record ?? null;
        if (is_null($rec)) {
            return;
        }

        $fields = ['name', 'route', 'content', 'description', 'sequence'];
        foreach ($fields as $field) {
            if (array_key_exists($field, $rec)) {
                if (!empty($rec[$field])) {
                    if ($field == 'route') {
                        $rec[$field] = str_replace(' ', '-', $rec[$field]);
                    }
                    $this->page->$field = $rec[$field];
                } else {
                    session()->flash('notification', [
                        'message' => "Please provide a value for $field!",
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
        }

        if (array_key_exists('display_in_footer', $rec)) {
            $this->page->display_in_footer = $rec['display_in_footer'];
        }

        $this->page->last_modified_by = auth()->user()->name;
        $this->page->save();

        $this->emit('itemSaved');
        session()->flash('notification', [
            'message' => 'Record edited successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);

        $this->record = [];
        $this->edititem = null;
        Cache::forget('static_pages');
        $this->registerDynamicRoutes();
    }
    public function registerDynamicRoutes()
    {
        $pages = Cache::rememberForever('static_pages', function () {
            return Static_Page::all();
        });

        foreach ($pages as $page) {
            Route::get($page->route, function () use ($page) {
                return view('store.page', ['page' => $page]);
            })->name($page->route);
        }
    }
    public function deleteSingleRecord()
    {
        $this->page->delete();
        Cache::forget('static_pages');

        $this->registerDynamicRoutes();

        $this->delete = false;
        return redirect()->route('pages')->with('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
}
