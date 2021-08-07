<?php

namespace App\V1\Application\Jobs;

use App\V1\Application\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class SaveImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $image;
    private int $bookingId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $image, int $bookingId)
    {
        $this->image = $image;
        $this->bookingId = $bookingId;
    }

    public function handle(): void
    {
        $year = now()->year;

        $image_64 = $this->image; //your base64 encoded data
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($image_64, 0, strpos($image_64, ',')+1);

        // find substring fro replace here eg: data:image/png;base64,

        $image = str_replace($replace, '', $image_64);
        $image = str_replace(' ', '+', $image);

        $imageName = Uuid::uuid4().'.'.$extension;
        $imageName = $year . '/' . $imageName;

        Storage::disk('s3')->put($imageName, base64_decode($image),'public');

        $booking = Booking::find($this->bookingId);
        $booking->path = $imageName;
        $booking->save();
    }
}
