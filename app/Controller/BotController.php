<?php

namespace App\Controller;

use App\Models\Bot;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Valitron\Validator;

final class BotController
{

    public function create(Request $request, Response $response)
    {
        $result = [];
        $data = $request->getParsedBody();
        $v = new Validator($data);
        $rules = [
            'name' => ['required', ['lengthMin', 3], ['lengthMax', 10]],
            'client_id' => ['integer'],
            'tag' => ['required', ['lengthMin', 1], ['lengthMax', 1]],
            'level' => ['required', ['min', 10], ['max', 9999]],
            'limit_friend' => ['required', ['max', 10]],
            'limit_hunted' => ['required', ['max', 10]],
            'limit_ally' => ['required', ['max', 90]],
            'limit_enemy' => ['required', ['max', 90]],
            'active' => ['required', 'integer']
        ];
        $v->mapFieldsRules($rules);

        if (!$v->validate()) {
            return $response->withJson(['status' => false, 'message' => $v->errors()])->withStatus(400);
        }

        try {
            $bot = new Bot();
            $bot->name = $data['name'];
            $bot->client_id = ($data['client_id'] ?? null);
            $bot->tag_command = $data['tag'];
            $bot->level_tibia = $data['level'];
            $bot->limit_friend = $data['limit_friend'];
            $bot->limit_hunted = $data['limit_hunted'];
            $bot->limit_ally = $data['limit_ally'];
            $bot->limit_enemy = $data['limit_enemy'];
            $bot->external_id = ($data['external_id'] ?? null);
            $bot->active = $data['active'];

            if ($bot->save()) {
                $result = ['status' => true, 'message' => 'success', 'data' => ['id' => $bot->id]];
            } else {
                $result = ['status' => false, 'message' => 'error'];
            }
        } catch (Exception $ex) {
            $result = ['status' => false, 'message' => $ex->getMessage()];
        }
        return $response->withJson($result)->withStatus($result['status'] ? 201 : 400);
    }

    public function update(Request $request, Response $response, array $args)
    {
        $result = [];
        $data = $request->getParsedBody();
        $v = new Validator($data);
        $rules = [
            'name' => ['required', ['lengthMin', 3], ['lengthMax', 10]],
            'client_id' => ['integer'],
            'tag' => ['required', ['lengthMin', 1], ['lengthMax', 1]],
            'level' => ['required', ['min', 10], ['max', 9999]],
            'limit_friend' => ['required', ['max', 10]],
            'limit_hunted' => ['required', ['max', 10]],
            'limit_ally' => ['required', ['max', 90]],
            'limit_enemy' => ['required', ['max', 90]],
            'active' => ['required', 'integer']
        ];
        $v->mapFieldsRules($rules);

        if (!$v->validate()) {
            return $response->withJson(['status' => false, 'message' => $v->errors()])->withStatus(400);
        }

        try {
            if (!$args['id']) {
                throw new Exception('Id not found');
            }
            $bot = Bot::find($args['id']);
            $bot->name = $data['name'];
            $bot->client_id = ($data['client_id'] ?? null);
            $bot->tag_command = $data['tag'];
            $bot->level_tibia = $data['level'];
            $bot->limit_friend = $data['limit_friend'];
            $bot->limit_hunted = $data['limit_hunted'];
            $bot->limit_ally = $data['limit_ally'];
            $bot->limit_enemy = $data['limit_enemy'];
            $bot->external_id = ($data['external_id'] ?? null);
            $bot->active = $data['active'];

            if ($bot->save()) {
                $result = ['status' => true, 'message' => 'success'];
            } else {
                $result = ['status' => false, 'message' => 'error'];
            }
        } catch (Exception $ex) {
            $result = ['status' => false, 'message' => $ex->getMessage()];
        }
        return $response->withJson($result)->withStatus($result['status'] ? 200 : 400);
    }

    public function show(Request $request, Response $response, $args)
    {
        try {
            if ($args['id']) {
                $data = Bot::find($args['id']);
            } else {
                $data = Bot::all();
            }

            if ($data) {
                $result = ['status' => true, 'message' => 'success', 'data' => $data];
            } else {
                $result = ['status' => false, 'message' => 'error'];
            }
        } catch (Exception $ex) {
            $result = ['status' => false, 'message' => $ex->getMessage()];
        }
        return $response->withJson($result)->withStatus($result['status'] ? 200 : 404);
    }

    public function delete(Request $request, Response $response, $args)
    {
        try {
            if (!$args['id']) {
                throw new Exception('Id not found');
            }

            $bot = Bot::find($args['id']);
            if ($bot && $bot->delete()) {
                $result = ['status' => true, 'message' => 'success'];
            } else {
                $result = ['status' => false, 'message' => 'error'];
            }
        } catch (Exception $ex) {
            $result = ['status' => false, 'message' => $ex->getMessage()];
        }
        return $response->withJson($result)->withStatus($result['status'] ? 200 : 400);
    }
}
