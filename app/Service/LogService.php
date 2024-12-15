<?php

namespace App\Service;

use App\Repositories\Contracts\IDBRepository;

class LogService extends Service
{
    /**
     * Create a new class instance.
     */
    private IDBRepository $dbRepos;

    public function __construct(IDBRepository $dbRepos)
    {
        $this->dbRepos = $dbRepos;
    }

}