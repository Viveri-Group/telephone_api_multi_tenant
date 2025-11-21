<?php

namespace App\Http\Controllers;

use App\Action\File\AudioFileExistsCheckAction;
use App\Action\File\GetFileNameAction;
use App\Action\Services\MakeShoutRequestAction;
use App\Http\Requests\AudioFileUploadRequest;
use App\Jobs\UploadAudioToShout;
use App\Models\Competition;
use App\Models\FileUpload;
use App\Services\Shout\Requests\Audio\AudioDelete;
use Illuminate\Support\Facades\Storage;

class AudioFileUploadController extends Controller
{
    public function store(AudioFileUploadRequest $request, Competition $competition)
    {
        abort_if((new AudioFileExistsCheckAction())->handle($request->toArray(), $competition), 403, 'Audio file already exists.');

        $file = $request->file('file');
        $filename = (new GetFileNameAction())->handle($file);

        $audio_type = [];

        if($request->input('audio_type') === 'competition'){
            $audio_type['competition_id'] = $competition->id;
        }

        if($request->input('audio_type') === 'competition_phone_line'){
            $audio_type['competition_phone_line_id'] = $request->input('competition_phone_line_id');
        }

        $fileUpload = FileUpload::create([
            'filename' => $filename,
            'name' => $request->input('name'),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'type' => $request->input('type'),
            ...$audio_type
        ]);

        Storage::disk('local')->put(
            FileUpload::LOCAL_BASE . $filename,
            file_get_contents($file->getRealPath())
        );

        UploadAudioToShout::dispatch($fileUpload);

        return response()->noContent(201);
    }

    public function destroy(Competition $competition, FileUpload $file)
    {
        if((new MakeShoutRequestAction())->handle(
            (new AudioDelete($file))
        )){
            $file->delete();
        }

        return response()->noContent();
    }
}
