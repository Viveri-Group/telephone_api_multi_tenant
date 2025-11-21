<?php

namespace App\Action\PhoneBook;

class GetPhoneBookEntriesKeyedAction
{
    public function handle(string $key = 'phone_number'): array
    {
        $phoneBookEntries = (new GetPhoneBookEntriesAction())->handle();

        return $phoneBookEntries
            ->keyBy($key)
            ->toArray();
    }
}
