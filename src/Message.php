<?php

namespace SmsPilot;

use SmsPilot\Models\Massages;

class Message
{
    protected $id;
    protected $from;
    protected $to;
    protected $text;

    public function __construct(string $to, string $text, int $id = 1)
    {
        $this->id = $id;
        $this->from = config('smspilot.senderName');
        $this->to = $to;
        $this->text = $text;
    }

    public function transform($save = false)
    {
        if($save) {
            $message = $this->store();
            $this->id = $message->id;
        }

        return [
            'id' => $this->id,
            'from' => $this->from,
            'to' => $this->to,
            'text' => $this->text,
        ];
    }

    public function store()
    {
        $message = new \SmsPilot\Models\Message();
        $message->phone = $this->to;
        $message->text = $this->text;

        $message->save();

        return $message;
    }
}