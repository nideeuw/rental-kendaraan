<?php
class ValidationHelper
{
    public static function validateName($value, $fieldName = "Nama", $maxLength = 100)
    {
        $errors = [];
        
        if (empty($value)) {
            $errors[] = "$fieldName harus diisi";
        } elseif (strlen($value) > $maxLength) {
            $errors[] = "$fieldName maksimal $maxLength karakter";
        } elseif (preg_match('/<script|javascript:|on\w+=/i', $value)) {
            $errors[] = "$fieldName mengandung karakter tidak diizinkan";
        }
        
        return $errors;
    }

    public static function validateEmail($value)
    {
        $errors = [];
        
        if (empty($value)) {
            $errors[] = "Email harus diisi";
        } elseif (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email tidak valid";
        }
        
        return $errors;
    }

    public static function validatePhone($value)
    {
        $errors = [];
        
        if (empty($value)) {
            $errors[] = "Nomor telepon harus diisi";
        } elseif (!preg_match('/^[0-9+\-\s()]+$/', $value)) {
            $errors[] = "Nomor telepon hanya boleh angka, +, -, spasi, ()";
        } elseif (strlen($value) < 10 || strlen($value) > 15) {
            $errors[] = "Nomor telepon harus 10-15 karakter";
        }
        
        return $errors;
    }

    public static function validatePositiveNumber($value, $fieldName = "Field", $min = 0, $max = null)
    {
        $errors = [];
        
        if (empty($value) && $value !== '0' && $value !== 0) {
            $errors[] = "$fieldName harus diisi";
        } elseif (!is_numeric($value)) {
            $errors[] = "$fieldName harus berupa angka";
        } elseif ($value < $min) {
            $errors[] = "$fieldName minimal $min";
        } elseif ($max !== null && $value > $max) {
            $errors[] = "$fieldName maksimal $max";
        }
        
        return $errors;
    }

    public static function validateDate($value, $fieldName = "Tanggal")
    {
        $errors = [];
        
        if (empty($value)) {
            $errors[] = "$fieldName harus diisi";
        } elseif (!strtotime($value)) {
            $errors[] = "Format $fieldName tidak valid";
        }
        
        return $errors;
    }

    public static function validateDateRange($startDate, $endDate, $startLabel = "Tanggal mulai", $endLabel = "Tanggal akhir")
    {
        $errors = [];
        
        if (strtotime($endDate) < strtotime($startDate)) {
            $errors[] = "$endLabel harus setelah $startLabel";
        }
        
        return $errors;
    }

    public static function validateEnum($value, $allowedValues, $fieldName = "Field")
    {
        $errors = [];
        
        if (empty($value)) {
            $errors[] = "$fieldName harus dipilih";
        } elseif (!in_array($value, $allowedValues)) {
            $errors[] = "$fieldName tidak valid";
        }
        
        return $errors;
    }

    public static function validateId($value, $fieldName = "ID", $required = true)
    {
        $errors = [];
        
        if ($required && (empty($value) || $value === '0')) {
            $errors[] = "$fieldName harus dipilih";
        } elseif (!empty($value) && !is_numeric($value)) {
            $errors[] = "$fieldName tidak valid";
        }
        
        return $errors;
    }

    public static function validatePlatNomor($value)
    {
        $errors = [];
        
        if (empty($value)) {
            $errors[] = "Plat nomor harus diisi";
        } elseif (!preg_match('/^[A-Z0-9\s-]+$/i', $value)) {
            $errors[] = "Plat nomor hanya boleh huruf, angka, spasi, dan strip";
        } elseif (strlen($value) > 15) {
            $errors[] = "Plat nomor maksimal 15 karakter";
        }
        
        return $errors;
    }

    public static function validateNoSim($value)
    {
        $errors = [];
        
        if (empty($value)) {
            $errors[] = "Nomor SIM harus diisi";
        } elseif (!preg_match('/^[A-Z0-9]+$/i', $value)) {
            $errors[] = "Nomor SIM hanya boleh huruf dan angka (tanpa spasi)";
        } elseif (strlen($value) < 12 || strlen($value) > 16) {
            $errors[] = "Nomor SIM harus 12-16 karakter";
        }
        
        return $errors;
    }

    public static function validateText($value, $fieldName = "Field", $required = true, $maxLength = 500)
    {
        $errors = [];
        
        if ($required && empty($value)) {
            $errors[] = "$fieldName harus diisi";
        } elseif (!empty($value) && strlen($value) > $maxLength) {
            $errors[] = "$fieldName maksimal $maxLength karakter";
        } elseif (!empty($value) && preg_match('/<script|javascript:|on\w+=/i', $value)) {
            $errors[] = "$fieldName mengandung karakter tidak diizinkan";
        }
        
        return $errors;
    }

    // Sanitize string untuk prevent XSS
    public static function sanitizeString($value)
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    public static function sanitizeEmail($value)
    {
        return filter_var(trim($value), FILTER_SANITIZE_EMAIL);
    }

    // Merge semua errors jadi satu string
    public static function formatErrors($errorsArray)
    {
        $allErrors = [];
        foreach ($errorsArray as $errors) {
            $allErrors = array_merge($allErrors, $errors);
        }
        return implode("<br>", $allErrors);
    }
}
?>