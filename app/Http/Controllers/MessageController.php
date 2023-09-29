<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Tweet;
use App\Models\User;
use App\Models\Message;
use Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'recieving_id' => 'required|exists:users,id',
            'dm' => 'nullable|string', // 'nullable'
        ]);

        // メッセージを取得
        $dm = $request->input('dm');

        // dm が null でない場合にメッセージをデータベースに保存
        if ($dm !== null) {
            $data = $request->merge(['user_id' => Auth::user()->id])->all();
            $result = Message::create($data);
        }

        // 選択したユーザーをセッションに保存
        session(['selected_user' => $request->input('recieving_id')]);
        
        // メッセージ送信後のリダイレクトなどを行う
        return redirect()->route('tweet.direct');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
