<?php

declare(strict_types=1);

namespace App\View;

/**
 * 链接卡片渲染器
 * 生成安全转义的HTML片段，用于展示包含标题、描述和链接的卡片
 */
final class LinkCard
{
    private const DEFAULT_TITLE = '爱游戏 - 精彩游戏平台';
    private const DEFAULT_DESCRIPTION = '探索无限游戏世界，尽在爱游戏';
    private const DEFAULT_URL = 'https://m-i-game.com.cn';
    private const CARD_TEMPLATE = <<<HTML
<div class="link-card">
    <div class="link-card-content">
        <h3 class="link-card-title">%s</h3>
        <p class="link-card-description">%s</p>
        <a href="%s" class="link-card-url" target="_blank" rel="noopener noreferrer">%s</a>
    </div>
</div>
HTML;

    private string $title;
    private string $description;
    private string $url;

    public function __construct(
        string $title = self::DEFAULT_TITLE,
        string $description = self::DEFAULT_DESCRIPTION,
        string $url = self::DEFAULT_URL
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
    }

    /**
     * 设置卡片标题
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * 设置卡片描述
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * 设置链接URL
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * 生成转义后的HTML卡片
     */
    public function render(): string
    {
        $escapedTitle = htmlspecialchars($this->title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedDescription = htmlspecialchars($this->description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedUrl = htmlspecialchars($this->url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $displayUrl = htmlspecialchars(
            $this->truncateUrl($this->url),
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );

        return sprintf(
            self::CARD_TEMPLATE,
            $escapedTitle,
            $escapedDescription,
            $escapedUrl,
            $displayUrl
        );
    }

    /**
     * 静态工厂方法：快速创建默认卡片
     */
    public static function createDefault(): self
    {
        return new self();
    }

    /**
     * 从数据数组创建卡片
     *
     * @param array{title?: string, description?: string, url?: string} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'] ?? self::DEFAULT_TITLE,
            $data['description'] ?? self::DEFAULT_DESCRIPTION,
            $data['url'] ?? self::DEFAULT_URL
        );
    }

    /**
     * 截断URL用于显示
     */
    private function truncateUrl(string $url): string
    {
        $parsed = parse_url($url);
        if ($parsed === false || !isset($parsed['host'])) {
            return $url;
        }

        $host = $parsed['host'];
        $path = $parsed['path'] ?? '';

        if (mb_strlen($host . $path) > 40) {
            $path = mb_substr($path, 0, 20) . '...';
        }

        return $host . $path;
    }
}