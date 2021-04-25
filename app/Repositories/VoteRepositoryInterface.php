<?php

namespace App\Repositories;

use http\Exception;

interface VoteRepositoryInterface
{
    /**
     * Seta um novo voto
     *
     * @param $vote_id
     * @param $name_voted
     * @param $ip
     *
     * @return bool | Exception
     *
     */
    public function newVote($vote_id, $name_voted, $ip);

    /**
     * Retorna o resultado de uma votação
     *
     * @param $vote_id
     *
     * @return array | Exception
     *
     */
    public function getResults ($vote_id);

}


