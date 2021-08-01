<?php

namespace Tests\Browser;

use App\V1\Application\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateBookingTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testItCanCreateBooking()
    {
        $products = Product::factory()->count(5)->create();

        $this->browse(function (Browser $browser) use($products) {
            $browser->visit('/')
                ->assertSee('Impulse Nails')
                ->waitForText($products->first()->name)
                ->scrollTo('.important-notice')
                ->screenshot('products_View')
                ->check('.v-input--checkbox')
                ->screenshot('after product checked')
                ->click('.next-button')
                ->waitUntilMissing('.next-button')
                ->screenshot('calendar view');

            $browser->script("document.querySelector('.v-calendar-weekly__day.v-future:nth-child(3)').click()");

            $browser->waitFor('.v-dialog', 5)
                ->screenshot('after click')
                ->click('.time-button-13')
                ->waitFor('.v-form', 5)
                ->screenshot('before info form')
                ->type('.name-form-field input', 'byron')
                ->type('.phone-form-field input', '454545454')
                ->type('.email-form-field input', 'byron@test.com')
                ->click('.book-button')
                ->waitForText('Thank you for booking with us', 5)
                ->screenshot('booking pending');
        });
    }
}
