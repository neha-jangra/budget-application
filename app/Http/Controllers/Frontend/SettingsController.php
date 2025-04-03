<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public $activeTab = 'edit_profile';

    public function index(Request $request)
    {
        $this->activeTab = $request->query('active_tab') ?? 'edit_profile';
        return view('settings.index', ['activeTab'=>$this->activeTab]);
    }
}
