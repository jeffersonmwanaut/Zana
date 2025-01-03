<?php namespace Zana\HTMLTag;

class HTMLTag {
    protected $tag;
    protected $attributes = [];
    protected $content = [];
    protected $selfClosing = false;

    public function __construct($tag, $selfClosing = false) {
        $this->tag = $tag;
        $this->selfClosing = $selfClosing; // Set the self-closing property
    }

    public function setAttribute($name, $value) {
        $this->attributes[$name] = $value;
        return $this;
    }

    public function getAttribute($name)
    {
        return $this->attributes[$name];
    }

    public function addContent($content) {
        if (!$this->selfClosing) { // Only add content if the tag is not self-closing
            $this->content[] = $content;
        }
        return $this;
    }

    public function clearContent() {
        $this->content = [];
        return $this;
    }

    protected function renderAttributes() {
        $attrString = '';
        foreach ($this->attributes as $name => $value) {
            $attrString .= sprintf(' %s="%s"', htmlspecialchars($name), htmlspecialchars($value));
        }
        return $attrString;
    }

    public function render() {
        if ($this->selfClosing) {
            // Render self-closing tag
            return sprintf('<%s%s />', $this->tag, $this->renderAttributes());
        } else {
            // Render regular tag
            $contentHtml = '';
            foreach ($this->content as $item) {
                if (is_string($item)) {
                    $contentHtml .= $item; // If it's a string, just append it
                } elseif ($item instanceof HTMLTag) {
                    $contentHtml .= $item->render(); // If it's an HTMLTag, render it
                }
            }
            return sprintf('<%s%s>%s</%s>', $this->tag, $this->renderAttributes(), $contentHtml, $this->tag);
        }
    }
}