<?php

namespace Tests\Unit\Action\File;

use App\Action\File\GetFileNameAction;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class GetFileNameActionTest extends TestCase
{
    public function test_filename_is_as_expected()
    {
        $file = UploadedFile::fake()->create('foo.jpg');
        $filename = (new GetFileNameAction())->handle($file);

        $this->assertMatchesRegularExpression('/^foo_[A-Za-z0-9]+\.jpg/', $filename);
    }
}
