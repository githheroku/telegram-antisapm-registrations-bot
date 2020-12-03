<?php

declare(strict_types=1);

namespace Telegram\Service;

use Longman\TelegramBot\Entities\InlineKeyboard;
use Telegram\Map\QuestionKeyboard as QuestionKeyboardMap;

/**
 * @see https://github.com/php-telegram-bot/example-bot/blob/master/Commands/Keyboard/KeyboardCommand.php
 * @see https://github.com/php-telegram-bot/example-bot/blob/master/Commands/Keyboard/InlinekeyboardCommand.php
 * Class KeybordQuestion
 *
 * @package Telegram\Service
 */
class KeybordQuestion
{
    
    /**
     * Вернёт клавиатуру с вопросом для пользователя
     * @return array [reply_markup => InlineKeyboard]
     */
    public function getQuestion()
    {
        
        $keyboard = new InlineKeyboard([
            ['text' => 'Я бот!', 'callback_data' => QuestionKeyboardMap::CALLBACK_ANSWER_BOT],
            ['text' => 'Я человек!', 'callback_data' => QuestionKeyboardMap::CALLBACK_ANSWER_HUMAN],
        ]);
        
        return ['reply_markup' => $keyboard];
        
    }
}