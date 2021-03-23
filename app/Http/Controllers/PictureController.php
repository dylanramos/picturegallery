<?php

namespace App\Http\Controllers;

use App\Picture;
use App\Http\Requests\PictureRequest;
use Illuminate\Http\Request;

use Aws\S3\S3Client;

class PictureController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $pictures = Picture::all();
    return view('pictures.index', compact('pictures'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('pictures.create', $this->createPreSignedPost());
  }

  public function createPreSignedPost()
  {
    $awsClient = new S3Client([
      'version' => 'latest',
      'region' => env('AWS_DEFAULT_REGION')
    ]);
    $bucket = env('AWS_BUCKET');
    $key = "dylan/" . \Str::random(40);
    $expires = '+1 hours';
    // Set some defaults for form input fields
    $formInputs = ['acl' => 'private', 'key' => $key];

    // Construct an array of conditions for policy
    $options = [
      ['acl' => 'private'],
      ['bucket' => $bucket],
      ['eq', '$key', $key],
    ];

    $postObject = new \Aws\S3\PostObjectV4(
      $awsClient,
      $bucket,
      $formInputs,
      $options,
      $expires
    );

    return [
      's3attributes' => $postObject->getFormAttributes(),
      's3inputs' => $postObject->getFormInputs()
    ];
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(PictureRequest $request)
  {
    $picture = new Picture;
    $picture->fill($request->all());
    $picture->save();
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Picture  $picture
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request, Picture $picture)
  {
    if (\Str::startsWith($request->header('Accept'), 'image')) {
      return redirect(\Storage::disk('s3')->temporaryUrl($picture->storage_path, now()->addMinutes(1)));
    }

    return view('pictures.show', compact('picture'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Picture  $picture
   * @return \Illuminate\Http\Response
   */
  public function edit(Picture $picture)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Picture  $picture
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Picture $picture)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Picture  $picture
   * @return \Illuminate\Http\Response
   */
  public function destroy(Picture $picture)
  {
    //
  }
}
