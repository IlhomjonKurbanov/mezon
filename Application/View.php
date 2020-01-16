<?php
namespace Mezon\Application;

/**
 * Class View
 *
 * @package Mezon
 * @subpackage View
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/06)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Base class for all views
 */
class View implements \Mezon\Application\ViewInterface
{

    /**
     * View's name
     *
     * @var string
     */
    protected $ViewName = '';

    /**
     * Constructor
     *
     * @param string $ViewName
     *            View name to be rendered
     */
    public function __construct(string $ViewName = '')
    {
        $this->ViewName = $ViewName;
    }

    /**
     * Method renders content from view
     *
     * @param string $ViewName
     *            View name to be rendered
     * @return string Generated content
     */
    public function render(string $ViewName = ''): string
    {
        if ($ViewName === '') {
            $ViewName = $this->ViewName;
        }

        if ($ViewName === '') {
            $ViewName = 'default';
        }

        if (method_exists($this, 'view' . $ViewName)) {
            return call_user_func([
                $this,
                'view' . $ViewName
            ]);
        }

        throw (new \Exception('View ' . $ViewName . ' was not found'));
    }

    /**
     * Method returns view name
     *
     * @return string view name
     */
    public function getViewName(): string
    {
        return $this->ViewName;
    }
}