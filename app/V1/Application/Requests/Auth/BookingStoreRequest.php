<?php

namespace App\V1\Application\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class BookingStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email'
        ];
    }

    public function getDateTime()
    {
        return $this->input('date_time');
    }

    public function getMinutesTotal()
    {
        return $this->input('minutes_total');
    }

    public function getBookingNote()
    {
        return $this->input('booking_note');
    }

    public function getProducts()
    {
        return $this->input('products');
    }

    public function getName()
    {
        return $this->input('name');
    }

    public function getEmail()
    {
        return $this->input('email');
    }

    public function getPhone()
    {
        return $this->input('phone');
    }

    public function getImage()
    {
        return $this->input('image');
    }

    public function getImagePath()
    {
        return $this->input('image_path');
    }
}
