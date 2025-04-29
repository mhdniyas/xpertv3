<?php

namespace App\Livewire\Marketplace;

use Livewire\Component;

class FaqAccordion extends Component
{
    // Track which FAQ items are open
    public $openItems = [];

    // FAQ data - could be moved to database in a real application
    public $faqs = [
        [
            'question' => 'How do I place an order?',
            'answer' => 'Browse our marketplace, select the items you want, add them to your cart, and proceed to checkout. You\'ll need to provide shipping and payment details to complete your purchase.'
        ],
        [
            'question' => 'What payment methods do you accept?',
            'answer' => 'We accept all major credit cards (Visa, MasterCard, American Express), PayPal, and bank transfers. All payments are processed securely.'
        ],
        [
            'question' => 'How long does shipping take?',
            'answer' => 'Shipping times vary depending on your location and the seller. Most domestic orders arrive within 3-5 business days, while international orders may take 7-14 business days.'
        ],
        [
            'question' => 'What is your return policy?',
            'answer' => 'We offer a 30-day return policy for most items if they\'re in original condition. Some sellers may have different policies, which will be clearly indicated on their product listings.'
        ],
        [
            'question' => 'How do I contact customer support?',
            'answer' => 'You can reach our customer support team by email at support@marketplace.com, by phone at 1-800-555-1234, or through the contact form on our website. We\'re available Monday-Friday, 9am-5pm.'
        ],
        [
            'question' => 'Can I sell my own products on your marketplace?',
            'answer' => 'Yes! We welcome new sellers. Click on "Become a Seller" in your account settings to get started. You\'ll need to complete a simple application process and agree to our seller terms.'
        ],
    ];

    public function toggleItem($index)
    {
        if (isset($this->openItems[$index])) {
            unset($this->openItems[$index]);
        } else {
            $this->openItems[$index] = true;
        }
    }

    public function render()
    {
        return view('livewire.marketplace.faq-accordion');
    }
}
