<?php 

namespace App\Controller;
use App\Models\Teamspeak;
use Valitron\Validator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exception;

final class TeamspeakController {
    
    public function create(Request $request, Response $response){
        $result = [];
        $data = $request->getParsedBody();
        $v = new Validator($data);
        $rules = [
            'host' => ['required', ['lengthMin', 6], ['lengthMax', 15]],
            'port' => ['required', ['lengthMin', 4], ['lengthMax', 6]],
            'querylogin' => ['required',['lengthMin', 3], ['lengthMax', 50]],
            'querypassword' => ['required', ['lengthMin', 5], ['lengthMax', 50]],
            'queryport' => ['required', ['lengthMin', 4], ['lengthMax', 6]],
            'fk_id_bot' => ['required', 'integer'],
            'active' => ['required', 'integer']
        ];
        $v->mapFieldsRules($rules);

        if (!$v->validate()) {
            return $response->withJson(['status' => false, 'message' => $v->errors()])->withStatus(400);
        }

        try {
            $ts = new Teamspeak();
            $ts->host = $data['host'];
            $ts->port = $data['port'];
            $ts->querylogin = $data['querylogin'];
            $ts->querypassword = $data['querypassword'];
            $ts->queryport = $data['queryport'];
            $ts->fk_id_bot = $data['fk_id_bot'];
            $ts->active = $data['active'];

            if ($ts->save()) {
                $result = [ 'status' => true, 'message' => 'success', 'data' => [ 'id' => $ts->id ]];
            } else {
                $result = [ 'status' => false, 'message' => 'error'];
            }
        } catch (Exception $ex) {
            $result = ['status' => false, 'message' => $ex->getMessage()];
        }
        return $response->withJson($result)->withStatus($result['status'] ? 201 : 400);
    }

    public function update(Request $request, Response $response, array $args){
        $result = [];
        $data = $request->getParsedBody();
        $v = new Validator($data);
        $rules = [
            'host' => ['required', ['lengthMin', 6], ['lengthMax', 15]],
            'port' => ['required', ['lengthMin', 4], ['lengthMax', 6]],
            'querylogin' => ['required',['lengthMin', 4], ['lengthMax', 10]],
            'querypassword' => ['required', ['min', 5], ['max', 50]],
            'queryport' => ['required', ['min', 4], ['max', 6]],
            'fk_id_bot' => ['required', 'integer'],
            'active' => ['required', 'integer']
        ];
        $v->mapFieldsRules($rules);

        if (!$v->validate()) {
            return $response->withJson(['status' => false, 'message' => $v->errors()])->withStatus(400);
        }

        try {
            $ts = Teamspeak::find($args['id']);
            $ts->host = $data['host'];
            $ts->port = $data['port'];
            $ts->querylogin = $data['querylogin'];
            $ts->querypassword = $data['querypassword'];
            $ts->queryport = $data['queryport'];
            $ts->fk_id_bot = $data['fk_id_bot'];
            $ts->active = $data['active'];

            if ($ts->save()) {
                $result = [ 'status' => true, 'message' => 'success', 'data' => [ 'id' => $ts->id ]];
            } else {
                $result = [ 'status' => false, 'message' => 'error'];
            }
        } catch (Exception $ex) {
            $result = ['status' => false, 'message' => $ex->getMessage()];
        }
        return $response->withJson($result)->withStatus($result['status'] ? 200 : 400);
    }

    public function show(Request $request, Response $response, array $args){
        try {
            if ($args['id']) {
                $data = Teamspeak::find($args['id']);
            } else {
                $data = Teamspeak::all();
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

    public function delete(Request $request, Response $response, array $args){
        try {
            if (!$args['id']) {
                throw new Exception('Id not found');
            }

            $ts = Teamspeak::find($args['id']);
            if ($ts && $ts->delete()) {
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

?>