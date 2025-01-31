<?php

declare(strict_types=1);

namespace App\Utils;

use Illuminate\View\View;

/**
 * Class ActionWidget.
 *
 * This class provides a simple view for rendering action buttons with an associated route.
 */
class ActionWidget
{
    /**
     * Render the show button view.
     *
     * @param  string  $route  Route to the resource show action
     * @param  string  $html  HTML content for the button
     * @param  string  $classes  CSS classes for the button
     */
    public static function renderShowBtn(
        string $route,
        string $html = '<i class="fa fa-eye"></i>',
        string $classes = 'btn btn-sm btn-info'
    ): View {
        return view('_widgets.actions.showBtn', [
            'route' => $route,
            'html' => $html,
            'classes' => $classes,
        ]);
    }

    /**
     * Render the edit button view.
     *
     * @param  string  $route  Route to the resource update action
     * @param  string  $html  HTML content for the button
     * @param  string  $classes  CSS classes for the button
     */
    public static function renderUpdateBtn(
        string $route,
        string $html = '<i class="fa fa-pen"></i>',
        string $classes = 'btn btn-sm btn-warning text-white'
    ): View {
        return view('_widgets.actions.updateBtn', [
            'route' => $route,
            'html' => $html,
            'classes' => $classes,
        ]);
    }

    /**
     * Render the delete button view.
     *
     * @param  int|string  $id  ID of the resource to delete
     * @param  string  $route  Route to the resource delete action
     * @param  string  $html  HTML content for the button
     * @param  string  $classes  CSS classes for the button
     */
    public static function renderDeleteBtn(
        int|string $id,
        string $route,
        string $html = '<i class="fa fa-trash"></i>',
        string $classes = 'btn btn-sm btn-danger'
    ): View {
        return view('_widgets.actions.deleteBtn', [
            'id' => $id,
            'route' => $route,
            'html' => $html,
            'classes' => $classes,
        ]);
    }

    /**
     * Render the mark closed button view.
     *
     * @param  int|string  $id  ID of the resource to delete
     * @param  string  $route  Route to the resource delete action
     * @param  string  $html  HTML content for the button
     * @param  string  $classes  CSS classes for the button
     */
    public static function renderMarkClosedBtn(
        int|string $id,
        string $route,
        string $html = '<i class="mdi mdi-check-all"></i>',
        string $classes = 'btn btn-sm btn-success'
    ): View {
        return view('_widgets.actions.markclosedBtn', [
            'id' => $id,
            'route' => $route,
            'html' => $html,
            'classes' => $classes,
        ]);
    }

    /**
     * Render the go back button view.
     *
     * @param  string  $html  HTML content for the button
     * @param  string  $classes  CSS classes for the button
     */
    public static function renderGoBackBtn(
        string $html = '<i class="fa fa-arrow-left"></i>',
        string $classes = 'btn btn-sm btn-primary'
    ): View {
        return view('_widgets.actions.goBackBtn', [
            'html' => $html,
            'classes' => $classes,
        ]);
    }

    /**
     * Render the test connection button.
     *
     * @param  string  $route  Route to the resource action
     * @param  string  $html  HTML content for the button
     * @param  string  $classes  CSS classes for the button
     */
    public static function renderTestConnectionBtn(
        string $route,
        string $html = '<i class="fa fa-rss"></i>',
        string $classes = 'btn btn-sm btn-dark'
    ): View {
        return view('_widgets.actions.testConnectionBtn', [
            'route' => $route,
            'html' => $html,
            'classes' => $classes,
        ]);
    }
}
