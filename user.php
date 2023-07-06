<?php 

    require_once "person.php";

    header("Content-Type: application/json");
    $data = [];

    $fn     = $_REQUEST['fn']       ?? null;
    $id     = $_REQUEST['id']       ?? 0;
    $nome   = $_REQUEST['name']     ?? null;
    $idade  = $_REQUEST['age']      ?? null;
    $email  = $_REQUEST['email']    ?? null;
    $senha  = $_REQUEST['senha']    ?? null;

    $person = new person;

    $person->setId($id);

    if ($fn == 'create' && $nome != null && $idade !== null && $email !== null && $senha !== null){
        $person->setNome($nome);
        $person->setIdade($idade);
        $person->setEmail($email);
        $person->setSenha($senha);

        $data['user'] = $person->create();
    }

    if ($fn === 'read') {
        $data['user'] = $person->read();
    }

    if ($fn == 'update' && $id > 0 && $nome != null && $idade !== null && $email !== null && $senha !== null){
        $person->setNome($nome);
        $person->setIdade($idade);
        $person->setEmail($email);
        $person->setSenha($senha);
        $data['user'] = $person->update();
    }

    if ($fn === 'delete' && $id > 0) {
        $data['user'] = $person->delete();
    }

    $msg = '';

    switch ($fn){
        case 'create':
            $msg = 'Usuario criado com sucesso!';
            break;
        case 'update':
            $msg = 'Cadastro atualizado com sucesso!';
            break;
        case 'delete':
            $msg = 'Usuario excluido com sucesso!';
            break;
        case 'read':
            $msg = 'Usuarios listados com sucesso!';
            break;
    }
    

    if (empty($data)){
        die($result = $person->createResponse(500, [
            'msm'       => 'Erro ao acessar a api',
            'results'   => ''
        ]));
        return $result;
    }{
        die($result = $person->createResponse(200, [
            'msm'       => $msg,
            'results'   => $data
        ]));
        return $result; 
    }

