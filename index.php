<?php
    require('vendor/autoload.php');
   
    use GraphQL\Type\Definition\ObjectType;
    use GraphQL\Type\Definition\Type;
    use GraphQL\Type\Schema;
    use GraphQL\GraphQL;

    try{

        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
            'echo' => [
                'type' => Type::string(),
                'args' => [
                    'message' => ['type' => Type::string()],                    
                ],
                'resolve' => function ($rootValue, $args){
                    return $rootValue['prefix'] . $args['message'];
                }
            ],
        ],
    ]);

    $schema = new Schema([
        'query' => $queryType,
    ]);

    $rawInput = file_get_contents('php://input');

    $input = json_decode($rawInput,true);

    $query = $input['query'];

    $variableValues = isset($input['variables']) ? $input['variables'] : null;

    $rootValue = ['prefix'=>'Você disse: '];

    $result = GraphQL:: executeQuery($schema,$query,$rootValue,null,$variableValues);

    $output = $result->toArray();

    }catch(\Exception $e){
        $output = [
            'error'=>[
                'message' => $e->getMessage()
            ]
        ];
    }

    header('Content-Type: application/json; charset=UTF-8');

    echo json_encode($output);

?>