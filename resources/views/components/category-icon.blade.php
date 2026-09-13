@php
    $name = trim((string) ($icon ?? '🔖'));
    $icons = [
        'utensils' => '🍔',
        'coffee' => '☕',
        'banknote' => '💰',
        'shirt' => '👕',
        'paw-print' => '🐾',
        'shopping-bag' => '🛒',
        'plane' => '✈️',
        'dumbbell' => '🎯',
        'gamepad-2' => '🎮',
        'gift' => '🎁',
        'apple' => '🍎',
        'heart-pulse' => '🏥',
        'car' => '🚗',
        'laptop' => '💻',
        'trending-up' => '📊',
        'plus-circle' => '💰',
        'circle' => '🔖',
    ];
@endphp

<span aria-hidden="true">{{ $icons[$name] ?? $name ?: '🔖' }}</span>
