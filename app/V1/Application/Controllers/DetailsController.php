<?php

namespace App\V1\Application\Controllers;

use App\Http\Controllers\Controller;
use App\V1\Application\Models\Detail;
use Illuminate\Http\Request;

class DetailsController extends Controller
{
    public function index()
    {
        return Detail::first();
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $detail = Detail::first();

        $detail->about = $data['about'];
        $detail->important = $data['important'];
        $detail->save();

        return 'success';
    }
}
