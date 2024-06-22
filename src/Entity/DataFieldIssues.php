<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


class DataFieldIssues
{
    private $issues;

    public function getIssues(): ?array
    {
        return $this->issues;
    }

    public function setIssues(?array $issues): self
    {
        $this->issues = $issues;

        return $this;
    }


}