@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Privacy Policy</h1>
        
        <div class="prose max-w-none">
            <p class="text-gray-600 mb-6">Last updated: {{ date('F d, Y') }}</p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Information We Collect</h2>
            <p class="text-gray-700 mb-4">
                We collect the following types of information when you use our event management platform:
            </p>
            <ul class="list-disc pl-6 text-gray-700 mb-6">
                <li><strong>Personal Information:</strong> Name, email address, phone number, and bio when you create an account</li>
                <li><strong>Authentication Data:</strong> Password (hashed and securely stored) for account access</li>
                <li><strong>Event Participation Data:</strong> Booking history, event attendance records, and special requirements</li>
                <li><strong>Event Creation Data:</strong> Event details, descriptions, and images when you create events (for organizers)</li>
                <li><strong>Usage Data:</strong> Information about how you interact with our platform</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Why We Collect This Information</h2>
            <p class="text-gray-700 mb-4">
                We use your information for the following purposes:
            </p>
            <ul class="list-disc pl-6 text-gray-700 mb-6">
                <li><strong>Account Management:</strong> To create and maintain your user account</li>
                <li><strong>Event Participation:</strong> To process bookings and manage event attendance</li>
                <li><strong>Communication:</strong> To send you important updates about events you've booked or created</li>
                <li><strong>Platform Improvement:</strong> To analyze usage patterns and improve our services</li>
                <li><strong>Security:</strong> To protect against fraud and unauthorized access</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. How We Store and Protect Your Data</h2>
            <p class="text-gray-700 mb-4">
                We implement industry-standard security measures to protect your information:
            </p>
            <ul class="list-disc pl-6 text-gray-700 mb-6">
                <li><strong>Password Security:</strong> All passwords are hashed using secure algorithms</li>
                <li><strong>Data Encryption:</strong> Sensitive data is encrypted both in transit and at rest</li>
                <li><strong>Access Control:</strong> Strict access controls limit who can view your personal information</li>
                <li><strong>Regular Updates:</strong> We regularly update our security measures to address new threats</li>
                <li><strong>Secure Servers:</strong> Your data is stored on secure, monitored servers</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Your Rights</h2>
            <p class="text-gray-700 mb-4">
                You have the following rights regarding your personal data:
            </p>
            <ul class="list-disc pl-6 text-gray-700 mb-6">
                <li><strong>Access:</strong> You can view all personal information we have about you</li>
                <li><strong>Correction:</strong> You can update or correct your personal information at any time</li>
                <li><strong>Deletion:</strong> You can request deletion of your account and associated data</li>
                <li><strong>Portability:</strong> You can request a copy of your data in a portable format</li>
                <li><strong>Withdrawal:</strong> You can withdraw consent for data processing at any time</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Data Sharing</h2>
            <p class="text-gray-700 mb-6">
                We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except:
            </p>
            <ul class="list-disc pl-6 text-gray-700 mb-6">
                <li>When required by law or legal process</li>
                <li>To protect our rights, property, or safety</li>
                <li>With service providers who assist in platform operations (under strict confidentiality agreements)</li>
                <li>In case of business transfer (with prior notice)</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Cookies and Tracking</h2>
            <p class="text-gray-700 mb-6">
                We use cookies and similar technologies to enhance your experience, remember your preferences, and analyze platform usage. You can control cookie settings through your browser preferences.
            </p>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Data Retention</h2>
            <p class="text-gray-700 mb-6">
                We retain your personal information only as long as necessary to provide our services and comply with legal obligations. When you delete your account, we will remove your personal data within 30 days, except where retention is required by law.
            </p>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Children's Privacy</h2>
            <p class="text-gray-700 mb-6">
                Our platform is not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13.
            </p>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. Changes to This Policy</h2>
            <p class="text-gray-700 mb-6">
                We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date.
            </p>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">10. Contact Us</h2>
            <p class="text-gray-700 mb-6">
                If you have any questions about this Privacy Policy or our data practices, please contact us at:
            </p>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-700"><strong>Email:</strong> privacy@eventhub.com</p>
                <p class="text-gray-700"><strong>Address:</strong> 123 Event Street, City, State 12345</p>
                <p class="text-gray-700"><strong>Phone:</strong> (555) 123-4567</p>
            </div>
        </div>
    </div>
</div>
@endsection
