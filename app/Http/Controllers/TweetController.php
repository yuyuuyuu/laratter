<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Tweet;
use App\Models\User;
use App\Models\Message;
use Auth;


class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tweets = Tweet::getAllOrderByUpdated_at();
        return response()->view('tweet.index',compact('tweets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return response()->view('tweet.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // バリデーション
        $validator = Validator::make($request->all(), [
        'tweet' => 'required | max:191',
        'description' => 'required',
        ]);
        // バリデーション:エラー
        if ($validator->fails()) {
        return redirect()
            ->route('tweet.create')
            ->withInput()
            ->withErrors($validator);
        }

        // 🔽 編集 フォームから送信されてきたデータとユーザIDをマージし，DBにinsertする
        $data = $request->merge(['user_id' => Auth::user()->id])->all();
        $result = Tweet::create($data);

        // tweet.index」にリクエスト送信（一覧ページに移動）
        return redirect()->route('tweet.index');

            $property = new Property();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tweet = Tweet::find($id);
        return response()->view('tweet.show', compact('tweet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tweet = Tweet::find($id);
        return response()->view('tweet.edit', compact('tweet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //バリデーション
        $validator = Validator::make($request->all(), [
            'tweet' => 'required | max:191',
            'description' => 'required',
        ]);
        //バリデーション:エラー
        if ($validator->fails()) {
            return redirect()
            ->route('tweet.edit', $id)
            ->withInput()
            ->withErrors($validator);
        }
        //データ更新処理
        $result = Tweet::find($id)->update($request->all());
        return redirect()->route('tweet.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $result = Tweet::find($id)->delete();
        return redirect()->route('tweet.index');
    }

    public function mydata()
    {
        // Userモデルに定義したリレーションを使用してデータを取得する．
        $tweets = User::query()
        ->find(Auth::user()->id)
        ->userTweets()
        ->orderBy('created_at','desc')
        ->get();
        return response()->view('tweet.index', compact('tweets'));
    }

    public function timeline()
    {
        // フォローしているユーザを取得する
        $followings = User::find(Auth::id())->followings->pluck('id')->all();
        // 自分とフォローしている人が投稿したツイートを取得する
        $tweets = Tweet::query()
            ->where('user_id', Auth::id())
            ->orWhereIn('user_id', $followings)
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('tweet.index', compact('tweets'));
    }

    public function direct()
    {
        // ログインしているユーザーのIDとセッションで保存されているIDを取得
        $loggedInUserId = auth()->id();
        $selectedUserId = session('selected_user');

        // メッセージ一覧を取得
        $messages = Message::where(function ($query) use ($loggedInUserId, $selectedUserId) {
            $query->where('user_id', $loggedInUserId)
                ->where('recieving_id', $selectedUserId);
        })->orWhere(function ($query) use ($loggedInUserId, $selectedUserId) {
            $query->where('user_id', $selectedUserId)
                ->where('recieving_id', $loggedInUserId);
        })
        ->orderBy('created_at')
        ->get();

        // ユーザー名リストを取得
        $userNames = User::pluck('name', 'id');

        return response()->view('tweet.direct', compact('messages', 'userNames'));
    }


}
