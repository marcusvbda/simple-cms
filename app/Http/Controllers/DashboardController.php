<?php

namespace App\Http\Controllers;

use App\Http\Models\InterfaceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
	public function index(Request $request)
	{
		$user = Auth::user();
		return view('admin.home', compact('user'));
	}

	public function getData($action, Request $request)
	{
		return $this->{$action}($request);
	}

	protected function interfaceQty()
	{
		return InterfaceModel::count();
	}
}
