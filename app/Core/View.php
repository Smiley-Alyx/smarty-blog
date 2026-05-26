<?php

declare(strict_types=1);

namespace App\Core;

use Smarty\Smarty;

final class View
{
    private Smarty $smarty;

    public function __construct(
        string $templatesPath,
        string $compileDir,
        string $cacheDir,
        bool $debug = false,
    ) {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($templatesPath);
        $this->smarty->setCompileDir($compileDir);
        $this->smarty->setCacheDir($cacheDir);
        $this->smarty->caching = false;

        if ($debug) {
            $this->smarty->error_reporting = E_ALL;
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(string $template, array $data = [], string $layout = 'layout.tpl'): string
    {
        $this->smarty->clearAllAssign();
        $this->smarty->assign(array_merge(['year' => (int) date('Y')], $data));

        $content = $this->smarty->fetch($template);
        $this->smarty->assign('content', $content);

        return $this->smarty->fetch($layout);
    }
}
