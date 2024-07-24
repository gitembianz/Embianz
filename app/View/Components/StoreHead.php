<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StoreHead extends Component
{
    public $title;
    public $description;
    public $canonical;
    public $image;
    public $preload;
    public $sessionId;

    /**
     * Create a new component instance.
     */
    public function __construct($title = "", $description = "", $canonical = "", $image = "", $preload = "")
    {
        $this->title = $title . app('global_site_name');
        $this->description = empty($description)
            ? ""
            : $description;
        $this->canonical = $canonical;
        $this->image  = empty($image)
            ? "images/store/logo-banner.webp"
            : $image;
        $this->preload  = $preload;

        // Set the session ID and store it in a cookie
        $this->sessionId = $this->getSessionId();
    }

    private function getSessionId()
    {
        if (array_key_exists('sessionId', $_COOKIE)) {
            return $_COOKIE['sessionId'];
        } else {
            $sessionId = session()->getId();

            // Set the sessionId cookie
            setrawcookie('sessionId', $sessionId, [
                'expires' => time() + 30 * 24 * 60 * 60, // 30 days
                'path' => '/',
                'secure' => false, // Set to true if using HTTPS
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            return $sessionId;
        }
    }

    public function render()
    {
        return view('components.store-head', [
            'title' => $this->title,
            'description' => $this->description,
            'canonical' => $this->canonical,
            'image' => $this->image,
            'preload' => $this->preload,
            'sessionId' => $this->sessionId, // Pass the session ID to the view if needed
        ]);
    }
}