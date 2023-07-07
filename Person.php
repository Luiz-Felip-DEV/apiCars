<?php 

class Person {
    private $id;
    private $nome;
    private $idade;
    private $email;
    private $senha;

    public function setId(int $id) :void
    {
        $this->id = $id;
    }

    public function getId() :int 
    {
        return $this->id;
    }

    public function setNome(string $nome) :void
    {
        $this->nome = $nome;
    }

    public function getNome() :string 
    {
        return $this->nome;
    }

    public function setIdade(int $idade) :void
    {
        $this->idade = $idade;
    }

    public function getIdade() :int 
    {
        return $this->idade;
    }

    public function setEmail(string $email) :void
    {
        $this->email = $email;
    }

    public function getEmail() :string 
    {
        return $this->email;
    }

    public function setSenha(string $senha) :void
    {
        $this->senha = $senha;
    }

    public function getSenha() :string 
    {
        return $this->senha;
    }

    private function connection()
    {
        return new \PDO('mysql:host=localhost;dbname=api;', 'root', '');
    }

    public function create() :array 
    {
        $con = $this->connection();
        $stmt = $con->prepare("INSERT INTO users VALUES (NULL,:_name, :_age, :_email, :_senha)");

        $stmt->bindValue(":_name", $this->getNome(), \PDO::PARAM_STR);
        $stmt->bindValue(":_age", $this->getIdade(), \PDO::PARAM_INT);
        $stmt->bindValue(":_email", $this->getEmail(), \PDO::PARAM_STR);
        $stmt->bindValue(":_senha", $this->getSenha(), \PDO::PARAM_STR);
        
        if ($stmt->execute()){
            $this->setId($con->lastInsertId());
            return $this->read();
        }
        return [];
    }

    public function read() :array 
    {
        $con = $this->connection();
        if ($this->getId() === 0){
            $stmt = $con->prepare("SELECT * FROM users");
            if ($stmt->execute()){
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }
        }else if ($this->getId() > 0){
            $stmt = $con->prepare("SELECT * FROM users WHERE id = :_id");
            $stmt->bindValue(":_id", $this->getId(), \PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }
        }
        return [];
    }

    public function update() :array 
    {
        $con = $this->connection();
        $stmt = $con->prepare("UPDATE users SET name = :_name, age = :_age, email = :_email, senha = :_senha WHERE id = :_id");

        $stmt->bindValue(":_name", $this->getNome(), \PDO::PARAM_STR);
        $stmt->bindValue(":_age", $this->getIdade(), \PDO::PARAM_INT);
        $stmt->bindValue(":_id", $this->getId(), \PDO::PARAM_INT);
        $stmt->bindValue(":_email", $this->getEmail(), \PDO::PARAM_STR);
        $stmt->bindValue(":_senha", $this->getSenha(), \PDO::PARAM_STR);

        if ($stmt->execute()){
            return $this->read();
        }
        return [];
    }

    public function delete() :array 
    {
        $con = $this->connection();
        $stmt = $con->prepare("DELETE FROM users WHERE id = :_id");

        $stmt->bindValue(":_id", $this->getId(), \PDO::PARAM_INT);

        if ($stmt->execute()){
            return  $this->read();
        }
    }

    public function createResponse($status_code, $mensagem){
        http_response_code($status_code);

        header('Content-Type: application/json');

        $response = array (
            'status_code'   => $status_code,
            'resp'          => $mensagem 
        );

        return json_encode((object) $response);
    }
}

?>