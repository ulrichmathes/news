<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * ViewHelper to get the target out of the typolink
 *
 * # Example: Basic Example
 * # Description: {relatedLink.uri} is defined as "123 _blank"
 * <code>
 * <f:link.typolink parameter="{relatedLink.uri}" target="{n:targetLink(link:relatedLink.uri)}">Link</f:link.typolink>
 * </code>
 * <output>
 * A link to the page with uid 123 and target set to "_blank"
 * </output>
 */
class TargetLinkViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('link', 'string', 'Link', true);
        $this->registerArgument('forceBlankForExternal', 'bool', 'Force blank for external links', false, true);
    }

    /**
     * Returns the correct target of a typolink
     *
     * @return mixed
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $params = explode(' ', $arguments['link']);

        // The target is on the 2nd place and must start with a '_'
        if (count($params) >= 2 && str_starts_with($params[1], '_')) {
            return $params[1];
        }

        if (($arguments['forceBlankForExternal'] ?? true) && (str_starts_with($params[0], 'https://') || str_starts_with($params[0], 'http://'))) {
            return '_blank';
        }

        return '';
    }
}
