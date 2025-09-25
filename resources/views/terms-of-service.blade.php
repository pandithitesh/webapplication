@extends('layouts.app')

@section('title', 'Terms of Service')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Terms of Service</h1>
        
        <div class="prose max-w-none">
            <p class="text-gray-600 mb-6">Last updated: {{ date('F d, Y') }}</p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Acceptance of Terms</h2>
            <p class="text-gray-700 mb-6">
                By accessing and using our event management platform, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.
            </p>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Description of Service</h2>
            <p class="text-gray-700 mb-4">
                Our platform provides the following services:
            </p>
            <ul class="list-disc pl-6 text-gray-700 mb-6">
                <li>Event discovery and browsing for attendees</li>
                <li>Event creation and management for organizers</li>
                <li>Event booking and registration system</li>
                <li>User account management and profiles</li>
                <li>Communication tools between organizers and attendees</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. User Accounts</h2>
            <p class="text-gray-700 mb-4">
                To use certain features of our platform, you must create an account. You agree to:
            </p>
            <ul class="list-disc pl-6 text-gray-700 mb-6">
                <li>Provide accurate, current, and complete information</li>
                <li>Maintain and update your account information</li>
                <li>Keep your password secure and confidential</li>
                <li>Accept responsibility for all activities under your account</li>
                <li>Notify us immediately of any unauthorized use</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. User Responsibilities</h2>
            <p class="text-gray-700 mb-4">
                As a user of our platform, you agree to:
            </p>
            <ul class="list-disc pl-6 text-gray-700 mb-6">
                <li>Use the platform only for lawful purposes</li>
                <li>Respect other users and their privacy</li>
                <li>Not post false, misleading, or inappropriate content</li>
                <li>Not attempt to gain unauthorized access to the platform</li>
                <li>Not use the platform to spam or harass others</li>
                <li>Comply with all applicable laws and regulations</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Event Organizer Responsibilities</h2>
            <p class="text-gray-700 mb-4">
                If you create events on our platform, you agree to:
            </p>
            <ul class="list-disc pl-6 text-gray-700 mb-6">
                <li>Provide accurate and complete event information</li>
                <li>Honor all bookings and commitments made through the platform</li>
                <li>Comply with all applicable laws and regulations for your events</li>
                <li>Maintain appropriate insurance for your events</li>
                <li>Respond promptly to attendee inquiries and concerns</li>
                <li>Provide refunds according to your stated refund policy</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Event Attendee Responsibilities</h2>
            <p class="text-gray-700 mb-4">
                If you book events through our platform, you agree to:
            </p>
            <ul class="list-disc pl-6 text-gray-700 mb-6">
                <li>Provide accurate booking information</li>
                <li>Attend events you have booked or cancel in advance</li>
                <li>Follow event rules and guidelines</li>
                <li>Respect other attendees and event staff</li>
                <li>Pay all applicable fees on time</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Payment Terms</h2>
            <p class="text-gray-700 mb-6">
                All payments are processed securely through our payment partners. Event fees are non-refundable unless otherwise stated in the event's refund policy. We reserve the right to change our pricing at any time with notice.
            </p>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Intellectual Property</h2>
            <p class="text-gray-700 mb-6">
                The platform and its original content, features, and functionality are owned by us and are protected by international copyright, trademark, patent, trade secret, and other intellectual property laws.
            </p>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. Privacy</h2>
            <p class="text-gray-700 mb-6">
                Your privacy is important to us. Please review our Privacy Policy, which also governs your use of the platform, to understand our practices.
            </p>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">10. Prohibited Uses</h2>
            <p class="text-gray-700 mb-4">
                You may not use our platform:
            </p>
            <ul class="list-disc pl-6 text-gray-700 mb-6">
                <li>For any unlawful purpose or to solicit others to perform unlawful acts</li>
                <li>To violate any international, federal, provincial, or state regulations, rules, laws, or local ordinances</li>
                <li>To infringe upon or violate our intellectual property rights or the intellectual property rights of others</li>
                <li>To harass, abuse, insult, harm, defame, slander, disparage, intimidate, or discriminate</li>
                <li>To submit false or misleading information</li>
                <li>To upload or transmit viruses or any other type of malicious code</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">11. Termination</h2>
            <p class="text-gray-700 mb-6">
                We may terminate or suspend your account and bar access to the platform immediately, without prior notice or liability, under our sole discretion, for any reason whatsoever, including without limitation if you breach the Terms.
            </p>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">12. Disclaimer</h2>
            <p class="text-gray-700 mb-6">
                The information on this platform is provided on an "as is" basis. To the fullest extent permitted by law, we exclude all representations, warranties, conditions and terms relating to our platform and the use of this platform.
            </p>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">13. Limitation of Liability</h2>
            <p class="text-gray-700 mb-6">
                In no event shall we, nor our directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your use of the platform.
            </p>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">14. Governing Law</h2>
            <p class="text-gray-700 mb-6">
                These Terms shall be interpreted and governed by the laws of the jurisdiction in which we operate, without regard to its conflict of law provisions.
            </p>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">15. Changes to Terms</h2>
            <p class="text-gray-700 mb-6">
                We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material, we will provide at least 30 days notice prior to any new terms taking effect.
            </p>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">16. Contact Information</h2>
            <p class="text-gray-700 mb-6">
                If you have any questions about these Terms of Service, please contact us at:
            </p>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-700"><strong>Email:</strong> legal@eventhub.com</p>
                <p class="text-gray-700"><strong>Address:</strong> 123 Event Street, City, State 12345</p>
                <p class="text-gray-700"><strong>Phone:</strong> (555) 123-4567</p>
            </div>
        </div>
    </div>
</div>
@endsection
