<?php

namespace App\Repositories;

use App\Repositories\VoteRepositoryInterface;
use App\Models\Vote;
use http\Exception\InvalidArgumentException;

class VoteRepository implements VoteRepositoryInterface
{
    /**
     * The vote repository instance.
     *
     * @var \App\Models\Vote
     */
    private $_model;

    public function __construct (Vote $vote)
    {
        $this->_model = $vote;
    }

    public function newVote($vote_id, $name_voted, $ip)
    {
        // Vote exists
        $voteExists = $this->_model->where('vote_id', $vote_id)->where('ip', $ip)->get();

        if ($voteExists->count() > 0) {
            return new \Exception('Voto já computado anteriormente', 422);
        }

        $vote = $this->_model->fill([
            'vote_id' => $vote_id,
            'name_voted' => $name_voted,
            'ip' => $ip,
        ]);

        return $vote->save();
    }

    public function getResults($vote_id)
    {
        // Vote exists
        $votes = $this->_model->where('vote_id', $vote_id)->get();

        if ($votes->count() <= 0) {
            return new \Exception('Enquete não encontrada / inválida', 422);
        }


        $aVoteResult = [];
        $aVoteResult['total_votos'] = $votes->count();

        foreach ($votes as $vote) {
            if (array_key_exists($vote->name_voted, $aVoteResult)) {
                $voto = $aVoteResult[$vote->name_voted];
                $voto['votos']++;

                $aVoteResult[$vote->name_voted] = $voto;
            } else {
                $aVoteResult[$vote->name_voted] = [
                    'votos' => 1,
                    'nome' => $vote->name_voted
                ];
            }
        }

        return array_values($aVoteResult);

    }
}


