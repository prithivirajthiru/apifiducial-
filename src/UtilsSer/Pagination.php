<?php

namespace App\UtilsSer;


class Pagination
{
    private $pageno;
    private $limit;
    private $skip;
    private $count;
    private $nextpage;
    private $prevpage;
    private $totalpage;
    private $status;
    private $keyword;

    public function getPageNo(): ?int
    {
        return $this->pageno;
    }

    public function setPageNo(?int $pageno): self
    {
        $this->pageno = $pageno;

        return $this;
    }
    public function getKeyWord(): ?string
    {
        return $this->keyword;
    }

    public function setKeyWord(?string $keyword): self
    {
        $this->keyword = $keyword;

        return $this;
    }
    public function getSkip(): ?int
    {
        return $this->skip;
    }

    public function setSkip(?int $skip): self
    {
        $this->skip = $skip;

        return $this;
    }
    public function calculate()
    {
        $this->skip = ($this->getPageNo() - 1)*$this->limit;
        if ($this->limit > 0) {
            $this->totalpage = ($this->count + $this->limit - 1) / $this->limit;
        }

        if ($this->getPageNo() < $this->getTotalPage()) {
            $this->nextpage = $this->pageno + 1;
        } else {
            $this->nextpage = -1;
        }

        if ($this->pageno > 1) {
            $this->prevpage = $this->pageno - 1;
        } else {
            $this->prevpage = -1;
        }

    }
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }
    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): self
    {
        $this->count = $count;

        return $this;
    }
    public function getNextPage(): ?int
    {
        return $this->nextpage;
    }

    public function setNextPage(?int $nextpage): self
    {
        $this->nextpage = $nextpage;

        return $this;
    }
    public function getPrevPage(): ?int
    {
        return $this->prevpage;
    }

    public function setPrevPage(?int $prevpage): self
    {
        $this->prevpage = $prevpage;

        return $this;
    }

    public function getTotalPage(): ?int
    {
        return $this->totalpage;
    }

    public function setTotalPage(?int $totalpage): self
    {
        $this->totalpage = $totalpage;

        return $this;
    }
    public function getStatus(): ?array
    {
        return $this->status;
    }
    public function setStatus(array $status): self
    {
        $this->status = $status;

        return $this;
    }

}