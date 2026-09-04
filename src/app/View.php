<?php

class View
{
    public function __construct(private string $baseViewPath)
    {
    }

    public function render(string $viewPath, array $data = []): string
    {
        extract($data);
        $path = $this->baseViewPath . '/' . $viewPath;

        ob_start();
        include $path;
        $content = ob_get_clean();
        return $content;
    }
}
