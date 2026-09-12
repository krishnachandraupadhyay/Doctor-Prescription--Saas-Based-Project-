<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SystemSettingController extends Controller
{
    /**
     * Display the System Settings control panel.
     */
    public function index()
    {
        $settings = SystemSetting::getAll();

        // Common Timezones with GMT Offsets
        $timezones = [
            'Asia/Kolkata' => '(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi',
            'UTC' => '(GMT+00:00) UTC / Greenwich Mean Time',
            'Asia/Dubai' => '(GMT+04:00) Dubai, Abu Dhabi, Muscat',
            'Asia/Dhaka' => '(GMT+06:00) Dhaka',
            'Asia/Kathmandu' => '(GMT+05:45) Kathmandu',
            'Asia/Karachi' => '(GMT+05:00) Islamabad, Karachi, Tashkent',
            'Asia/Singapore' => '(GMT+08:00) Singapore, Kuala Lumpur',
            'Asia/Bangkok' => '(GMT+07:00) Bangkok, Hanoi, Jakarta',
            'Asia/Tokyo' => '(GMT+09:00) Tokyo, Osaka, Sapporo',
            'Europe/London' => '(GMT+00:00) London, Edinburgh, Dublin',
            'Europe/Paris' => '(GMT+01:00) Paris, Berlin, Rome, Madrid',
            'America/New_York' => '(GMT-05:00) Eastern Time (US & Canada)',
            'America/Chicago' => '(GMT-06:00) Central Time (US & Canada)',
            'America/Denver' => '(GMT-07:00) Mountain Time (US & Canada)',
            'America/Los_Angeles' => '(GMT-08:00) Pacific Time (US & Canada)',
            'Australia/Sydney' => '(GMT+10:00) Sydney, Melbourne, Canberra',
        ];

        $dateFormats = [
            'd M Y' => date('d M Y') . ' (e.g. 12 Sep 2026)',
            'd/m/Y' => date('d/m/Y') . ' (e.g. 12/09/2026)',
            'm/d/Y' => date('m/d/Y') . ' (e.g. 09/12/2026)',
            'Y-m-d' => date('Y-m-d') . ' (e.g. 2026-09-12)',
            'd-m-Y' => date('d-m-Y') . ' (e.g. 12-09-2026)',
            'M d, Y' => date('M d, Y') . ' (e.g. Sep 12, 2026)',
        ];

        $currencies = [
            'INR' => ['symbol' => '₹', 'name' => 'Indian Rupee (₹)'],
            'USD' => ['symbol' => '$', 'name' => 'US Dollar ($)'],
            'EUR' => ['symbol' => '€', 'name' => 'Euro (€)'],
            'GBP' => ['symbol' => '£', 'name' => 'British Pound (£)'],
            'AED' => ['symbol' => 'AED', 'name' => 'UAE Dirham (AED)'],
            'BDT' => ['symbol' => '৳', 'name' => 'Bangladeshi Taka (৳)'],
            'PKR' => ['symbol' => '₨', 'name' => 'Pakistani Rupee (₨)'],
            'NPR' => ['symbol' => 'रू', 'name' => 'Nepalese Rupee (रू)'],
            'SAR' => ['symbol' => 'SAR', 'name' => 'Saudi Riyal (SAR)'],
        ];

        $presetThemes = [
            ['name' => 'Valex Blue', 'color' => '#0162e8'],
            ['name' => 'Ocean Cyan', 'color' => '#0284c7'],
            ['name' => 'Emerald Health', 'color' => '#059669'],
            ['name' => 'Royal Violet', 'color' => '#7c3aed'],
            ['name' => 'Ruby Rose', 'color' => '#e11d48'],
            ['name' => 'Amber Gold', 'color' => '#d97706'],
            ['name' => 'Deep Indigo', 'color' => '#312e81'],
            ['name' => 'Slate Steel', 'color' => '#334155'],
        ];

        return view('backend.Admin.settings.index', compact('settings', 'timezones', 'dateFormats', 'currencies', 'presetThemes'));
    }

    /**
     * Update system settings.
     */
    public function update(Request $request)
    {
        $group = $request->input('group', 'general');

        if ($group === 'general') {
            $request->validate([
                'system_name' => 'required|string|max:100',
                'system_tagline' => 'nullable|string|max:200',
                'support_email' => 'nullable|email|max:150',
                'support_phone' => 'nullable|string|max:50',
                'footer_text' => 'nullable|string|max:300',
                'system_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
                'system_favicon' => 'nullable|image|mimes:jpeg,png,jpg,ico,webp|max:1024',
            ]);

            SystemSetting::set('system_name', $request->input('system_name'), 'general');
            SystemSetting::set('system_tagline', $request->input('system_tagline'), 'general');
            SystemSetting::set('support_email', $request->input('support_email'), 'general');
            SystemSetting::set('support_phone', $request->input('support_phone'), 'general');
            SystemSetting::set('footer_text', $request->input('footer_text'), 'general');

            // Handle Logo Upload
            if ($request->hasFile('system_logo')) {
                $uploadPath = public_path('uploads/settings');
                if (!File::isDirectory($uploadPath)) {
                    File::makeDirectory($uploadPath, 0777, true, true);
                }

                $file = $request->file('system_logo');
                $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);

                // Remove old logo if exists
                $oldLogo = SystemSetting::get('system_logo');
                if ($oldLogo && File::exists(public_path($oldLogo))) {
                    @File::delete(public_path($oldLogo));
                }

                SystemSetting::set('system_logo', 'uploads/settings/' . $filename, 'general');
            }

            // Handle Favicon Upload
            if ($request->hasFile('system_favicon')) {
                $uploadPath = public_path('uploads/settings');
                if (!File::isDirectory($uploadPath)) {
                    File::makeDirectory($uploadPath, 0777, true, true);
                }

                $file = $request->file('system_favicon');
                $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);

                // Remove old favicon if exists
                $oldFavicon = SystemSetting::get('system_favicon');
                if ($oldFavicon && File::exists(public_path($oldFavicon))) {
                    @File::delete(public_path($oldFavicon));
                }

                SystemSetting::set('system_favicon', 'uploads/settings/' . $filename, 'general');
            }
        } elseif ($group === 'appearance') {
            $request->validate([
                'theme_color' => ['required', 'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'],
                'theme_mode' => 'required|in:light,dark',
                'sidebar_color' => 'required|in:dark,light,gradient',
                'topbar_color' => 'required|in:light,dark',
            ]);

            SystemSetting::set('theme_color', strtolower($request->input('theme_color')), 'appearance');
            SystemSetting::set('theme_mode', $request->input('theme_mode'), 'appearance');
            SystemSetting::set('sidebar_color', $request->input('sidebar_color'), 'appearance');
            SystemSetting::set('topbar_color', $request->input('topbar_color'), 'appearance');
        } elseif ($group === 'localization') {
            $request->validate([
                'timezone' => 'required|string',
                'date_format' => 'required|string',
                'currency_symbol' => 'required|string|max:10',
                'currency_code' => 'required|string|max:10',
                'currency_position' => 'required|in:before,after',
            ]);

            SystemSetting::set('timezone', $request->input('timezone'), 'localization');
            SystemSetting::set('date_format', $request->input('date_format'), 'localization');
            SystemSetting::set('currency_symbol', $request->input('currency_symbol'), 'localization');
            SystemSetting::set('currency_code', $request->input('currency_code'), 'localization');
            SystemSetting::set('currency_position', $request->input('currency_position'), 'localization');
        } elseif ($group === 'security') {
            $request->validate([
                'min_password_length' => 'required|integer|min:6|max:32',
                'session_lifetime' => 'required|integer|min:15|max:1440',
                'max_login_attempts' => 'required|integer|min:3|max:20',
            ]);

            SystemSetting::set('min_password_length', $request->input('min_password_length'), 'security');
            SystemSetting::set('pwd_require_uppercase', $request->has('pwd_require_uppercase') ? '1' : '0', 'security');
            SystemSetting::set('pwd_require_number', $request->has('pwd_require_number') ? '1' : '0', 'security');
            SystemSetting::set('pwd_require_special', $request->has('pwd_require_special') ? '1' : '0', 'security');
            SystemSetting::set('session_lifetime', $request->input('session_lifetime'), 'security');
            SystemSetting::set('max_login_attempts', $request->input('max_login_attempts'), 'security');
        }

        return redirect()->route('admin.settings.index', ['tab' => $group])
            ->with('success', 'System settings updated successfully!');
    }
}
