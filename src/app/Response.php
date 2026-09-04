<?php

class Response
{
    private function __construct(
        private string $header,
        private int $statusCode,
        private string $content
    ) {}
    public static function html(string $content, int $statusCode = 200): self
    {
        $header = "Content-Type: text/html; charset=utf-8";
        return new self($header, $statusCode, $content);
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        header($this->header);
        echo $this->content;
    }
}
