<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TermsCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class TermsConditionController extends Controller
{
    // El método checkAdmin ha sido reemplazado por el middleware AdminMiddleware
    public function index()
    {
        $terms = TermsCondition::first();
        return view('admin.terms', compact('terms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        TermsCondition::updateOrCreate(
            ['id' => 1],
            ['content' => $request->content]
        );

        return redirect()->route('admin.terms')->with('success', 'Terms & Conditions updated successfully');
    }
}