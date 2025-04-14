<?php
// Start session
session_start();

// Include database connection
include 'config/foodOrder.php';

// Include helper functions
include 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'เตี๋ยวเรือเจ๊เต้ย'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#F59E0B', // amber-500
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Sukhumvit Set', 'Prompt', -apple-system, BlinkMacSystemFont, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Minimal header for auth pages -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0">
                    <a href="index.php" class="flex items-center">
                        <i class="fas fa-utensils text-primary text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">เตี๋ยวเรือเจ๊เต้ย</span>
                        
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1"><?php // Main content will go here ?>