<?php

namespace App\Http\View\Composers;

use App\Services\Backend\Setting\UserService;
use Illuminate\View\View;

class NotificationDropDownComposer
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * Create a new profile composer.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {

    }
}
