<?php

/**
 * выводит пагинатор в разделе новостей/статей
 */
class Pager
{
    public int $limit;
    public int $total;
    public int $current;
    public int $totalPages;
    public int $totalButtons = 9;
    public array $buttons;
    public int $offset;

    public function __construct(int $limit, int $total)
    {
        $this->limit = $limit;
        $this->total = $total;
        $this->current = (int) (new Request())->get('page', 1);
        $this->totalPages = (int) ceil($total / $limit);

        if ($this->current > $this->totalPages) {
            (new Errors())->notFound();
            exit;
        }
    }

    /**
     * высчитывает отображаемые кнопки пагинатора
     */
    public function setButtons(): void
    {
        $offset = floor($this->totalButtons / 2);

        $start = ($this->current) - $offset;

        if (($start + $this->totalButtons) > $this->totalPages) {
            $start = $this->totalPages - $this->totalButtons + 1;
        }

        if ($start <= 0) {
            $start = 1;
        }

        $end = $start + $this->totalButtons - 1;
        if ($end > $this->totalPages) {
            $end = $this->totalPages;
        }

        $this->buttons = range($start, $end);
    }

    /**
     * вызывает отображение рендера
     */
    public function show(): void
    {
        (new View)->render('pager', ['pager' => $this]);
    }
}