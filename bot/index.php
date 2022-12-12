<?php 
    ini_set('display_errors', true);
    define('YII_DEBUG', true);

    include "lib/db.php";
    include "lib/keyboard.php";

    const LOCATION_TYPE = 1;
    const ORGANIZATION_TYPE = 2;

    const PAYMENT_CARD = "your_payment_card";

    const SUPERADMIN = your_chat_id;
    const BOT_API = "your_bot_api";
    const IMAGE_LINK = "your_img_file_path";

    function bot($method, $data = []) {
        $url = "https://api.telegram.org/bot".BOT_API.'/'.$method;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $res = curl_exec($ch);

        if(curl_error($ch)){
            var_dump(curl_error($ch));
        } else {
            return json_decode($res);
        }
    }
    function typing($ch) {
        return bot('sendChatAction', [
            'chat_id' => $ch,
            'action' => 'typing'
        ]);
    }

    $update = file_get_contents('php://input');
    $update = json_decode($update);
    $message = $update->message;
    if (isset($update->callback_query)) {
        $callback_id = $update->callback_query->id;
        $chat_id = $update->callback_query->message->chat->id;
        $message_id = $update->callback_query->message->message_id;
        $realname = $update->callback_query->message->chat->first_name;
        $data = $update->callback_query->data;
        $nameuser = "";
    } else {
        $chat_id = $message->chat->id;
        $message_id = $message->message_id;
        $realname = $message->chat->first_name;
        $text = $message->text;
        $photo = $message->photo;
        $nameuser = $message->from->username;
        $phone_number = $message->contact->phone_number;
    }

    function sendMessage($chat_id, $text, $keyboard) {
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'html',
            'reply_markup' => $keyboard
        ]);
    }

    function sendMessageNo($chat_id, $text) {
        $reply_markup = array(
            'remove_keyboard' => true
        ); $noKeyboard = json_encode($reply_markup);

        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'html',
            'reply_markup' => $noKeyboard
        ]);
    }

    function deleteMessage($chat_id, $message_id) {
        bot('deleteMessage', [
            'chat_id' => $chat_id,
            'message_id' => $message_id
        ]);
    }

    function errorMessage($chat_id) {
        $message = "Noto'g'ri qiymat kiritdingiz!";
        sendMessageNo($chat_id, $message);
    }

    function updateStep($chat_id, $step_1, $step_2, $conn) {
        $checkAction = 'SELECT * FROM action WHERE chat_id = '.$chat_id;
        $resultAction = $conn->query($checkAction);
        if ($resultAction->num_rows > 0){
            $sql = 'UPDATE action SET step_1 = '.$step_1.', step_2 = '.$step_2.' WHERE chat_id = '.$chat_id; 
        } else {
            $sql = 'INSERT INTO action (chat_id, step_1, step_2) VALUES ('.$chat_id.', '.$step_1.', '.$step_2.')';
        }
        $conn->query($sql);
    }

    function getUserId($chat_id, $conn) {
        $getUser = "SELECT * FROM user WHERE chat_id = $chat_id";
        $resultUser = $conn->query($getUser);
        $user = $resultUser->fetch_assoc();
        $user_id = $user['id'];
        return $user_id;
    }

    function saveUnrealOrder($chat_id, $meal_id, $conn) {
        $checkUser = "SELECT * FROM orders_unreal WHERE chat_id = $chat_id";
        $resultUser = $conn->query($checkUser);
        if ($resultUser->num_rows > 0) {
            $sql = "UPDATE orders_unreal SET meal_id = $meal_id, count = 1 WHERE chat_id = $chat_id";
        } else {
            $sql = "INSERT INTO orders_unreal (chat_id, meal_id, count) VALUES ($chat_id, $meal_id, 1)";
        }
        $conn->query($sql);
    }

    function getUnrealOrder($chat_id, $message_id, $meal_id, $conn) {
        $getMeal = "SELECT m.title, o.count, m.price, m.img FROM orders_unreal AS o
                    INNER JOIN meals AS m ON o.meal_id = m.id
                    WHERE o.meal_id = $meal_id";
        $resultMeal = $conn->query($getMeal);
        if ($resultMeal->num_rows > 0) {
            $meal = $resultMeal->fetch_assoc();
            $meal_title = $meal['title'];
            $meal_count = $meal['count'];
            $meal_price = $meal['price'];
            $meal_image = $meal['img'];
            $total_price = $meal_price * $meal_count;

            $message = "🍽 Taom: $meal_id\n\n<b>Nomi:</b> $meal_title\n<b>Narxi:</b> $total_price so'm\n\n🍞Non va salat albatta bepul!";
            $keyboard = json_encode([
                'inline_keyboard' => [
                    [["text" => "➖", "callback_data" => "minus_".$meal_id], ["text" => $meal_count, "callback_data" => "save_order"], ["text" => "➕", "callback_data" => "plus_".$meal_id]],
                    [["text" => "😋 Savatga", "callback_data" => "save_order"]],
                    [["text" => "🔙 Orqaga", "callback_data" => "back"]],
                ]
            ]);
            bot('editMessageCaption', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'caption' => $message,
                'parse_mode' => 'html',
                'reply_markup' => $keyboard
            ]);
        }
    }

    function plusMealCount($chat_id, $message_id, $meal_id, $conn) {
        $getUnrealOrder = "SELECT * FROM orders_unreal WHERE meal_id = $meal_id AND chat_id = $chat_id";
        $resultUnrealOrder = $conn->query($getUnrealOrder);
        if ($resultUnrealOrder->num_rows > 0) {
            $order = $resultUnrealOrder->fetch_assoc();
            $order_count = $order['count'];
            $new_count = $order_count + 1;

            $updateUnrealOrder = "UPDATE orders_unreal SET count = $new_count WHERE meal_id = $meal_id AND chat_id = $chat_id";
            $conn->query($updateUnrealOrder);

            getUnrealOrder($chat_id, $message_id, $meal_id, $conn);
        }
    }

    function minusMealCount($chat_id, $message_id, $meal_id, $conn) {
        $getUnrealOrder = "SELECT * FROM orders_unreal WHERE meal_id = $meal_id AND chat_id = $chat_id";
        $resultUnrealOrder = $conn->query($getUnrealOrder);
        if ($resultUnrealOrder->num_rows > 0) {
            $order = $resultUnrealOrder->fetch_assoc();
            $order_count = $order['count'];
            if ($order_count > 1) {
                $new_count = $order_count - 1;

                $updateUnrealOrder = "UPDATE orders_unreal SET count = $new_count WHERE meal_id = $meal_id AND chat_id = $chat_id";
                $conn->query($updateUnrealOrder);

                getUnrealOrder($chat_id, $message_id, $meal_id, $conn);
            }
        }
    }

    function showCard($chat_id, $user_id, $date, $conn) {
        $getMeal = 'SELECT m.title, m.price, o.count FROM meals AS m
                    INNER JOIN orders AS o ON o.meal_id = m.id
                    WHERE o.user_id = '.$user_id.' AND o.date = "'.$date.'" AND o.status = 0';
        $resultMeal = $conn->query($getMeal);
        if ($resultMeal->num_rows > 0) {
            $message = "📥 Savatcha:\n\n";
            $total = 0;
            while ($meal = $resultMeal->fetch_assoc()) {
                $meal_title = $meal['title'];
                $meal_price = $meal['price'];
                $meal_count = $meal['count'];
                $total_price = $meal_price * $meal_count;
                $total += $total_price;

                $message .= "<b>$meal_title:</b> $meal_count\n<b>Narxi:</b> $total_price so'm\n\n";
            }

            $message .= "<b>Umumiy: $total so'm</b>\n\n🔹 Yana taom qo'shishingiz mumkin!";
            $keyboard = json_encode([
                'inline_keyboard' => [
                    [["text" => "😋 Yana qo'shish", "callback_data" => "menu"], ["text" => "🔄 Tozalash", "callback_data" => "clear"]],
                    [["text" => "🛍 Buyutma qilish", "callback_data" => "order"]],
                ]
            ]);
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => $message,
                'parse_mode' => 'html',
                'reply_markup' => $keyboard
            ]);
        } else {
            $message = "🗑 Savatcha bo'sh!";
            $keyboard = json_encode([
                'inline_keyboard' => [
                    [["text" => "🔙 Orqaga", "callback_data" => "back"]],
                ]
            ]);
            sendMessage($chat_id, $message, $keyboard);
        }
    }

    function clearCard($chat_id, $user_id, $date, $conn) {
        $clearOrders = 'DELETE FROM orders WHERE user_id = '.$user_id.' AND date = "'.$date.'" AND status = 0';
        $conn->query($clearOrders);
    }

    function saveOrder($chat_id, $conn) {
        $getUnrealOrder = "SELECT * FROM orders_unreal WHERE chat_id = $chat_id";
        $resultUnrealOrder = $conn->query($getUnrealOrder);
        if ($resultUnrealOrder->num_rows > 0) {
            $unreal_order = $resultUnrealOrder->fetch_assoc();
            $meal_id = $unreal_order['meal_id'];
            $meal_count = $unreal_order['count'];

            $user_id = getUserId($chat_id, $conn);
            $date = date('Y-m-d');

            $checkMealOrder = 'SELECT * FROM orders WHERE meal_id = '.$meal_id.' AND user_id = '.$user_id.' AND date = "'.$date.'" AND status = 0';
            $resultMealOrder = $conn->query($checkMealOrder);
            if ($resultMealOrder->num_rows > 0) {
                $real_order = $resultMealOrder->fetch_assoc();
                $order_count = $real_order['count'] + $meal_count;

                $saveOrder = 'UPDATE orders SET count = '.$order_count.' WHERE meal_id = '.$meal_id.' AND user_id = '.$user_id.' AND date = "'.$date.'" AND status = 0';
            } else {
                $saveOrder = 'INSERT INTO orders (user_id, meal_id, count, date) VALUES ('.$user_id.', '.$meal_id.', '.$meal_count.', "'.$date.'")';
            }
            $conn->query($saveOrder);

            showCard($chat_id, $user_id, $date, $conn);
        }
    }

    function getOrganization($chat_id, $conn) {
        $sql = "SELECT * FROM organizations WHERE status = 1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $i = 1;
            $menu = [];
            $array = [];
            $message = "Tashkilotlar ro'yxati:\n\n";
            while ($row = $result->fetch_assoc()) {
                $organ_id = $row['id'];
                $organ_title = $row['title'];

                $message .= $i . ". " . $organ_title . PHP_EOL;
                $menu[] = ['callback_data' => $organ_id, 'text' => $i];

                if ($result->num_rows % 2 == 0) {
                    if ($result->num_rows % 5 == 0) {
                        if ($i % 5 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($result->num_rows % 3 == 0) {
                        if ($i % 3 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($result->num_rows % 8 == 0) {
                        if ($i % 8 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($result->num_rows % 7 == 0) {
                        if ($i % 7 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($result->num_rows % 6 == 0) {
                        if ($i % 6 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($result->num_rows % 10 == 0) {
                        if ($i % 10 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($result->num_rows % 4 == 0) {
                        if ($i % 4 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($result->num_rows % 9 == 0) {
                        if ($i % 9 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($result->num_rows % 2 == 0) {
                        if ($i % 2 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    }
                } else {
                    if ($result->num_rows % 9 == 0) {
                        if ($i % 9 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        } 
                    } else if ($result->num_rows % 7 == 0) {
                        if ($i % 7 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($result->num_rows % 5 == 0) {
                        if ($i % 5 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($result->num_rows % 3 == 0) {
                        if ($i % 3 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else {
                        if ($i % 2 == 1) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    }
                }
                $i++;
            }

            $array[] = [['callback_data' => "back", 'text'=> "🔙 Orqaga"]];

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => $message,
                'parse_mode' => 'markdown',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $array
                ])
            ]);
        } else {
            $message = "🗑 Tashkilotlar ro'yxati bo'sh!";
            $keyboard = json_encode([
                'inline_keyboard' => [
                    [["text" => "🔙 Orqaga", "callback_data" => "back"]],
                ]
            ]);
            sendMessage($chat_id, $message, $keyboard);
        }
    }

    function getMenu($chat_id, $conn) {
        $today = date('Y-m-d');
        $getMenu = 'SELECT 
                        d.day, m.id, m.title, m.img, m.price 
                    FROM days AS d
                    INNER JOIN daily_menu AS dm ON d.id = dm.day_id
                    INNER JOIN meals AS m ON dm.meal_id = m.id
                    WHERE d.day = "'.$today.'" AND dm.status = 1';
        $resultMenu = $conn->query($getMenu);
        if ($resultMenu->num_rows > 0) {
            $i = 1;
            $menu = [];
            $array = [];
            $images = [];
            $message = "Assalomu alaykum 🤗\n\n🍽 Taomnoma:\n";
            while ($row = $resultMenu->fetch_assoc()) {
                $meal_id = $row['id'];
                $meal_title = $row['title'];
                $meal_price = $row['price'];
                $meal_image = IMAGE_LINK.$row['img'];

                $message .= "🫕 " . $i . ". " . $meal_title . " : " . $meal_price . " so'm" . PHP_EOL;
                $menu[] = ['callback_data' => $meal_id."_".($resultMenu->num_rows + 1), 'text' => $i];

                if ($resultMenu->num_rows % 2 == 0) {
                    if ($resultMenu->num_rows % 5 == 0) {
                        if ($i % 5 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($resultMenu->num_rows % 3 == 0) {
                        if ($i % 3 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($resultMenu->num_rows % 8 == 0) {
                        if ($i % 8 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($resultMenu->num_rows % 7 == 0) {
                        if ($i % 7 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($resultMenu->num_rows % 6 == 0) {
                        if ($i % 6 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($resultMenu->num_rows % 10 == 0) {
                        if ($i % 10 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($resultMenu->num_rows % 4 == 0) {
                        if ($i % 4 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($resultMenu->num_rows % 9 == 0) {
                        if ($i % 9 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($resultMenu->num_rows % 2 == 0) {
                        if ($i % 2 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    }
                } else {
                    if ($resultMenu->num_rows % 9 == 0) {
                        if ($i % 9 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        } 
                    } else if ($resultMenu->num_rows % 7 == 0) {
                        if ($i % 7 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($resultMenu->num_rows % 5 == 0) {
                        if ($i % 5 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else if ($resultMenu->num_rows % 3 == 0) {
                        if ($i % 3 == 0) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    } else {
                        if ($i % 2 == 1) {
                            $array[] = $menu;
                            $menu = [];
                        }
                    }
                }

                if ($i == $resultMenu->num_rows) {
                    $message .= "\n\n🍞Non va salat albatta bepul!";
                    $images[] = ['type' => 'photo', 'media' => $meal_image, 'caption' => $message, 'parse_mode' => 'html'];
                } else {
                    $images[] = ['type' => 'photo', 'media' => $meal_image];
                }

                $i++;
            }

            bot('sendMediaGroup', [
                'chat_id' => $chat_id,
                'media' => json_encode($images),
            ]);

            $array[] = [['callback_data' => "back_".$i, 'text'=> "🔙 Orqaga"]];

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Buyurtma bermoqchi bo'lgan ovqatingizni tanlang!",
                'parse_mode' => 'markdown',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $array
                ])
            ]);
        } else {
            $message = "🗑 Bugungi menyu bo'sh!";
            $keyboard = json_encode([
                'inline_keyboard' => [
                    [["text" => "🔙 Orqaga", "callback_data" => "back_1"]],
                ]
            ]);
            sendMessage($chat_id, $message, $keyboard);
        }
    }

    function getMeal($chat_id, $meal_id, $conn) {
        $getMeal = "SELECT * FROM meals WHERE id = $meal_id";
        $resultMeal = $conn->query($getMeal);
        if ($resultMeal->num_rows > 0) {
            saveUnrealOrder($chat_id, $meal_id, $conn);
            $meal = $resultMeal->fetch_assoc();
            $meal_title = $meal['title'];
            $meal_price = $meal['price'];
            $meal_image = $meal['img'];

            $message = "🍽 Taom: $meal_id\n\n<b>Nomi:</b> $meal_title\n<b>Narxi:</b> $meal_price so'm\n\n🍞Non va salat albatta bepul!";
            $keyboard = json_encode([
                'inline_keyboard' => [
                    [["text" => "➖", "callback_data" => "minus_".$meal_id], ["text" => "1", "callback_data" => "save_order"], ["text" => "➕", "callback_data" => "plus_".$meal_id]],
                    [["text" => "😋 Savatga", "callback_data" => "save_order"]],
                    [["text" => "🔙 Orqaga", "callback_data" => "back"]],
                ]
            ]);
            bot('sendPhoto', [
                'chat_id' => $chat_id,
                'photo' => IMAGE_LINK.$meal_image,
                'caption' => $message,
                'parse_mode' => 'html',
                'reply_markup' => $keyboard
            ]);
        } else {
            $message = "🗑 Taom topilmadi!";
            $keyboard = json_encode([
                'inline_keyboard' => [
                    [["text" => "🔙 Orqaga", "callback_data" => "back"]],
                ]
            ]);
            sendMessage($chat_id, $message, $keyboard);
        }
    }

    function welcomeAuth($chat_id, $conn) {
        $message = "Telefon raqamingizni kiriting!\n(998901234567)";
        $keyboard = json_encode([
            'keyboard' => [
                [
                    [
                        'text' => "📞 Raqam jo'natish",
                        'request_contact' => true
                    ],
                ],
            ],
            'resize_keyboard' => true
        ]);
        sendMessage($chat_id, $message, $keyboard);
    }

    function welcomeMenu($chat_id, $conn) {
        $message = "Bosh menyuga xush kelibsiz!";
        $keyboard = json_encode([
            'inline_keyboard' => [
                [["text" => "🛒 Buyurtma berish", "callback_data" => "order"]],
                [["text" => "📥 Savatcha", "callback_data" => "card"], ["text" => "💸 Qarzlarim", "callback_data" => "debts"]],
            ]
        ]);
        sendMessage($chat_id, $message, $keyboard);
    }

    function checkUser($chat_id, $conn) {
        $checkUser = "SELECT * FROM user WHERE chat_id = $chat_id AND status = 1";
        $resultUser = $conn->query($checkUser);
        if ($resultUser->num_rows > 0) {
            updateStep($chat_id, 1, 0, $conn);
            welcomeMenu($chat_id, $conn);
        } else {
            updateStep($chat_id, 0, 0, $conn);
            welcomeAuth($chat_id, $conn);
        }
    }

    function activateUser($chat_id, $conn) {
        $sql = "UPDATE user SET status = 1 WHERE chat_id = $chat_id";
        $conn->query($sql);
    }

    function saveUserLocation($chat_id, $latitude, $longitude, $conn) {
        $user = "SELECT * FROM user WHERE chat_id = $chat_id AND status = 0";
        $resultUser = $conn->query($user);
        $row = $resultUser->fetch_assoc();
        $user_id = $row['id'];

        $checkLocation = "SELECT * FROM user_location WHERE user_id = $user_id";
        $resultLocation = $conn->query($checkLocation);
        if ($resultLocation->num_rows > 0) {
            $sql = 'UPDATE user_location SET latitude = '.$latitude.', longitude = '.$longitude.' WHERE user_id = '.$user_id;
        } else {
            $sql = 'INSERT INTO user_location (user_id, longitude, latitude) VALUES ('.$user_id.', "'.$longitude.'", "'.$latitude.'")';
        }
        $conn->query($sql);
        if ($conn) {
            $message = "Lokatsiyangiz kiritildi!";
            sendMessageNo($chat_id, $message);
            activateUser($chat_id, $conn);
        }
    }

    function saveUserOrganization($chat_id, $organ_id, $conn) {
        $user = "SELECT * FROM user WHERE chat_id = $chat_id AND status = 0";
        $resultUser = $conn->query($user);
        $row = $resultUser->fetch_assoc();
        $user_id = $row['id'];

        $checkOrganization = "SELECT * FROM user_organization WHERE user_id = $user_id";
        $resultOrganization = $conn->query($checkOrganization);
        if ($resultOrganization->num_rows > 0) {
            $sql = 'UPDATE user_organization SET organ_id = '.$organ_id.' WHERE user_id = '.$user_id;
        } else {
            $sql = 'INSERT INTO user_organization (user_id, organ_id) VALUES ('.$user_id.', '.$organ_id.')';
        }
        $conn->query($sql);
        if ($conn) {
            activateUser($chat_id, $conn);
        }
    }

    function saveUser($chat_id, $table, $value, $conn) {
        $checkUser = "SELECT * FROM user WHERE chat_id = $chat_id AND status = 0";
        $resultUser = $conn->query($checkUser);
        if ($resultUser->num_rows > 0) {
            $sql = 'UPDATE user SET '.$table.' = "'.$value.'" WHERE chat_id = '.$chat_id;
        } else {
            $sql = 'INSERT INTO user (chat_id, '.$table.') VALUES ('.$chat_id.', "'.$value.'")';
        }
        $conn->query($sql);
    }

    function addressLog($chat_id, $address_id, $address_type, $conn) {
        $checkAddress = "SELECT * FROM address_log WHERE chat_id = $chat_id AND status = 1";
        $resultAddress = $conn->query($checkAddress);
        if ($resultAddress->num_rows > 0) {
            $sql = "UPDATE address_log SET address_id = $address_id, address_type = $address_type WHERE chat_id = $chat_id AND status = 1";
        } else {
            $sql = "INSERT INTO address_log (chat_id, address_id, address_type) VALUES ($chat_id, $address_id, $address_type)";
        }
        $conn->query($sql);
    }

    function checkAddress($chat_id, $conn) {
        $checkLocation = "SELECT ul.id, ul.latitude, ul.longitude FROM user AS u 
                        INNER JOIN user_location AS ul ON u.id = ul.user_id
                        WHERE u.chat_id = $chat_id AND NOT ul.status = 0";
        $resultLocation = $conn->query($checkLocation);
        if ($resultLocation->num_rows > 0) {
            $location = $resultLocation->fetch_assoc();
            $location_id = $location['id'];
            $latitude = $location['latitude'];
            $longitude = $location['longitude'];

            bot('sendLocation', [
                'chat_id' => $chat_id,
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);

            $keyboard = json_encode([
                'keyboard' => [
                    [
                        [
                            'text' => "✔️ Tasdiqlash",
                        ],
                    ],
                    [
                        [
                            'text' => "📍 Lokatsiya jo'natish",
                            'request_location' => true
                        ],
                    ],
                ],
                'resize_keyboard' => true
            ]);

            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Lokatsiya to'g'rimi? Agar o'zgartirmoqchi bo'lsangiz yangi lokatsiya yuboring!",
                'parse_mode' => 'html',
                'reply_markup' => $keyboard
            ]);

            addressLog($chat_id, $location_id, 1, $conn);
        } else {
            $checkOrganization = "SELECT o.id, o.title FROM user AS u 
                                INNER JOIN user_organization AS uo ON u.id = uo.user_id
                                INNER JOIN organizations AS o ON uo.organ_id = o.id
                                WHERE u.chat_id = $chat_id";
            $resultOrganization = $conn->query($checkOrganization);
            if ($resultOrganization->num_rows > 0) {
                $organization = $resultOrganization->fetch_assoc();
                $organ_id = $organization['id'];
                $organ_title = $organization['title'];

                $keyboard = json_encode([
                    'keyboard' => [
                        [
                            [
                                'text' => "✔️ Tasdiqlash",
                            ],
                        ],
                        [
                            [
                                'text' => "📍 Lokatsiya jo'natish",
                                'request_location' => true
                            ],
                        ],
                    ],
                    'resize_keyboard' => true
                ]);

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => "<b>Manzil: $organ_title</b>\n\nManzil to'g'rimi? Agar o'zgartirmoqchi bo'lsangiz yangi lokatsiya yuboring!",
                    'parse_mode' => 'html',
                    'reply_markup' => $keyboard
                ]);

                addressLog($chat_id, $organ_id, 2, $conn);
            }
        }
    }

    function getAddressInfo($chat_id, $conn) {
        $getAddressInfo = "SELECT * FROM address_log WHERE chat_id = $chat_id AND status = 1";
        $resultAddressInfo = $conn->query($getAddressInfo);
        if ($resultAddressInfo->num_rows > 0) {
            $address = $resultAddressInfo->fetch_assoc();
            $address_id = $address['address_id'];
            $address_type = $address['address_type'];

            if ($address_type == 1) {
                $sql = "SELECT * FROM user_location WHERE id = $address_id AND status IN (1, 2)";
                $result = $conn->query($sql);
                $location = $result->fetch_assoc();
                $latitude = $location['latitude'];
                $longitude = $location['longitude'];

                return ['type' => $address_type, 'latitude' => $latitude, 'longitude' => $longitude];
            } else if ($address_type == 2) {
                $sql = "SELECT * FROM organizations WHERE id = $address_id AND status = 1";
                $result = $conn->query($sql);
                $organization = $result->fetch_assoc();
                $title = $organization['title'];

                return ['type' => $address_type, 'title' => $title];
            }
        }
    }

    function saveUserPaymentLocation($chat_id, $user_id, $latitude, $longitude, $conn) {
        $checkLocation = "SELECT * FROM user_location WHERE user_id = $user_id";
        $resultLocation = $conn->query($checkLocation);
        if ($resultLocation->num_rows > 0) {
            $sql = 'UPDATE user_location SET latitude = '.$latitude.', longitude = '.$longitude.', status = 2 WHERE user_id = '.$user_id;
        } else {
            $sql = 'INSERT INTO user_location (user_id, longitude, latitude, status) VALUES ('.$user_id.', "'.$longitude.'", "'.$latitude.'", 2)';
        }
        $conn->query($sql);
    }

    function deactivateUserPaymentLocation($chat_id, $user_id, $conn) {
        $checkLocation = "SELECT * FROM user_location WHERE user_id = $user_id AND status = 2";
        $resultLocation = $conn->query($checkLocation);
        if ($resultLocation->num_rows > 0) {
            $sql = 'UPDATE user_location SET status = 0 WHERE user_id = '.$user_id;
            $conn->query($sql);
        }
    }

    function changeBalanceUser($chat_id, $perform, $value, $conn) {
        if ($perform == 0) {
            $sql = "UPDATE user SET balance = balance - $value WHERE chat_id = $chat_id";
        }
        $conn->query($sql);
    }

    function paymentType($chat_id, $type, $conn) {
        $checkUser = "SELECT * FROM payment_log WHERE chat_id = $chat_id AND status = 1";
        $resultUser = $conn->query($checkUser);
        if ($resultUser->num_rows > 0) {
            $sql = "UPDATE payment_log SET type = $type WHERE chat_id = $chat_id AND status = 1";
        } else {
            $sql = "INSERT INTO payment_log (chat_id, type) VALUES ($chat_id, $type)";
        }
        $conn->query($sql);

        checkAddress($chat_id, $conn);
    }

    function paymentMoney($chat_id, $user_id, $date, $conn) {
        $getOrders = 'SELECT o.id, m.price, o.count FROM orders AS o
                    INNER JOIN meals AS m ON o.meal_id = m.id
                    WHERE o.user_id = '.$user_id.' AND o.date = "'.$date.'" AND o.status = 0';
        $resultOrders = $conn->query($getOrders);
        if ($resultOrders->num_rows > 0) {
            $total = 0;
            while ($order = $resultOrders->fetch_assoc()) {
                $order_id = $order['id'];
                $order_price = $order['price'];
                $order_count = $order['count'];
                $total_price = $order_price * $order_count;
                $total += $total_price;

                $sql = "INSERT INTO payment_money (order_id, user_id) VALUES ($order_id, $user_id)";
                $conn->query($sql);

                $update = "UPDATE orders SET status = 1 WHERE id = $order_id AND user_id = $user_id";
                $conn->query($update);
            }
            changeBalanceUser($chat_id, 0, $total, $conn);
        }
    }

    function paymentCard($chat_id, $user_id, $date, $conn) {
        $getOrders = 'SELECT o.id, m.price, o.count FROM orders AS o
                    INNER JOIN meals AS m ON o.meal_id = m.id
                    WHERE o.user_id = '.$user_id.' AND o.date = "'.$date.'" AND o.status = 0';
        $resultOrders = $conn->query($getOrders);
        if ($resultOrders->num_rows > 0) {
            $total = 0;
            while ($order = $resultOrders->fetch_assoc()) {
                $order_id = $order['id'];
                $order_price = $order['price'];
                $order_count = $order['count'];
                $total_price = $order_price * $order_count;
                $total += $total_price;

                $sql = "INSERT INTO payment_card (order_id, user_id) VALUES ($order_id, $user_id)";
                $conn->query($sql);

                $update = "UPDATE orders SET status = 1 WHERE id = $order_id AND user_id = $user_id";
                $conn->query($update);
            }
            changeBalanceUser($chat_id, 0, $total, $conn);
        }
    }

    function finalMessage($chat_id, $conn) {
        $checkPaymentType = "SELECT * FROM payment_log WHERE chat_id = $chat_id AND status = 1";
        $resultPaymentType = $conn->query($checkPaymentType);
        if ($resultPaymentType->num_rows > 0) {
            $payment_log = $resultPaymentType->fetch_assoc();
            $payment_type = $payment_log['type'];

            $date = date('Y-m-d');
            $user_id = getUserId($chat_id, $conn);

            if ($payment_type == 1) {
                paymentMoney($chat_id, $user_id, $date, $conn);
            } else if ($payment_type == 2) {
                paymentCard($chat_id, $user_id, $date, $conn);
            }

            $getUserInfo = "SELECT * FROM user WHERE chat_id = $chat_id AND status = 1";
            $resultUserInfo = $conn->query($getUserInfo);
            $user = $resultUserInfo->fetch_assoc();
            $user_name = $user['first_name'];
            $user_phone = $user['phone_number'];

            $getMeal = 'SELECT m.title, m.price, o.count FROM meals AS m
                    INNER JOIN orders AS o ON o.meal_id = m.id
                    WHERE o.user_id = '.$user_id.' AND o.date = "'.$date.'" AND o.status = 1';
            $resultMeal = $conn->query($getMeal);
            if ($resultMeal->num_rows > 0) {
                $message = "#️⃣ <b>Buyurtma:</b> ".rand(1, 100)."\n\n";
                $total = 0;
                while ($meal = $resultMeal->fetch_assoc()) {
                    $meal_title = $meal['title'];
                    $meal_price = $meal['price'];
                    $meal_count = $meal['count'];
                    $total_price = $meal_price * $meal_count;
                    $total += $total_price;

                    $message .= "🫕 <b>$meal_title:</b> $meal_count ta\n<b>Narxi:</b> $total_price so'm\n\n";
                }

                $addressInfo = getAddressInfo($chat_id, $conn);
                if ($addressInfo['type'] == 1) {
                    $latitude = $addressInfo['latitude'];
                    $longitude = $addressInfo['longitude'];
                    $message .= "<b>💰 Umumiy: $total so'm</b>\n\n<b>👤 Mijoz:</b> $user_name\n<b>📞 Telefon raqam:</b> +$user_phone\n<b>📍 Manzil:</b>";
                } else {
                    $user_address = $addressInfo['title'];
                    $message .= "<b>💰 Umumiy: $total so'm</b>\n\n<b>👤 Mijoz:</b> $user_name\n<b>📞 Telefon raqam:</b> +$user_phone\n<b>📍 Manzil:</b> $user_address";
                }

                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    'text' => $message,
                    'parse_mode' => 'html',
                    'reply_markup' => $keyboard
                ]);

                if ($addressInfo['type'] == 1) {
                    bot('sendLocation', [
                        'chat_id' => $chat_id,
                        'latitude' => $latitude,
                        'longitude' => $longitude
                    ]);
                }
            }

            deactivateUserPaymentLocation($chat_id, $user_id, $conn);
            if ($payment_type == 1) {
                $message = "Buyurtmangiz muvaffaqiyatli qabul qilindi!";
                sendMessageNo($chat_id, $message);
                updateStep($chat_id, 1, 0, $conn);
                welcomeMenu($chat_id, $conn);
            } else if ($payment_type == 2) {
                $message = "Buyurtmangiz muvaffaqiyatli qabul qilindi!\n\nUshbu kartaga pul o'tkazing va to'laganingiz haqida botga rasm yuboring: ".PAYMENT_CARD;
                sendMessageNo($chat_id, $message);
                updateStep($chat_id, 1, 6, $conn);
            }
        }
    }

    function getDebts($chat_id, $conn) {
        $getDebts = "SELECT * FROM user WHERE chat_id = $chat_id AND status = 1";
        $resultDebts = $conn->query($getDebts);
        if ($resultDebts->num_rows > 0) {
            $user = $resultDebts->fetch_assoc();
            $user_balance = $user['balance'];

            if ($user_balance >= 0) {
                $message = "<b>💰 Balansingiz:</b> $user_balance so'm\n\nQarzingiz yo'q, xursandmiz!";
            } else {
                $message = "<b>💰 Qarzingiz:</b> $user_balance so'm\n\nTo'laganingiz haqida rasmni shuyerdan yuborishingiz mumkin!";
            }

            $keyboard = json_encode([
                'inline_keyboard' => [
                    [["text" => "🔙 Orqaga", "callback_data" => "back"]],
                ]
            ]);

            sendMessage($chat_id, $message, $keyboard);
        }
    }

    function savePaymentProof($chat_id, $user_id, $date, $file_id, $conn) {
        $imageFileId = bot('getFile', [
            'file_id' => $file_id
        ]);

        if (isset($imageFileId)) {
            $photo = 'https://api.telegram.org/file/bot'.BOT_API.'/'.$imageFileId->result->file_path;
            $data = file_get_contents($photo);
            $file_name = strtotime("Y-m-d H:i:s").rand(1000, 9999);
            $doc_name = $chat_id.$file_name;
            $path = "payment_files/$doc_name.jpg";
            file_put_contents($path, $data);

            $path_to_photo = "https://bots.abduazam.uz/ochil_dasturxon/".$path;
        }

        $sql = 'INSERT INTO payment_proof (user_id, date, image) VALUES ('.$user_id.', "'.$date.'", "'.$path_to_photo.'")';
        $conn->query($sql);
    }

    if (isset($message)) {
        typing($chat_id);
    }

    if ($text == "/start") {
        checkUser($chat_id, $conn);
    }

    // GET STEP
    $sql = "SELECT step_1, step_2 FROM action WHERE chat_id = $chat_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $step_1 = $row['step_1'];
        $step_2 = $row['step_2'];
    }

    // AUTHENTICATION
    if ($step_1 == 0) {
        // PHONE NUMBER
        if ($step_2 == 0) {
            if (isset($text) && strlen($text) >= 9 || isset($update->message->contact)) {
                $user_phone = "";
                $run = false;
                if (isset($message->contact)) {
                    $user_phone = trim($phone_number, "+");
                    $run = true;
                } else if (isset($text) && $text != "/start") {
                    $user_phone = $text;
                    $run = true;
                }

                if ($run === true) {
                    $table = "phone_number";
                    saveUser($chat_id, $table, $user_phone, $conn);
                    $message = "Ismingizni kiriting!\n(Abdurahmon)";
                    updateStep($chat_id, 0, 1, $conn);
                    sendMessageNo($chat_id, $message);
                }
            } else {
                if ($text != "/start") {
                    deleteMessage($chat_id, $message_id);
                    errorMessage($chat_id);
                }
            }
        }

        // FIRST NAME
        if ($step_2 == 1) {
            if (isset($text) && $text != "/start" && strlen($text) >= 3) {
                $table = "first_name";
                saveUser($chat_id, $table, $text, $conn);
                sendMessage($chat_id, $locationMessage, $locationKeyboard);
                updateStep($chat_id, 0, 2, $conn);
            } else {
                if ($text != "/start") {
                    deleteMessage($chat_id, $message_id);
                    errorMessage($chat_id);
                }
            }
        }

        // LOCATION OR ORGANIZATION
        if ($step_2 == 2) {
            if (isset($update->callback_query)) {
                if ($data == "location") {
                    $message = "Ovqat yuboriladigan manzilni lokatsiya orqali yuboring!";
                    $keyboard = json_encode([
                        'keyboard' => [
                            [
                                [
                                    'text' => "📍 Lokatsiya jo'natish",
                                    'request_location' => true
                                ],
                            ],
                        ],
                        'resize_keyboard' => true
                    ]);
                    deleteMessage($chat_id, $message_id);
                    sendMessage($chat_id, $message, $keyboard);
                    updateStep($chat_id, 0, 3, $conn);
                }

                if ($data == "organization") {
                    deleteMessage($chat_id, $message_id);
                    getOrganization($chat_id, $conn);
                    updateStep($chat_id, 0, 4, $conn);
                }
            } else {
                if ($text != "/start") {
                    deleteMessage($chat_id, $message_id);
                    errorMessage($chat_id);
                }
            }
        }

        // LOCATION
        if ($step_2 == 3) {
            if (isset($message->location)) {
                $latitude = $message->location->latitude;
                $longitude = $message->location->longitude;

                saveUserLocation($chat_id, $latitude, $longitude, $conn);
                updateStep($chat_id, 1, 0, $conn);
                welcomeMenu($chat_id, $conn);
            } else {
                if ($text != "/start") {
                    deleteMessage($chat_id, $message_id);
                    errorMessage($chat_id);
                }
            }
        }

        // ORGANIZATION
        if ($step_2 == 4) {
            if (isset($update->callback_query)) {
                if ($data == "back") {
                    deleteMessage($chat_id, $message_id);
                    sendMessage($chat_id, $locationMessage, $locationKeyboard);
                    updateStep($chat_id, 0, 2, $conn);
                }

                if (preg_match("/^[1-9][0-9]*$/", $data)) {
                    saveUserOrganization($chat_id, $data, $conn);
                    deleteMessage($chat_id, $message_id);
                    updateStep($chat_id, 1, 0, $conn);
                    welcomeMenu($chat_id, $conn);
                }
            } else {
                if ($text != "/start") {
                    deleteMessage($chat_id, $message_id);
                    errorMessage($chat_id);
                }
            }
        }
    }

    // ORDERING
    if ($step_1 == 1) {
        // SHOW MENU / PRESSING ORDER
        if ($step_2 == 0) {
            if (isset($update->callback_query)) {
                if ($data == "order") {
                    deleteMessage($chat_id, $message_id);
                    getMenu($chat_id, $conn);
                    updateStep($chat_id, 1, 1, $conn);
                }

                if ($data == "card") {
                    deleteMessage($chat_id, $message_id);
                    $user_id = getUserId($chat_id, $conn);
                    $date = date('Y-m-d');
                    showCard($chat_id, $user_id, $date, $conn);
                    updateStep($chat_id, 1, 3, $conn);
                }

                if ($data == "debts") {
                    deleteMessage($chat_id, $message_id);
                    getDebts($chat_id, $conn);
                    updateStep($chat_id, 1, 7, $conn);
                }
            } else {
                if ($text != "/start") {
                    deleteMessage($chat_id, $message_id);
                    errorMessage($chat_id);
                }
            }
        }

        // MENU
        if ($step_2 == 1) {
            if (isset($update->callback_query)) {
                $dataRaw = explode("_", $data);
                $dataMethod = $dataRaw[0];
                $messageCount = $dataRaw[1];

                for ($i = 0; $i < $messageCount; $i++) { 
                    deleteMessage($chat_id, $message_id-$i);
                }

                if ($data == "back_".$messageCount) {
                    updateStep($chat_id, 1, 0, $conn);
                    welcomeMenu($chat_id, $conn);
                }

                if (preg_match("/^[1-9][0-9]*$/", $dataMethod) && $data == $dataMethod."_".$messageCount) {
                    updateStep($chat_id, 1, 2, $conn);
                    getMeal($chat_id, $dataMethod, $conn);
                }
            } else {
                if ($text != "/start") {
                    deleteMessage($chat_id, $message_id);
                    errorMessage($chat_id);
                }
            }
        }

        // MEAL
        if ($step_2 == 2) {
            if (isset($update->callback_query)) {
                $dataRaw = explode("_", $data);
                $dataMethod = $dataRaw[0];
                $dataId = $dataRaw[1];

                if ($dataMethod == "back") {
                    deleteMessage($chat_id, $message_id);
                    getMenu($chat_id, $conn);
                    updateStep($chat_id, 1, 1, $conn);
                }

                if ($data == "save_order") {
                    deleteMessage($chat_id, $message_id);
                    saveOrder($chat_id, $conn);
                    updateStep($chat_id, 1, 3, $conn);
                }

                if ($dataMethod == "minus") {
                    minusMealCount($chat_id, $message_id, $dataId, $conn);
                }

                if ($dataMethod == "plus") {
                    plusMealCount($chat_id, $message_id, $dataId, $conn);
                }
            } else {
                if ($text != "/start") {
                    deleteMessage($chat_id, $message_id);
                    errorMessage($chat_id);
                }
            }
        }

        // CARD
        if ($step_2 == 3) {
            if (isset($update->callback_query)) {
                if ($data == "clear") {
                    $date = date('Y-m-d');
                    $user_id = getUserId($chat_id, $conn);
                    clearCard($chat_id, $user_id, $date, $conn);
                    deleteMessage($chat_id, $message_id);
                    showCard($chat_id, $user_id, $date, $conn);
                }

                if ($data == "menu") {
                    deleteMessage($chat_id, $message_id);
                    getMenu($chat_id, $conn);
                    updateStep($chat_id, 1, 1, $conn);
                }

                if ($data == "back") {
                    deleteMessage($chat_id, $message_id);
                    updateStep($chat_id, 1, 0, $conn);
                    welcomeMenu($chat_id, $conn);
                }

                if ($data == "order") {
                    deleteMessage($chat_id, $message_id);
                    sendMessage($chat_id, $paymentMessage, $paymentKeyboard);
                    updateStep($chat_id, 1, 4, $conn);
                }
            }
        }

        // PAYMENT TYPE
        if ($step_2 == 4) {
            if (isset($update->callback_query)) {
                deleteMessage($chat_id, $message_id);
                updateStep($chat_id, 1, 5, $conn);
                if ($data == "money") {
                    paymentType($chat_id, 1, $conn);
                }

                if ($data == "card") {
                    paymentType($chat_id, 2, $conn);
                }
            }
        }

        // LOCATION
        if ($step_2 == 5) {
            if (isset($text) && $text != "/start") {
                if ($text == "🔙 Orqaga") {
                    for ($i=0; $i < 3; $i++) { 
                        deleteMessage($chat_id, $message_id-$i);
                    }
                    sendMessage($chat_id, $paymentMessage, $paymentKeyboard);
                    updateStep($chat_id, 1, 4, $conn);
                }

                if ($text == "✔️ Tasdiqlash") {
                    finalMessage($chat_id, $conn);
                }
            } else if (isset($message->location)) {
                $latitude = $message->location->latitude;
                $longitude = $message->location->longitude;

                $user_id = getUserId($chat_id, $conn);
                saveUserPaymentLocation($chat_id, $user_id, $latitude, $longitude, $conn);
                checkAddress($chat_id, $conn);
            } else {
                if ($text != "/start") {
                    deleteMessage($chat_id, $message_id);
                    errorMessage($chat_id);
                }
            }
        }

        // PAYMENT PROOF
        if ($step_2 == 6) {
            if (isset($message->photo)) {
                if (isset($message->photo[2]->file_id)) {
                    $file_id = $message->photo[2]->file_id;
                } else if ($message->photo[1]->file_id) {
                    $file_id = $message->photo[1]->file_id;
                } else {
                    $file_id = $message->photo[0]->file_id;
                }

                $user_id = getUserId($chat_id, $conn);
                $date = date('Y-m-d');
                savePaymentProof($chat_id, $user_id, $date, $file_id, $conn);
                updateStep($chat_id, 1, 0, $conn);
                welcomeMenu($chat_id, $conn);
            }
        }

        // DEBTS
        if ($step_2 == 7) {
            if (isset($update->callback_query)) {
                if ($data == "back") {
                    deleteMessage($chat_id, $message_id);
                    updateStep($chat_id, 1, 0, $conn);
                    welcomeMenu($chat_id, $conn);
                }
            } else if (isset($message->photo)) {
                if (isset($message->photo[2]->file_id)) {
                    $file_id = $message->photo[2]->file_id;
                } else if ($message->photo[1]->file_id) {
                    $file_id = $message->photo[1]->file_id;
                } else {
                    $file_id = $message->photo[0]->file_id;
                }

                $user_id = getUserId($chat_id, $conn);
                $date = date('Y-m-d');
                savePaymentProof($chat_id, $user_id, $date, $file_id, $conn);
                updateStep($chat_id, 1, 0, $conn);
                welcomeMenu($chat_id, $conn);
            }
        }
    }