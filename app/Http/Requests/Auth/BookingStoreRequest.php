<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class BookingStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
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
}
