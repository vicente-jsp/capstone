<?php

namespace App\View\Components; // <-- Check Namespace

use Illuminate\View\Component;
use Illuminate\Contracts\View\View; // Check Use statement

class AuthSessionStatus extends Component // <-- Check Class Name & extends
{
    /**
     * The session status message.
     *
     * @var string|null
     */
    public $status;

    /**
     * Create a new component instance.
     *
     * @param  string|null  $status
     * @return void
     */
    public function __construct($status = null)
    {
        $this->status = $status;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View // Check Return Type
    {
        // Check view path
        return view('components.auth-session-status');
    }
}