<?php 

$locationMessage = "Tashkilot tanlaysizmi yoki lokatsiya yuborasizmi?!";
$locationKeyboard = json_encode([
	'inline_keyboard' => [
	    [["text" => "📍 Lokatsiya", "callback_data" => "location"],["text" => "🏢 Tashkilot", "callback_data" => "organization"]],
	]
]);

$paymentMessage = "To'lov turini tanlang?!";
$paymentKeyboard = json_encode([
	'inline_keyboard' => [
	    [["text" => "💵 Naqd", "callback_data" => "money"],["text" => "💳 Karta", "callback_data" => "card"]],
	]
]);