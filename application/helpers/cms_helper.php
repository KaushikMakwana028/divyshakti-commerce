<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Divy Shakti CMS & Legal Pages Helper
 * Manages default fallback content and database resolution for Privacy Policy,
 * Terms & Conditions, and Account Deletion guidelines.
 */

if (!function_exists('cms_get_page')) {
    /**
     * Retrieves page data (title, content, is_custom, updated_at).
     * If admin has saved custom content in DB, uses that.
     * Otherwise falls back to our rich, pre-built default content.
     *
     * @param string $slug 'privacy_policy' or 'terms_conditions'
     * @return array
     */
    function cms_get_page($slug)
    {
        $CI =& get_instance();
        if (!isset($CI->db)) {
            $CI->load->database();
        }
        $slug = trim(strtolower($slug));
        
        $page = null;
        if ($CI->db && $CI->db->table_exists('cms_pages')) {
            $page = $CI->db->get_where('cms_pages', ['slug' => $slug])->row();
        }

        $defaults = cms_get_default_content($slug);

        // Check if admin has provided non-empty custom content
        $raw_content = $page ? trim((string)$page->content) : '';
        $has_custom = !empty($raw_content) && strlen(strip_tags($raw_content)) > 20;

        if ($has_custom) {
            return [
                'slug'        => $slug,
                'title'       => !empty($page->title) ? $page->title : $defaults['title'],
                'content'     => $page->content,
                'is_custom'   => true,
                'updated_at'  => $page->updated_at ?? date('Y-m-d H:i:s')
            ];
        }

        // Return rich default content
        return [
            'slug'        => $slug,
            'title'       => $defaults['title'],
            'content'     => $defaults['content'],
            'is_custom'   => false,
            'updated_at'  => $page->updated_at ?? date('Y-m-d H:i:s')
        ];
    }
}

if (!function_exists('cms_get_default_content')) {
    /**
     * Returns rich, structured, professional default HTML content for legal pages.
     *
     * @param string $slug
     * @return array ['title' => string, 'content' => string]
     */
    function cms_get_default_content($slug)
    {
        $year = date('Y');

        if ($slug === 'privacy_policy') {
            return [
                'title' => 'Privacy Policy',
                'content' => <<<HTML
<h2>1. Introduction</h2>
<p>Welcome to <strong>Divy Shakti</strong> ("we", "our", or "us"). We value the trust you place in us when you use our mobile application and website to browse and purchase religious idols, puja items, and related spiritual products. This Privacy Policy explains what information we collect, how we use it, and the choices you have regarding your data.</p>

<h2>2. Information We Collect</h2>
<p>We collect necessary information to process your orders and provide a seamless shopping experience:</p>
<ul>
    <li><strong>Account Information:</strong> Full name, email address, mobile phone number, and optional profile photo.</li>
    <li><strong>Delivery Information:</strong> Recipient name, complete shipping address, landmark, city, state, pincode, and contact number.</li>
    <li><strong>Order &amp; Transaction Details:</strong> Products viewed, items added to cart, purchase history, payment status, and order totals.</li>
    <li><strong>Identity Verification:</strong> Mobile phone number and One-Time Passwords (OTP) used to securely log you into your account.</li>
    <li><strong>Technical Information:</strong> Device type, IP address, and browser information used solely to maintain account security and prevent fraudulent access.</li>
</ul>

<h2>3. How We Use Your Information</h2>
<p>We use the collected information for the following purposes:</p>
<ul>
    <li>Authenticating your account via secure mobile OTP.</li>
    <li>Processing, packing, and delivering your orders through reliable courier partners.</li>
    <li>Providing order status notifications, shipping updates, and invoice receipts.</li>
    <li>Offering customer support and resolving queries or order issues promptly.</li>
    <li>Preventing security breaches, fraud, and unauthorized account access.</li>
</ul>

<h2>4. Data Protection &amp; Security</h2>
<p>We implement industry-standard security measures to safeguard your personal data:</p>
<ul>
    <li>All sensitive data transmissions are protected using encrypted SSL / TLS protocols.</li>
    <li>Account passwords and security credentials are encrypted using one-way cryptographic hashing.</li>
    <li>Your personal details are stored on secure servers with restricted administrative access.</li>
    <li>We do not sell, rent, or trade your personal information to third parties for third-party marketing.</li>
</ul>

<h2>5. Account Deletion &amp; Data Erasure</h2>
<p>You have full authority to request the permanent deletion of your account and personal records at any time.</p>
<p>You may initiate account deletion directly from the <strong>Delete Account</strong> link on our website or inside the Divy Shakti Mobile Application under <em>Settings &gt; Privacy &gt; Delete Account</em>.</p>
<p>Upon confirmation, your profile data, addresses, and active cart will be permanently deleted from active systems.</p>

<h2>6. Updates to This Policy</h2>
<p>We may update this Privacy Policy from time to time. Any changes will be posted on this page with an updated revision date.</p>

<h2>7. Contact &amp; Support</h2>
<p>If you have any questions or concerns regarding this Privacy Policy, please contact our support desk:</p>
<ul>
    <li><strong>Business Name:</strong> Divy Shakti</li>
    <li><strong>Email:</strong> support@divyshakti.com</li>
    <li><strong>Helpline:</strong> +91 8160348894</li>
</ul>
HTML
            ];
        }

        // Terms and Conditions default
        return [
            'title' => 'Terms and Conditions',
            'content' => <<<HTML
<h2>1. Acceptance of Terms</h2>
<p>By accessing, browsing, or using the <strong>Divy Shakti</strong> website or mobile application, you agree to comply with and be bound by these Terms and Conditions. Please review them carefully before placing an order.</p>

<h2>2. User Account &amp; Security</h2>
<p>To use certain features or place orders, you may register an account using your mobile number:</p>
<ul>
    <li>You agree to provide accurate and complete personal details during registration.</li>
    <li>You are responsible for maintaining the confidentiality of your account credentials and OTP codes.</li>
    <li>Notify us immediately if you suspect unauthorized use of your account.</li>
</ul>

<h2>3. Products, Pricing &amp; Orders</h2>
<p>We strive to provide accurate product descriptions, pricing, and availability:</p>
<ul>
    <li>All prices are listed in Indian Rupees (INR, ₹) and include applicable taxes unless stated otherwise.</li>
    <li>We reserve the right to correct pricing errors or cancel orders resulting from inadvertent typographical errors.</li>
    <li>Orders are confirmed once payment is verified and are dispatched through vetted logistics services.</li>
</ul>

<h2>4. Digital Wallet &amp; Payments</h2>
<ul>
    <li>Your Divy Shakti Wallet stores funds for purchases and transaction refunds.</li>
    <li>Deposit requests submitted via bank transfer or UPI are verified by our team prior to wallet credit.</li>
    <li>Wallet funds are non-transferable to third parties outside authorized platform mechanisms.</li>
</ul>

<h2>5. Cancellation &amp; Returns Policy</h2>
<ul>
    <li><strong>Cancellation:</strong> You may cancel an order before it has been marked as packed or dispatched. The paid amount will be credited back to your wallet.</li>
    <li><strong>Damaged or Incorrect Items:</strong> If you receive a damaged product, report it within 48 hours of delivery with photo evidence to initiate a replacement or refund.</li>
</ul>

<h2>6. Prohibited Activities</h2>
<p>Users agree not to engage in fraudulent orders, platform tampering, unauthorized access attempts, or distribution of harmful software on our systems.</p>

<h2>7. Account Deletion</h2>
<p>Users may permanently delete their account at any time via the <strong>Delete Account</strong> portal. Deleting your account permanently cancels any remaining wallet balance and cannot be reversed.</p>

<h2>8. Governing Law &amp; Jurisdiction</h2>
<p>These Terms and Conditions shall be governed by and interpreted in accordance with the laws of India. Any legal disputes shall be subject to the jurisdiction of the competent courts in Gujarat, India.</p>
HTML
        ];
    }
}
