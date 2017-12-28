<?php

namespace Mosinas\SmsPilot;

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

    public function transform()
    {
        return [
            'id' => $this->id,
            'from' => $this->from,
            'to' => $this->to,
            'text' => $this->text,
        ];
    }
}