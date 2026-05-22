<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KbArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'How to Reset Your Password',
                'content' => 'To reset your password, follow these steps: 1. Go to the login page. 2. Click on "Forgot Password" link. 3. Enter your email address. 4. Check your email for a password reset link. 5. Click the link and enter your new password. 6. Confirm your new password and submit. Your password has been successfully reset.',
                'category' => 'Account Management',
                'is_published' => true,
            ],
            [
                'title' => 'How to Update Your Profile',
                'content' => 'To update your profile information: 1. Log in to your account. 2. Click on your profile icon in the top right corner. 3. Select "Profile Settings". 4. Update your information such as name, email, phone number, and address. 5. Click "Save Changes" to update your profile. Your profile has been successfully updated.',
                'category' => 'Account Management',
                'is_published' => true,
            ],
            [
                'title' => 'Payment Methods Accepted',
                'content' => 'We accept the following payment methods: Credit Cards (Visa, MasterCard, American Express), Debit Cards, PayPal, Bank Transfer, and Cryptocurrency (Bitcoin, Ethereum). All payments are processed securely through our encrypted payment gateway. You can manage your payment methods in your account settings.',
                'category' => 'Billing',
                'is_published' => true,
            ],
            [
                'title' => 'How to Request a Refund',
                'content' => 'To request a refund: 1. Log in to your account. 2. Go to "Order History". 3. Find the order you want to refund. 4. Click "Request Refund". 5. Select the reason for refund. 6. Submit your request. Our team will review your request within 2-3 business days. Refunds are processed within 5-7 business days after approval.',
                'category' => 'Billing',
                'is_published' => true,
            ],
            [
                'title' => 'Shipping and Delivery Information',
                'content' => 'Standard shipping takes 5-7 business days. Express shipping takes 2-3 business days. International shipping takes 10-15 business days. Free shipping is available for orders over $50. You will receive a tracking number via email once your order ships. You can track your order in the "Order History" section of your account.',
                'category' => 'Shipping',
                'is_published' => true,
            ],
            [
                'title' => 'How to Track Your Order',
                'content' => 'To track your order: 1. Log in to your account. 2. Go to "Order History". 3. Click on the order you want to track. 4. View the tracking information and current status. 5. Click on the tracking number to see detailed shipping updates. You will also receive email notifications when your order status changes.',
                'category' => 'Shipping',
                'is_published' => true,
            ],
            [
                'title' => 'Return Policy',
                'content' => 'Our return policy allows returns within 30 days of purchase. Items must be unused and in original packaging. To initiate a return: 1. Contact our support team. 2. Provide your order number and reason for return. 3. Receive a return authorization number. 4. Ship the item back to us. 5. Refund will be processed within 5-7 business days after we receive the item.',
                'category' => 'Returns',
                'is_published' => true,
            ],
            [
                'title' => 'Contact Support',
                'content' => 'You can contact our support team through multiple channels: Email: support@example.com, Phone: +1-800-123-4567 (Mon-Fri 9AM-5PM EST), Live Chat: Available on our website during business hours, Support Ticket: Submit a ticket through your account dashboard. Our average response time is 24 hours for emails and tickets.',
                'category' => 'Support',
                'is_published' => true,
            ],
            [
                'title' => 'Account Security Best Practices',
                'content' => 'To keep your account secure: 1. Use a strong, unique password with at least 12 characters. 2. Enable two-factor authentication (2FA). 3. Never share your password with anyone. 4. Log out from shared devices. 5. Regularly review your account activity. 6. Update your password every 3-6 months. 7. Be cautious of phishing emails. If you suspect unauthorized access, change your password immediately and contact support.',
                'category' => 'Security',
                'is_published' => true,
            ],
            [
                'title' => 'How to Enable Two-Factor Authentication',
                'content' => 'To enable two-factor authentication (2FA): 1. Log in to your account. 2. Go to "Security Settings". 3. Click "Enable 2FA". 4. Choose your preferred method (SMS or Authenticator App). 5. Follow the setup instructions. 6. Enter the verification code to confirm. 7. Save your backup codes in a safe place. 2FA adds an extra layer of security to your account.',
                'category' => 'Security',
                'is_published' => true,
            ],
        ];

        foreach ($articles as $article) {
            \App\Models\KbArticle::create($article);
        }
    }
}
