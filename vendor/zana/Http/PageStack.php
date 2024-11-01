<?php namespace Zana\Http;

use Zana\Session\Session;

class PageStack
{
    public static function push($currentUrl): void
    {
        // Retrieve the current page stack from the session
        $pageStack = Session::get('page_stack');
        // Use an empty array if not set
        $pageStack = ($pageStack === null || $pageStack === false) ? [] : $pageStack;

        $currentUrlParts = explode("/", $currentUrl);
        $uri =  end($currentUrlParts);

        // Avoid adding the same page multiple times
        if (end($pageStack) !== $currentUrl && $uri <> 'navigate-back' ) {
            $pageStack[] = $currentUrl; // Add the current URL to the stack

            // Limit the stack size to the last 5 pages
            if (count($pageStack) > 5) {
                array_shift($pageStack); // Remove the oldest page
            }

            // Update the session with the new page stack
            Session::set('page_stack', $pageStack);
        }
    }

    public static function pop():string
    {
        // Retrieve the current page stack from the session
        $pageStack = Session::get('page_stack');
        // Use an empty array if not set
        $pageStack = ($pageStack === null || $pageStack === false) ? [] : $pageStack;

        // Check if the page stack exists
        if (count($pageStack) > 1) {
            // Pop the current page
            array_pop($pageStack); // Remove the current url

            // Get the last url from the stack
            $lastUrl = array_pop($pageStack); // Get the previous url

            Session::set('page_stack', $pageStack);

            return $lastUrl;
        } else {
            // If there's no valid url to go back to, return the same url
            return $_SERVER['HTTP_REFERER'];
        }
    }

    /**
     * Get the current page on top of the stack without removing it
     * @return string|null
     */
    public static function peek(): ?string
    {
        $pageStack = Session::get('page_stack') ?? [];
        return end($pageStack); // Return the last page without removing it
    }

    public static function getStack():array
    {
        return Session::get('page_stack');   
    }

    public static function clear()
    {
        Session::delete('page_stack');
    }
}