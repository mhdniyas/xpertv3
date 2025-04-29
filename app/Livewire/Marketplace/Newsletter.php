<?php

namespace App\Livewire\Marketplace;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class Newsletter extends Component
{
    public $email = '';
    public $successMessage = '';
    public $errorMessage = '';

    public function subscribe()
    {
        // Validate email
        $validator = Validator::make(
            ['email' => $this->email],
            ['email' => 'required|email|max:255'],
            [
                'email.required' => 'Please enter your email address',
                'email.email' => 'Please enter a valid email address',
            ]
        );

        if ($validator->fails()) {
            $this->errorMessage = $validator->errors()->first('email');
            $this->successMessage = '';
            return;
        }

        // Clear any previous error
        $this->errorMessage = '';

        // In a real application, you would save to database
        // For now, we'll just simulate success

        // Subscriber::create(['email' => $this->email]);

        // Show success message and reset form
        $this->successMessage = 'Thank you for subscribing to our newsletter!';
        $this->email = '';
    }

    public function render()
    {
        return view('livewire.marketplace.newsletter');
    }
}
