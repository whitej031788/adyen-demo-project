<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class DemoController extends Controller
{
  public function view()
  {
    return view('create-demo');
  }

  public function create(Request $request)
  {
    $validatedData = $request->validate([
      'merchantName' => 'required',
      'merchantLogoUrl' => 'required|url'
    ]);

    // Once we have validated all required fields, lets just put the demo settings into a JSON object
    // this will be easy to manage both server side and client side
    $params = $request->all();
    // var_dump($params);
    // exit();
    $request->session()->put('demo_session', $params);

    if (isset($params["_token"])) {
      unset($params["_token"]);
    }
    $configJson = json_encode($params);
    Storage::disk('local')->put('demo_configs/'.$params['merchantName'].'.txt', $configJson);


    return redirect('/');
  }

  public function delete(Request $request)
  {
    $request->session()->forget('demo_session');
    return redirect('/create-demo');
  }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileUpload()
    {
      return view('fileUpload');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileUploadPost(Request $request)
    {
      $request->validate([
        'file' => 'required|mimes:txt,json|max:2048',
      ]);

      $fileData = $request->file->get();
      var_dump($fileData);
      exit();

        // $fileName = time().'.'.$request->file->extension();  

        // $request->file->move(public_path('uploads'), $fileName);

      // return back()
      // ->with('success','You have successfully upload file.')
      // ->with('file',$fileName);

    }


  }