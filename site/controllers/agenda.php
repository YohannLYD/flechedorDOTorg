<?php

use Kirby\Toolkit\Str;

return function ($kirby, $pages, $page) {
    $request = $kirby->request();

    // TODO adds "archive" button in the menu?
    if ($request->get('section') == 'archives') {
        $events = $page->children()->listed()->filterBy('fin', 'date <', date('Y-m-d'))->sortBy('fin', 'desc');
    } else {
        $events = $page->children()->listed()->filterBy('fin', 'date >=', date('Y-m-d'))->sortBy('debut', 'asc');
    }

    // group by month
    $groupedEvents = [];
    foreach ($events as $event) {
        if (!array_key_exists($event->monthDate(), $groupedEvents))
            $groupedEvents[$event->monthDate()] = [];
        $groupedEvents[$event->monthDate()][] = $event;
    }

    $filters = [];
    foreach ($events as $event) {
        foreach ($event->filters()->toData() as $filter)
            $filters[] = $filter;
    }
    $filters = array_unique($filters);

    return [
        'groupedEvents' => $groupedEvents,
        'filters' => $filters
    ];
};
