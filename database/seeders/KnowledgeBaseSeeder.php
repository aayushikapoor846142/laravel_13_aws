<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\SupportKnowledgeBaseService;

class KnowledgeBaseSeeder extends Seeder
{
    public function run(): void
    {
        $kbService = app(SupportKnowledgeBaseService::class);

        $articles = [
            [
                'title' => 'How to reset your password',
                'content' => 'To reset your password: 1. Click on "Forgot Password" on the login page. 2. Enter your email address. 3. Check your email for a password reset link. 4. Click the link and enter your new password. 5. Your password is now reset and you can log in with the new password.',
                'category' => 'Account Management'
            ],
            [
                'title' => 'How to update billing information',
                'content' => 'To update your billing information: 1. Log in to your account. 2. Go to Settings > Billing. 3. Click "Update Payment Method". 4. Enter your new credit card or payment details. 5. Click Save. Your billing information will be updated immediately.',
                'category' => 'Billing'
            ],
            [
                'title' => 'Refund policy',
                'content' => 'Our refund policy: We offer a 30-day money-back guarantee on all purchases. If you are not satisfied with your purchase, you can request a full refund within 30 days of purchase. To request a refund, contact our support team with your order number. Refunds are processed within 5-7 business days.',
                'category' => 'Billing'
            ],
            [
                'title' => 'How to contact support',
                'content' => 'You can contact our support team in several ways: 1. Email: support@example.com (response within 24 hours). 2. Live chat: Available on our website Monday-Friday 9am-5pm EST. 3. Phone: Call us at 1-800-SUPPORT. 4. Submit a ticket through your account dashboard.',
                'category' => 'Support'
            ],
            [
                'title' => 'Getting started guide',
                'content' => 'Welcome! Here is how to get started: 1. Create your account and verify your email. 2. Complete your profile information. 3. Explore the dashboard and familiarize yourself with the features. 4. Check out our video tutorials in the Help section. 5. Start using the platform! If you need help, our support team is always available.',
                'category' => 'Getting Started'
            ],
            [
                'title' => 'System requirements',
                'content' => 'Minimum system requirements: Browser: Chrome 90+, Firefox 88+, Safari 14+, or Edge 90+. Internet: Stable broadband connection (5 Mbps minimum). Device: Desktop, laptop, tablet, or smartphone. Operating System: Windows 10+, macOS 10.15+, iOS 14+, or Android 10+. For best performance, we recommend using the latest browser version.',
                'category' => 'Technical'
            ],
            [
                'title' => 'Privacy and data security',
                'content' => 'We take your privacy seriously. Your data is encrypted in transit and at rest using industry-standard encryption. We never sell your personal information to third parties. You can export or delete your data at any time from your account settings. We comply with GDPR, CCPA, and other privacy regulations. Read our full Privacy Policy for more details.',
                'category' => 'Privacy & Security'
            ],
            [
                'title' => 'How to cancel subscription',
                'content' => 'To cancel your subscription: 1. Log in to your account. 2. Go to Settings > Subscription. 3. Click "Cancel Subscription". 4. Confirm cancellation. Your subscription will remain active until the end of your current billing period. You will not be charged again. You can reactivate your subscription at any time.',
                'category' => 'Billing'
            ]
        ];

        foreach ($articles as $article) {
            $kbService->addArticle(
                $article['title'],
                $article['content'],
                $article['category']
            );
        }

        $this->command->info('Knowledge base seeded successfully!');
    }
}
