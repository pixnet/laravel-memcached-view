<?php

/**
 * PhpEngine
 *
 * Rewrite evaluatePath to use eval to render php instead of including file
 *
 */

namespace PIXNET\MemcachedView\Engines;

use Illuminate\View\Exception;
use Illuminate\View\Throwable;
use Illuminate\View\Engines\PhpEngine as IlluminatePhpEngine;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class PhpEngine extends IlluminatePhpEngine
{

    /**
     * Get the evaluated contents of the view at the given path.
     *
     * @param  string  $__path
     * @param  array   $__data
     * @return string
     */
    protected function evaluatePath($__path, $__data)
    {
        $obLevel = ob_get_level();

        ob_start();

        extract($__data, EXTR_SKIP);

        // We'll evaluate the contents of the view inside a try/catch block so we can
        // flush out any stray output that might get out before an error occurs or
        // an exception is thrown. This prevents any partial views from leaking.
        try {
            eval('?>' . $this->files->get($__path));
        } catch (Exception $e) {
            $this->handleViewException($e, $obLevel);
        } catch (Throwable $e) {
            $this->handleViewException(new FatalThrowableError($e), $obLevel);
        }

        return ltrim(ob_get_clean());
    }
}
