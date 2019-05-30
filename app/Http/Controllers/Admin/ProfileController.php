<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Profile;
use App\Profile_History;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function add()
{
    return view('admin.profile.create');
}

public function create(Request $request)
{
  $this->validate($request, Profile::$rules);
  $profile = new Profile;
  $form = $request->all();
  // if (isset($form['image'])) {
  //   $path = $request->file('image')->store('public/image');
  //   $profile->image_path = basename($path);
  // } else {
  //   $profile->image_path = null;
  // }
  unset($form['_token']);
  unset($form['image']);

  $profile->fill($form);
  $profile->save();

  return redirect('admin/profile/create');
}

public function edit(Request $request)
{
  $profile = Profile::find($request->id);
  if (empty($profile)) {
  abort(404);
}
    return view('admin.profile.edit',['profile_form' => $profile]);
}

public function update(Request $request)
{
  $this->validate($request, profile::$rules);
  // News Modelからデータを取得する
  $profile = profile::find($request->id);
  // 送信されてきたフォームデータを格納する
  $profile_form = $request->all();
  // if (isset($profile_form['image'])) {
  //   $path = $request->file('image')->store('public/image');
  //   $profile->image_path = basename($path);
  //   unset($news_form['image']);
  // } elseif (0 == strcmp($request->remove, 'true')) {
  //   $profile->image_path = null;
  // }
  unset($profile_form['_token']);
  unset($profile_form['remove']);

  // 該当するデータを上書きして保存する
  $profile->fill($profile_form)->save();

  $history = new Profile_History;
  $history->profile_id = $profile->id;
  $history->edited_at = Carbon::now();
  $history->save();

  // return redirect('admin/profile/');

  return redirect('admin/profiles');
}
}
