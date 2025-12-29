<?php

namespace App\Helpers;

class SlugHelper
{
    /**
     * Convert Persian/Farsi text to Persian slug (keeps Persian characters)
     */
    public static function persianSlug($text)
    {
        if (empty($text)) {
            return 'ad';
        }
        
        // Normalize Persian/Arabic characters
        // Convert Arabic characters to Persian equivalents
        $arabicToPersian = [
            'ي' => 'ی',
            'ك' => 'ک',
            'ة' => 'ه',
            'إ' => 'ا',
            'أ' => 'ا',
            'آ' => 'آ',
        ];
        $text = strtr($text, $arabicToPersian);
        
        // Replace spaces and special characters with dash
        $text = preg_replace('/[\s_\.\/\\\\]+/', '-', $text);
        
        // Remove special characters but keep Persian, Arabic, English letters and numbers
        // Persian Unicode range: \x{0600}-\x{06FF}
        // Arabic Unicode range: \x{0600}-\x{06FF} (overlaps with Persian)
        // English: a-zA-Z
        // Numbers: 0-9 and Persian/Arabic numbers
        $text = preg_replace('/[^\x{0600}-\x{06FF}a-zA-Z0-9۰-۹٠-٩\-]/u', '', $text);
        
        // Normalize Persian/Arabic numbers to Persian
        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $text = str_replace($arabicNumbers, $persianNumbers, $text);
        
        // Remove multiple consecutive dashes
        $text = preg_replace('/-+/', '-', $text);
        
        // Trim dashes from start and end
        $text = trim($text, '-');
        
        // If empty after processing, return default
        return $text ?: 'ad';
    }
}

