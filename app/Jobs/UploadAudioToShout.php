<?php

namespace App\Jobs;

use App\Action\Services\MakeShoutRequestAction;
use App\Models\FileUpload;
use App\Services\Shout\Requests\Audio\AudioStore;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadAudioToShout implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public FileUpload $fileUpload)
    {
    }

    public function handle(): void
    {
        $response = (new MakeShoutRequestAction())->handle(
            (new AudioStore(
                $this->fileUpload,
                ['file_name' => $this->fileUpload->name]
            ))
        );

        $this->handleResponse($response);
    }

    protected function handleResponse(array $response): void
    {
        $data = $response[0]->toArray();

        if($data && array_key_exists('file_number', $data)){
            $this->fileUpload->update([
                'external_id' => $data['file_number'],
            ]);
        }
    }

    public function tags(): array
    {
        return ['Upload Audio To Shout'];
    }
}
