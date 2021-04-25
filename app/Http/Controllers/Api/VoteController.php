<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Http\Request;
use http\Exception;
use App\Repositories\VoteRepositoryInterface;

class VoteController extends Controller
{
    /**
     * The vote repository instance.
     *
     * @var \App\Repositories\VoteRepositoryInterface
     */
    protected $_voteRepository;

    public function __construct(
        VoteRepositoryInterface $voteRepository
    )
    {
        $this->_voteRepository = $voteRepository;
    }

    public function newVote(Request $request)
    {
        $dataRequest  = $request->all();

        $validator = \validator($dataRequest,
            [
                'vote_id' => 'required|integer',
                'name_voted' => 'required|string',
            ],
            [
                'vote_id.required' => 'É necessário informar a enquete de votação',
                'vote_id.integer' => 'Enquete de votação inválida',

                'name_voted.required' => 'É necessário informar o voto',
                'name_voted.string' => 'O campo voto não é válido'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['Dados inválidos!', $validator->getMessageBag()->getMessages()], 422);
        }

        try {
            $vote = $this->_voteRepository->newVote($dataRequest['vote_id'], $dataRequest['name_voted'], $request->getClientIp());

            if ($vote instanceof \Exception) {
                return response()->json($vote->getMessage(), $vote->getCode());
            }

            return response()->json('Voto computado com sucesso');
        } catch (Exception $e) {
            return response()->json([$e->getMessage()], $e->getCode());
        }
    }

    public function resultVote($voteId, Request $request)
    {

        $validator = \validator(['vote_id' => $voteId],
            [
                'vote_id' => 'required|integer',

            ],
            [
                'vote_id.required' => 'É necessário informar a enquete de votação',
                'vote_id.integer' => 'Enquete de votação inválida',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['Enquete não existe!', $validator->getMessageBag()->getMessages()], 422);
        }

        try {
            $vote = $this->_voteRepository->getResults($voteId);

            if ($vote instanceof \Exception) {
                return response()->json($vote->getMessage(), $vote->getCode());
            }

            return response()->json(['result' => $vote]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()], $e->getCode());
        }
    }
}
